<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\JobApplication;


class DashboardController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'dashboard';
    protected $folder = 'dashboard';

    public function index(Request $request)
    {
        $statuses = JobApplication::statusLabels();
        $statusCounts = JobApplication::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $applicationSummary = [
            'all' => [
                'label' => 'ใบสมัครทั้งหมด',
                'count' => JobApplication::count(),
                'url' => url("$this->segment/jobapplication"),
                'class' => 'bg-light-primary',
            ],
        ];

        foreach ($statuses as $status => $label) {
            $applicationSummary[$status] = [
                'label' => $label,
                'count' => (int) ($statusCounts[$status] ?? 0),
                'url' => url("$this->segment/jobapplication?status=$status"),
                'class' => match ($status) {
                    JobApplication::STATUS_NEW => 'bg-light-warning',
                    JobApplication::STATUS_REVIEWING => 'bg-light-primary',
                    JobApplication::STATUS_INTERVIEW => 'bg-light-info',
                    JobApplication::STATUS_PASSED => 'bg-light-success',
                    JobApplication::STATUS_FAILED => 'bg-light-danger',
                    default => 'bg-light',
                },
            ];
        }

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "", "last" => 0],
            // '0' => ['url' => "javascript:void(0)", 'name' => "Dashboard", "last" => 0],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'navs' => $navs,
            'applicationSummary' => $applicationSummary,
        ]);
    }

    public static function uploadimage_text(Request $request)
    {

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('uploads/texteditor/'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('uploads/texteditor/' . $fileName);
            $msg = "อัพโหลดรูปภาพสำเร็จ";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
