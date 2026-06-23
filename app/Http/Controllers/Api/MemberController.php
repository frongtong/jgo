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
        $relatedMembers = collect();
        $relatedParents = collect();

        if ($member->type === 'parent') {
            $relatedMembers = $account->linkedChildren()
                ->with('profile')
                ->orderBy('members.id')
                ->get();
        } else {
            $relatedParents = $account->parents()
                ->select([
                    'members.id',
                    'members.member_code',
                    'members.username',
                    'members.email',
                    'members.status',
                ])
                ->orderBy('members.id')
                ->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'token' => $token,
            'results' => [
                'type' => $member->type,
                'member' => $account,
                'related_members' => $relatedMembers,
                'related_parents' => $relatedParents,
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
            $parent->type = 'parent';
            $parent->status = 'active';
            $parent->apply_date = now();
            $parent->save();

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
            ->select([
                'members.id',
                'members.member_code',
                'members.username',
                'members.email',
                'members.status',
                'members.apply_date',
                'members.created_at',
            ])
            ->orderBy('member_parent.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'results' => [
                'member_id' => $member->id,
                'parents' => $parents,
            ],
        ]);
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
