<?php

namespace App\Http\Controllers\Webpanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Authuse\Admin;

class AuthController extends Controller
{
    protected $prefix = 'back-end';
    public function getLogin()
    {
        if (Auth::guard('admin')->id() != null) {
            return redirect('webpanel');
        } else {
            return view("$this->prefix.auth.login", [
                'css' => [""],
                'prefix' => $this->prefix
            ]);
        }
    }
    public function postLogin(Request $request)
    {
        try{
            $username = $request->username;
            $password = $request->password;
            $remember = ($request->remember == 'on') ? true : false;
            if (Auth::guard('admin')->attempt(['email' => $username, 'password' => $password], $remember))
            {
                $member = Admin::find(Auth::guard('admin')->id());
                if ($member->isActive != "Y") {
                    return redirect('webpanel\login')->with(['error' => 'ไม่สามารถใช้งานได้ กรุณาติดต่อผู้ดูแล !']);
                } else {
                    $arr = [
                        'status' => '200',
                        'result' => 'success',
                        'message' => 'ดำเนินการสำเร็จ'
                    ];
                }
            }
            else
            {
                $arr = [
                    'status' => '500',
                    'result' => 'error',
                    'title' => 'ไม่สามารถดำเนินรายการได้',
                    'text' => 'ไม่พบข้อมูลผู้ใช้หรือรหัสผ่านผิด !'
                ];
            }
        }catch (\Exception $e) {
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            LogsController::logInsert($error_line,$error_url,$error_log,$type_log);
            $arr = [
                'status' => '500',
                'result' => 'error',
                'title' => 'ไม่สามารถทำรายการได้',
                'text' => 'กรุณาทำรายการใหม่อีกครั้ง !'
            ];
        }
        echo json_encode($arr);
    }

    public function logOut()
    {
        if (!Auth::guard('admin')->logout()) {
            return redirect("webpanel\login");
        }
    }
}
