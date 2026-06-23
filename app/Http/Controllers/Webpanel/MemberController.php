<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Backend\Member;
use App\Models\Backend\MemberProfile;
use App\Models\Backend\MemberEducation;
use App\Models\Backend\MemberTrainingCourse;

class MemberController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'member';

    /*
    |--------------------------------------------------------------------------
    | List Items
    |--------------------------------------------------------------------------
    */

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);

        $query = Member::query();

        $query = $query->with('profile')->withCount('parents');
        $query = $query->where('type', '=', 'applicant');
        if ($search) {

            $query = $query->where(function ($q) use ($search) {

                $q->where('member_code', 'LIKE', '%' . trim($search) . '%')

                    ->orWhereHas('profile', function ($profile) use ($search) {

                        $profile->where('first_name_th', 'LIKE', '%' . trim($search) . '%')
                            ->orWhere('last_name_th', 'LIKE', '%' . trim($search) . '%')
                            ->orWhere('phone', 'LIKE', '%' . trim($search) . '%');
                    });
            });
        }

        $query = $query->orderBy('id', 'desc');

        return $query->paginate($paginate);
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $items = $this->items($request->all());

        $items->pages = new \stdClass();
        $items->pages->start =
            ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => [
                'url' => "javascript:void(0)",
                'name' => "ระบบสมาชิก",
                'last' => 0
            ],
            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "สมาชิก",
                'last' => 1
            ],
        ];

        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Add
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $navs = [
            '0' => [
                'url' => "javascript:void(0)",
                'name' => "ระบบสมาชิก",
                'last' => 0
            ],
            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "สมาชิก",
                'last' => 0
            ],
            '2' => [
                'url' => "$this->segment/$this->folder/add",
                'name' => "Add",
                'last' => 1
            ],
        ];

        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
