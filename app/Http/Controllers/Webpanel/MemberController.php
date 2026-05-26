<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\Member;
use App\Models\Backend\MemberProfile;

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

        $query = $query->with('profile');
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
            'parent'
        ])->find($id);

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
            'row' => $data
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

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store($request, $id = null)
    {
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
            $member->apply_date  = now();
            $member->updated_at  = now();

            $member->save();
            /*
            |--------------------------------------------------------------------------
            | Member Profile
            |--------------------------------------------------------------------------
            */

            $profile = MemberProfile::firstOrNew([
                'member_id' => $member->id
            ]);

            $profile->title_th         = $request->title_th;
            $profile->first_name_th    = $request->first_name_th;
            $profile->last_name_th     = $request->last_name_th;

            $profile->title_en         = $request->title_en;
            $profile->first_name_en    = $request->first_name_en;
            $profile->last_name_en     = $request->last_name_en;

            $profile->nickname         = $request->nickname;

            $profile->citizen_id       = $request->citizen_id;

            $profile->gender           = $request->gender;
            $profile->birth_date       = $request->birth_date;
            $profile->age              = $request->age;

            $profile->marital_status   = $request->marital_status;

            $profile->phone            = $request->phone;
            $profile->line_id          = $request->line_id;
            $profile->facebook         = $request->facebook;

            $profile->email_contact    = $request->email_contact;
            $profile->emergency_phone  = $request->emergency_phone;

            $profile->house_no         = $request->house_no;
            $profile->village_no       = $request->village_no;
            $profile->village_name     = $request->village_name;

            $profile->province_id      = $request->province_id;
            $profile->district_id      = $request->district_id;
            $profile->subdistrict_id   = $request->subdistrict_id;
            $profile->postcode         = $request->postcode;

            $profile->current_address  = $request->current_address;

            $profile->same_as_house_registration =
                $request->same_as_house_registration ?? 0;

            $profile->house_registration_address =
                $request->house_registration_address;

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

            DB::commit();

            return view("$this->prefix.alert.success", [
                'url' => url("$this->segment/$this->folder")
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
