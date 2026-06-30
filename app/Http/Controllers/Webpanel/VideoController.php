<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Video; 
use App\Models\Backend\VideoCategory1; 
use App\Models\Backend\VideoCategory2;

class VideoController extends Controller
{
     protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'video';

    public function index(Request $request)
    {
        $items = Video::with([
            'mainCategory',
            'subCategory'
        ]);

        if ($request->search) {

            $items->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );
        }

        $items = $items
            ->orderBy('id', 'desc')
            ->paginate(10);

        if ($items->total() > 0) {

            $items->pages = new \stdClass();

            $items->pages->start =
                ($items->perPage() * $items->currentPage())
                - $items->perPage();
        }
        $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'วีดีโอ',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder",
                'name' => 'รายการ',
                'last' => 1
            ]
        ];
        return view(
            "$this->prefix.pages.$this->folder.index",
            [
                'prefix'  => $this->prefix,
                'segment' => $this->segment,
                'folder'  => $this->folder,
                'navs'    => $navs,
                'items'   => $items
            ]
        );
    }

    public function add()
    {
        $mainCategories =
            VideoCategory1::where(
                'status',
                'on'
            )
            ->orderBy('name_th')
            ->get();
        $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'วีดีโอ',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder",
                'name' => 'add',
                'last' => 1
            ]
        ];

        return view(
            "$this->prefix.pages.$this->folder.add",
            [
                'prefix' => $this->prefix,
                'segment' => $this->segment,
                'folder' => $this->folder,
                'navs'    => $navs,
                'mainCategories' => $mainCategories
            ]
        );
    }

    public function insert(Request $request)
    {
        return $this->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    public function edit($id)
    {
        $data =
            Video::findOrFail($id);

        $mainCategories =
            VideoCategory1::orderBy(
                'name_th'
            )->get();

        $subCategories =
            VideoCategory2::where(
                'category1_id',
                $data->main_category_id
            )->get();

            $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'วีดีโอ',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder",
                'name' => 'add',
                'last' => 1
            ]
        ];


        return view(
            "$this->prefix.pages.$this->folder.edit",
            [
                'prefix' => $this->prefix,
                'segment' => $this->segment,
                'folder' => $this->folder,
                'data' => $data,
                'navs'    => $navs,
                'mainCategories' => $mainCategories,
                'subCategories' => $subCategories
            ]
        );
    }

   
    public function store(Request $request, $id = null)
    {
        try {

            DB::beginTransaction();

            if ($id) {

                $data = Video::findOrFail($id);

            } else {

                $data = new Video();
            }

            $data->title = $request->title;

            $data->youtube_url = $request->youtube_url;

            $data->main_category_id =
                $request->main_category_id;

            $data->sub_category_id =
                $request->sub_category_id;

            $data->published_at =
                $request->published_at;

            $data->status =
                $request->status ?? 'on';

            /*
            |--------------------------------------------------------------------------
            | COVER IMAGE
            |--------------------------------------------------------------------------
            */

            if ($file = $request->file('cover_image')) {

                $path = 'upload/video';

                if (!file_exists(public_path($path))) {

                    mkdir(
                        public_path($path),
                        0777,
                        true
                    );
                }

                $filename =
                    'cover-' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path($path),
                    $filename
                );

                $data->cover_image_url =
                    $path . '/' . $filename;
            }

            $data->save();

            DB::commit();

            return view(
                "$this->prefix.alert.success",
                [
                    'url' => url(
                        "$this->segment/$this->folder"
                    )
                ]
            );

        } catch (\Exception $e) {

            DB::rollback();

            LogsController::logInsert(
                $e->getLine(),
                url()->current(),
                $e->getMessage(),
                'backend'
            );

            return view(
                "$this->prefix.alert.alert",
                [
                    'url' => url()->current(),
                    'title' => 'ไม่สามารถทำรายการได้',
                    'text' => 'กรุณาลองใหม่อีกครั้ง',
                    'icon' => 'error'
                ]
            );
        }
    }



    public function destroy(
        Request $request
    ) {

        $item =
            Video::find(
                $request->id
            );

        if ($item) {

            $item->delete();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function updateStatus(
        Request $request
    ) {

        $item =
            Video::find(
                $request->id
            );

        if ($item) {

            $item->status =
                $request->status;

            $item->save();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function getSubCategory(
        $id
    ) {

        return VideoCategory2::where(
            'category1_id',
            $id
        )
        ->where(
            'status',
            'on'
        )
        ->orderBy(
            'name_th'
        )
        ->get();
    }

    public function updateSortOrder(
        Request $request
    ) {

        if (
            $request->has('sort')
        ) {

            foreach (
                $request->sort
                as $id => $sort
            ) {

                Video::where(
                    'id',
                    $id
                )->update([
                    'sort_order' => $sort
                ]);
            }
        }

        return response()->json([
            'status' => true
        ]);
    }
}