public function edit(Request $request, $id)
{
    $data = Member::with([
        'profile',
        'parents',
        'educations',
        'trainingCourses'
    ])->findOrFail($id);

    // แยกข้อมูลการศึกษาตามระดับ
$studying = MemberEducation::where('member_id', $id)
    ->where('study_status', 'studying')
    ->first();

$educations = MemberEducation::where('member_id', $id)
    ->where('study_status', '!=', 'studying')
    ->get()
    ->keyBy('education_level');

$educationData = [
      'studying' => $studying,

    'lower_secondary' => $educations->get('lower_secondary'),
    'upper_secondary' => $educations->get('upper_secondary'),
    'vocational' => $educations->get('vocational'),
    'high_vocational' => $educations->get('high_vocational'),
    'bachelor' => $educations->get('bachelor'),
    'master' => $educations->get('master'),
    'doctorate' => $educations->get('doctorate'),
    'other' => $educations->get('other'),
];
    $navs = [
        '0' => [
            'url' => "javascript:void(0)",
            'name' => "ระบบสมาชิก",
            'last' => 0
        ],
        '1' => [
            'url' => "$this->segment/$this->folder",
            'name' => "สมาชิก",
            'last' => 0
        ],
        '2' => [
            'url' => "$this->segment/$this->folder/edit/$id",
            'name' => "Edit",
            'last' => 1
        ],
    ];

    return view("$this->prefix.pages.$this->folder.edit", [
        'segment' => $this->segment,
        'prefix' => $this->prefix,
        'folder' => $this->folder,
        'navs' => $navs,
        'row' => $data,

        // Education
        'educationData' => $educationData,

    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        }

        $datas = Member::find(explode(',', $request->id));

        if ($datas) {

            foreach ($datas as $data) {

                if (@$data->profile->profile_image) {

                    $oldImage = public_path($data->profile->profile_image);

                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                Member::destroy($data->id);
            }

            return response()->json(true);
        }

        return response()->json(false);
    }

    /*
    |--------------------------------------------------------------------------
    | Insert / Update
    |--------------------------------------------------------------------------
    */

    public function insert(Request $request)
    {
        return $this->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    public function view($id)
    {
        return redirect(url("$this->segment/$this->folder/edit/$id"));
    }

    public function createParent($id)
    {
        $member = Member::where('id', $id)
            ->where('type', 'applicant')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            do {
                $suffix = Str::lower(Str::random(10));
                $username = 'parent_' . $member->id . '_' . $suffix;
            } while (Member::where('username', $username)->exists());

            do {
                $memberCode = 'PAR' . now()->format('ymd') . random_int(100000, 999999);
            } while (Member::where('member_code', $memberCode)->exists());

            $plainPassword = Str::random(12);

            $parent = new Member();
            $parent->member_code = $memberCode;
            $parent->username = $username;
            $parent->email = $username . '@jgo.com';
            $parent->password = bcrypt($plainPassword);
            $parent->parent_plain_password = $plainPassword;
            $parent->type = 'parent';
            $parent->status = 'active';
            $parent->apply_date = now();
            $parent->created_by = Auth::guard('admin')->id();
            $parent->save();

            $member->parents()->attach($parent->id);

            if (empty($member->parent_id)) {
                $member->parent_id = $parent->id;
                $member->save();
            }

            DB::commit();

            return redirect(url("$this->segment/$this->folder/edit/$id"))
                ->with('success', 'สร้างบัญชีผู้ปกครองสำเร็จ')
                ->with('parent_credentials', [
                    'username' => $parent->username,
                    'email' => $parent->email,
                    'password' => $plainPassword,
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect(url("$this->segment/$this->folder/edit/$id"))
                ->with('error', 'ไม่สามารถสร้างบัญชีผู้ปกครองได้');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store($request, $id = null)
    {
        $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'training' => ['nullable', 'array'],
            'training.training_id' => ['nullable', 'integer'],
            'training.program_type' => ['nullable', 'string', 'max:255'],
            'training.institution_name' => ['nullable', 'string', 'max:255'],
            'training.start_month_year' => ['nullable', 'date_format:Y-m'],
            'training.end_month_year' => ['nullable', 'date_format:Y-m', 'after_or_equal:training.start_month_year'],
        ]);

        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Member
            |--------------------------------------------------------------------------
            */

            if ($id == null) {

                /*
    |--------------------------------------------------------------------------
    | Create Parent Auto
    |--------------------------------------------------------------------------
    */

                $parent = new Member();

                $parent->created_at = now();

                $parent->member_code =
                    'PAR' . date('ymd') . rand(1000, 9999);

                /*
    |--------------------------------------------------------------------------
    | Generate Username
    |--------------------------------------------------------------------------
    */

                $parentUsername =
                    'parent_' . time();

                /*
    |--------------------------------------------------------------------------
    | Generate Password
    |--------------------------------------------------------------------------
    */


                /*
    |--------------------------------------------------------------------------
    | Generate Email
    |--------------------------------------------------------------------------
    */

                $parentEmail =
                    'parent_' . time() . '@jgo.com';

                $parent->username =
                    $parentUsername;

                $parent->email =
                    $parentEmail;

                $parentPassword =
                    rand(100000, 999999);

                $parent->password =
                    bcrypt($parentPassword);

                $parent->parent_plain_password =
                    $parentPassword;

                $parent->type =
                    'parent';

                $parent->status =
                    'active';

                $parent->apply_date =
                    now();

                $parent->created_by =
                    auth()->id();

                $parent->save();


                /*
    |--------------------------------------------------------------------------
    | Create Applicant
    |--------------------------------------------------------------------------
    */

                $member = new Member();

                $member->created_at = now();

                $member->member_code =
                    'JGO' . date('ymd') . rand(1000, 9999);

                $member->parent_id =
                    $parent->id;

                $member->type =
                    'applicant';
            } else {

                $member = Member::find($id);
            }
            $member->email       = $request->email;
            $member->username    = $request->username;
            if ($request->password) {
                $member->password =
                    bcrypt($request->password);
            }
            $member->created_by = Auth::guard('admin')->id();
            $member->status      = $request->status ?? 'pending';
            if (empty($member->apply_date)) {
                $member->apply_date = now();
            }
            $member->updated_at  = now();

            $member->save();

            if ($id === null && isset($parent)) {
                $member->parents()->attach($parent->id);
            }
            /*
            |--------------------------------------------------------------------------
            | Member Profile
            |--------------------------------------------------------------------------
            */

            $profile = MemberProfile::firstOrNew([
                'member_id' => $member->id
            ]);

            $profile->fill($request->only([
                'title_th',
                'first_name_th',
                'last_name_th',
                'title_en',
                'first_name_en',
                'last_name_en',
                'nickname',
                'citizen_id',
                'gender',
                'birth_date',
                'age',
                'marital_status',
                'phone',
                'line_id',
                'facebook',
                'email_contact',
                'emergency_phone',
                'house_no',
                'village_no',
                'village_name',
                'province_id',
                'district_id',
                'subdistrict_id',
                'postcode',
                'current_address',
                'same_as_house_registration',
                'house_registration_address',
            ]));

            /*
            |--------------------------------------------------------------------------
            | Upload Image
            |--------------------------------------------------------------------------
            */

            $path = 'upload/member';

            if ($request->file('profile_image')) {

                $file = $request->file('profile_image');

                if ($profile->profile_image) {

                    $oldImage = public_path($profile->profile_image);

                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                $fileName =
                    $path . '/member-' . time() . '.' .
                    $file->getClientOriginalExtension();

                $file->move(public_path($path), $fileName);

                $profile->profile_image = $fileName;
            }

            $profile->save();

       $educationData = [

    'studying' => $request->studying,

    'lower_secondary' => $request->lower_secondary,

    'upper_secondary' => $request->upper_secondary,

    'vocational' => $request->vocational,

    'high_vocational' => $request->high_vocational,

    'bachelor' => $request->bachelor,

    'master' => $request->master,

    'doctorate' => $request->doctorate,

    'other' => $request->other,

];

       foreach ($educationData as $level => $data) {

    if (empty($data)) {
        continue;
    }

    // ข้ามถ้าไม่มีข้อมูลเลย
    if (
        empty($data['institution_name']) &&
        empty($data['major']) &&
        empty($data['note'])
    ) {
        continue;
    }

    MemberEducation::updateOrCreate(

        [
            'id' => $data['id'] ?? null,
            'member_id' => $member->id,
        ],

        [
            'member_id'        => $member->id,

            // กรณี studying ให้ใช้ค่าที่ผู้ใช้เลือก
            'education_level'  => $level == 'studying'
                ? ($data['education_level'] ?? null)
                : $level,

            'institution_name' => $data['institution_name'] ?? null,

            'major'            => $data['major'] ?? null,

            'start_month'      => $data['start_month'] ?? null,

            'start_year'       => $data['start_year'] ?? null,

            'note'             => $data['note'] ?? null,

            'study_status'     => $level == 'studying'
                ? 'studying'
                : 'graduated',
        ]
    );
}

            $training = $request->input('training', []);
            $trainingId = $training['training_id'] ?? null;
            $hasTrainingData = !empty($training['program_type'])
                || !empty($training['institution_name'])
                || !empty($training['start_month_year'])
                || !empty($training['end_month_year']);

            if ($hasTrainingData) {
                MemberTrainingCourse::updateOrCreate(
                    [
                        'training_id' => $trainingId,
                        'member_id' => $member->id,
                    ],
                    [
                        'program_type' => $training['program_type'] ?? null,
                        'institution_name' => $training['institution_name'] ?? null,
                        'start_month_year' => $training['start_month_year'] ?? null,
                        'end_month_year' => $training['end_month_year'] ?? null,
                    ]
                );
            } elseif ($trainingId) {
                MemberTrainingCourse::where('member_id', $member->id)
                    ->where('training_id', $trainingId)
                    ->delete();
            }

            DB::commit();

            return view("$this->prefix.alert.success", [
                'url' => url("$this->segment/$this->folder"),
                'parent_credentials' => isset($parent) ? [
                    'username' => $parent->username,
                    'email' => $parent->email,
                    'password' => $parentPassword,
                ] : null,
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return view("$this->prefix.alert.alert", [
                'url' => url()->current(),
                'title' => "ไม่สามารถทำรายการได้",
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $member = Member::find($request->id);

        if (!$member) {
            return response()->json(false);
        }

        $member->status = $request->status;
        $member->save();

        return response()->json(true);
    }
}
