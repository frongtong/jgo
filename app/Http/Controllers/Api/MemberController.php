<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Authuse\MemberAuth;

use App\Models\Backend\Member;
use App\Models\Backend\MemberProfile;

class MemberController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

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

            /*
            |--------------------------------------------------------------------------
            | Create Parent
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Create Member
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            
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

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $r)
        {
            $member = MemberAuth::where(function ($query) use ($r) {

                $query->where('email', $r->email)
                    ->orWhere('username', $r->email);

            })->first();

            if (!$member) {

                return response()->json([

                    'status' => false,

                    'message' => 'ไม่พบผู้ใช้งาน'

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Password
            |--------------------------------------------------------------------------
            */

            if (!Hash::check(
                $r->password,
                $member->password
            )) {

                return response()->json([

                    'status' => false,

                    'message' => 'รหัสผ่านไม่ถูกต้อง'

                ]);
            }

        /*
        |--------------------------------------------------------------------------
        | Create Token
        |--------------------------------------------------------------------------
        */

            $token = $member
                ->createToken('member_token')
                ->plainTextToken;

            return response()->json([

                'status' => true,

                'message' => 'Success',

                'token' => $token,

                'results' => [

                    'member' => $member,

                ]

            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

    public function profile(Request $r)
    {
        $member = Member::find($r->member_id);

        if (!$member) {

            return response()->json([

                'status' => false,

                'message' => 'ไม่พบข้อมูลสมาชิก',

            ]);
        }

        return response()->json([

            'status' => true,

            'results' => [

                'member' => $member,

                'profile' => MemberProfile::where(
                    'member_id',
                    $member->id
                )->first(),

            ]

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