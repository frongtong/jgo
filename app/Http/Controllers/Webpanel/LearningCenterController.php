<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Webpanel\LogsController;
use App\Models\Backend\LearningCenterBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningCenterController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'learning-center';

    public function index()
    {
        $data = LearningCenterBanner::firstOrCreate(
            ['id' => 1],
            ['status' => 'on']
        );

        $navs = [
            [
                'url' => 'javascript:void(0)',
                'name' => 'ศูนย์การเรียนรู้',
                'last' => 0,
            ],
            [
                'url' => "$this->segment/$this->folder",
                'name' => 'แบนเนอร์',
                'last' => 1,
            ],
        ];

        return view("$this->prefix.pages.$this->folder.edit", [
            'prefix' => $this->prefix,
            'segment' => $this->segment,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['nullable', 'in:on,off'],
        ]);

        try {
            DB::beginTransaction();

            $data = LearningCenterBanner::firstOrCreate(
                ['id' => 1],
                ['status' => 'on']
            );

            $data->status = $request->status ?? 'on';

            if ($file = $request->file('banner_image')) {
                $path = 'upload/learning-center';

                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }

                if ($data->image_url) {
                    $oldImagePath = public_path($data->image_url);

                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $filename = 'banner-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $filename);

                $data->image_url = $path . '/' . $filename;
            }

            $data->save();

            DB::commit();

            return view("$this->prefix.alert.success", [
                'url' => url("$this->segment/$this->folder"),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            LogsController::logInsert(
                $e->getLine(),
                url()->current(),
                $e->getMessage(),
                'backend'
            );

            return view("$this->prefix.alert.alert", [
                'url' => url()->current(),
                'title' => 'ไม่สามารถทำรายการได้',
                'text' => 'กรุณาลองใหม่อีกครั้ง',
                'icon' => 'error',
            ]);
        }
    }
}
