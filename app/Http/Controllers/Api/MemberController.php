<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Authuse\MemberAuth;

use App\Models\Backend\Member;
use App\Models\Backend\MemberProfile;

class MemberController extends Controller
{
    

    public function register(Request $r)
    { 
        try {

            /*
            |--------------------------------------------------------------------------
            | Validate
            |--------------------------------------------------------------------------
            */

            $checkEmail = Member::where(
                'email',
                $r->email
            )->first();

            if ($checkEmail) {

                return response()->json([

                    'status' => false,

                    'message' => 'อีเมลนี้ถูกใช้งานแล้ว',

                ]);
            }

            $checkUsername = Member::where(
                'username',
                $r->username
            )->first();

            if ($checkUsername) {

                return response()->json([

                    'status' => false,

                    'message' => 'Username นี้ถูกใช้งานแล้ว',

                ]);
            }

            DB::beginTransaction();

        

            $parentPassword =
                rand(100000, 999999);

            $parent = new Member();

            $parent->member_code =
                'PAR' . date('ymd') . rand(1000,9999);

            $parent->username =
                'parent_' . time();

            $parent->email =
                'parent_' . time() . '@jgo.com';

            $parent->password =
                bcrypt($parentPassword);

            $parent->parent_plain_password =
                $parentPassword;

            $parent->type = 'parent';

            $parent->status = 'pending';

            $parent->apply_date = now();

            $parent->created_at = now();
            $parent->updated_at = now();

            $parent->save();


            $member = new Member();

            $member->member_code =
                '๋JGO' . date('ymd') . rand(1000,9999);

            $member->username =
                $r->username;

            $member->email =
                $r->email;

            $member->password =
                bcrypt($r->password);

            $member->type = 'applicant';

            $member->parent_id =
                $parent->id;

            $member->status = 'pending';

            $member->apply_date = now();

            $member->created_at = now();
            $member->updated_at = now();

            $member->save();

            $member->parents()->attach($parent->id);

          

            
            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'สมัครสมาชิกสำเร็จ',

                'results' => [

                    'member' => $member,

                   
                    'parent' => [

                        'username' =>
                            $parent->username,

                        'email' =>
                            $parent->email,

                        'password' =>
                            $parentPassword,

                    ]

                ]

            ]);

        } catch (\Throwable $e) {

            DB::rollback();

            return response()->json([

                'status' => false,

                'message' => 'เกิดข้อผิดพลาด',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

            ], 500);
        }
    }

 

    public function login(Request $r)
    {
        $validated = $r->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $member = MemberAuth::where(function ($query) use ($validated) {
            $query->where('email', $validated['email'])
                ->orWhere('username', $validated['email']);
        })->first();

        if (!$member || !Hash::check($validated['password'], $member->password)) {
            return response()->json([
                'status' => false,
                'message' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
            ], 401);
        }

        if ($member->status === 'inactive') {
            return response()->json([
                'status' => false,
                'message' => 'บัญชีนี้ถูกระงับการใช้งาน',
            ], 403);
        }

        if (!in_array($member->type, ['applicant', 'parent'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'ประเภทบัญชีไม่รองรับการเข้าสู่ระบบนี้',
            ], 403);
        }

        $token = $member
            ->createToken($member->type . '_token')
            ->plainTextToken;

        $account = Member::with('profile')->findOrFail($member->id);
        // $relatedMembers = collect();
        // $relatedParents = collect();

        // if ($member->type === 'parent') {
        //     $relatedMembers = $account->linkedChildren()
        //         ->with('profile')
        //         ->orderBy('members.id')
        //         ->get();
        // } else {
        //     $relatedParents = $account->parents()
        //         ->with('profile')
        //         ->select([
        //             'members.id',
        //             'members.member_code',
        //             'members.username',
        //             'members.email',
        //             'members.status',
        //         ])
        //         ->orderBy('members.id')
        //         ->get();
        // }

        $accountData = $account->toArray();
        if ($member->type === 'parent') {
            $accountData['password'] = $account->getRawOriginal('parent_plain_password');
        }

        return response()->json([
            'status' => true,
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'token' => $token,
            'results' => [
                'type' => $member->type,
                'member' => $accountData,
                'url' => $request->getSchemeAndHttpHost(),
                // 'related_members' => $relatedMembers,
                // 'related_parents' => $relatedParents,
            ],
        ]);
    }
        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

    public function profile(Request $r)
    {
        $memberId = $r->input('member_id', $r->user()->id);
        $member = Member::with('profile')->find($memberId);

        if (!$member) {

            return response()->json([

                'status' => false,

                'message' => 'ไม่พบข้อมูลสมาชิก',

            ], 404);
        }

        $isOwner = (int) $r->user()->id === (int) $member->id;
        $isLinkedParent = $r->user()->type === 'parent'
            && DB::table('member_parent')
                ->where('member_id', $member->id)
                ->where('parent_id', $r->user()->id)
                ->exists();

        if (!$isOwner && !$isLinkedParent) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่มีสิทธิ์ดูข้อมูลสมาชิกนี้',
            ], 403);
        }

        return response()->json([

            'status' => true,

            'results' => [

                'member' => $member,
                'profile' => $member->profile,

            ]

        ]);
    }

    public function createParent(Request $request, $memberId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
        ]);

        $member = Member::where('id', $memberId)
            ->where('type', 'applicant')
            ->first();

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลสมาชิก',
            ], 404);
        }

        if ((int) $request->user()->id !== (int) $member->id) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่มีสิทธิ์จัดการข้อมูลผู้ปกครองของสมาชิกนี้',
            ], 403);
        }

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
            $parent->password = Hash::make($plainPassword);
            $parent->parent_plain_password = $plainPassword;
            $parent->type = 'parent';
            $parent->status = 'active';
            $parent->apply_date = now();
            $parent->save();

            $nameParts = preg_split('/\s+/', trim($validated['name']), 2);
            $parentProfile = MemberProfile::create([
                'member_id' => $parent->id,
                'citizen_id' => '',
                'first_name_th' => $nameParts[0],
                'last_name_th' => $nameParts[1] ?? '',
            ]);

            $member->parents()->attach($parent->id);

            // รองรับโค้ดเก่าที่ยังอ่านผู้ปกครองจาก members.parent_id
            if (empty($member->parent_id)) {
                $member->parent_id = $parent->id;
                $member->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'สร้างบัญชีผู้ปกครองสำเร็จ',
                'results' => [
                    'parent' => [
                        'id' => $parent->id,
                        'member_code' => $parent->member_code,
                        'username' => $parent->username,
                        'email' => $parent->email,
                        'name' => trim($parentProfile->first_name_th . ' ' . $parentProfile->last_name_th),
                        'password' => $plainPassword,
                        'status' => $parent->status,
                    ],
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json([
                'status' => false,
                'message' => 'ไม่สามารถสร้างบัญชีผู้ปกครองได้',
            ], 500);
        }
    }

    public function parents(Request $request, $memberId)
    {
        $member = Member::where('id', $memberId)
            ->where('type', 'applicant')
            ->first();

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลสมาชิก',
            ], 404);
        }

        $isOwner = (int) $request->user()->id === (int) $member->id;
        $isLinkedParent = $request->user()->type === 'parent'
            && DB::table('member_parent')
                ->where('member_id', $member->id)
                ->where('parent_id', $request->user()->id)
                ->exists();

        if (!$isOwner && !$isLinkedParent) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่มีสิทธิ์ดูข้อมูลผู้ปกครองของสมาชิกนี้',
            ], 403);
        }

        $parents = $member->parents()
            ->with('profile')
            ->select([
                'members.id',
                'members.member_code',
                'members.username',
                'members.email',
                'members.parent_plain_password',
                'members.status',
                'members.apply_date',
                'members.created_at',
            ])
            ->orderBy('member_parent.created_at', 'desc')
            ->get();

        $parentResults = $parents->map(function ($parent) {
            return [
                'id' => $parent->id,
                'member_code' => $parent->member_code,
                'username' => $parent->username,
                'email' => $parent->email,
                'name' => trim(
                    optional($parent->profile)->first_name_th . ' ' .
                    optional($parent->profile)->last_name_th
                ),
                'password' => $parent->getRawOriginal('parent_plain_password'),
                'status' => $parent->status,
                'apply_date' => $parent->apply_date,
                'created_at' => $parent->created_at,
                'profile' => $parent->profile,
            ];
        });

        return response()->json([
            'status' => true,
            'results' => [
                'member_id' => $member->id,
                'parents' => $parentResults,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $memberId = $request->input('member_id', $request->user()->id);
        $member = Member::find($memberId);

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลสมาชิก',
            ], 404);
        }

        $isOwner = (int) $request->user()->id === (int) $member->id;
        $isLinkedParent = $request->user()->type === 'parent'
            && DB::table('member_parent')
                ->where('member_id', $member->id)
                ->where('parent_id', $request->user()->id)
                ->exists();

        if (!$isOwner && !$isLinkedParent) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่มีสิทธิ์แก้ไขโปรไฟล์สมาชิกนี้',
            ], 403);
        }

        $profile = MemberProfile::firstOrNew(['member_id' => $member->id]);
        $requiredRule = $profile->exists ? 'sometimes' : 'required';

        $validated = $request->validate([
            'citizen_id' => [$requiredRule, 'string', 'max:20'],
            'title_th' => ['nullable', 'string', 'max:20'],
            'first_name_th' => [$requiredRule, 'string', 'max:100'],
            'last_name_th' => [$requiredRule, 'string', 'max:100'],
            'title_en' => ['nullable', 'string', 'max:20'],
            'first_name_en' => ['nullable', 'string', 'max:100'],
            'last_name_en' => ['nullable', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'in:ชาย,หญิง,อื่นๆ'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'marital_status' => ['nullable', 'in:โสด,สมรส,หย่า,หม้าย'],
            'phone' => ['nullable', 'string', 'max:20'],
            'line_id' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'email_contact' => ['nullable', 'email', 'max:150'],
            'emergency_phone' => ['nullable', 'string', 'max:20'],
            'house_no' => ['nullable', 'string', 'max:100'],
            'village_no' => ['nullable', 'string', 'max:20'],
            'village_name' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'integer'],
            'district_id' => ['nullable', 'integer'],
            'subdistrict_id' => ['nullable', 'integer'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'current_address' => ['nullable', 'string'],
            'same_as_house_registration' => ['nullable', 'boolean'],
            'house_registration_address' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profileFields = [
            'citizen_id', 'title_th', 'first_name_th', 'last_name_th',
            'title_en', 'first_name_en', 'last_name_en', 'nickname',
            'gender', 'birth_date', 'age', 'marital_status', 'phone',
            'line_id', 'facebook', 'email_contact', 'emergency_phone',
            'house_no', 'village_no', 'village_name', 'province_id',
            'district_id', 'subdistrict_id', 'postcode', 'current_address',
            'same_as_house_registration', 'house_registration_address',
        ];

        $oldImage = null;
        $newImage = null;

        try {
            DB::beginTransaction();

            $profile->fill(collect($validated)->only($profileFields)->all());

            if ($request->hasFile('profile_image')) {
                $path = 'upload/member';
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }

                $oldImage = $profile->profile_image;
                $file = $request->file('profile_image');
                $filename = 'member-' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $filename);
                $profile->profile_image = $path . '/' . $filename;
                $newImage = $profile->profile_image;
            }

            $profile->member_id = $member->id;
            $profile->save();

            DB::commit();

            if ($oldImage && is_file(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }

            return response()->json([
                'status' => true,
                'message' => 'บันทึกข้อมูลโปรไฟล์สำเร็จ',
                'results' => [
                    'member' => $member->fresh(),
                    'profile' => $profile->fresh(),
                    'url' => $request->getSchemeAndHttpHost(),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newImage && is_file(public_path($newImage))) {
                unlink(public_path($newImage));
            }

            report($e);

            return response()->json([
                'status' => false,
                'message' => 'ไม่สามารถบันทึกข้อมูลโปรไฟล์ได้',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([

            'status' => true,

            'message' => 'Logout success'

        ]);
    }
}
