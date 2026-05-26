<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\Backend\CustomerModel;
use App\Models\Backend\Logs_listModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\LogsModel;
use Illuminate\Support\Facades\DB;


class LogsController extends Controller
{
    public static function send_line($message, $token)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=$message");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $headers = array("Content-type: application/x-www-form-urlencoded", "Authorization: Bearer $token",);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    public static function save_logbackend($type_log, $error_log, $error_line, $error_url)
    {
        $data = new LogsModel;
        $data->type_log = $type_log;
        $data->error_log = $error_log;
        $data->error_line = $error_line;
        $data->error_url = $error_url;
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if ($data->save()) {
            $message = "Emax_V02 \n เกิดข้อผิดพลาดทางด้านโปรแกรม (Code:$data->id) \n Log ที่ได้รับแจ้ง : $error_log \n Website Link : $error_url \n บรรทัดที่มีปัญหา : $error_line";
            $token = 'VF3nsFQ35D2TVJ4AOAFGtxrxmLNebA1gYBfhuHBgTVR';
            // $token = 'n37ZmpR7n8bUzo9UkclYXMQXYpBjN13niv8OubMpgEV'; 
            LogsController::send_line($message, $token);
            return $data->id;
        }
    }

    public static function logInsert($error_line,$error_url,$error_log,$type_log){
        DB::beginTransaction();
        $date = date('Y-m-d');
        $check = LogsModel::where('date',$date)->first();
        if($check){
           $data = $check;
        }else{
            $data = new LogsModel();
            $data->date = $date;
            $data->save();
        }

        $lists = new Logs_listModel();
        $lists->log_id = $data->id;
        $lists->type = $type_log;
        $lists->env = env('APP_ENV');
        $lists->date = date('Y-m-d H:i:s');
        $lists->line = $error_line;
        $lists->url = $error_url;
        $lists->desc = $error_log;
        if($lists->save())
        {

        }
        DB::commit();
    }
}
