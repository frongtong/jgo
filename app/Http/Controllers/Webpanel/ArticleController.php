<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Article; 
use App\Models\Backend\ArticleCategory1; 
use App\Models\Backend\ArticleCategory2;

class ArticleController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'article';

    public function index(Request $request)
    {
        $items = Article::with([
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
                'name' => 'บทความ',
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
            ArticleCategory1::where(
                'status',
                'on'
            )
            ->orderBy('name_th')
            ->get();
        $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'บทความ',
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
            Article::findOrFail($id);

        $mainCategories =
            ArticleCategory1::orderBy(
                'name_th'
            )->get();

        $subCategories =
            ArticleCategory2::where(
                'category1_id',
                $data->main_category_id
            )->get();

            $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'บทความ',
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

                $data = Article::findOrFail($id);

            } else {

                $data = new Article();
            }

            $data->title = $request->title;

            $data->article_category1_id =
                $request->article_category1_id;

            $data->article_category2_id =
                $request->article_category2_id;

            $data->short_description =
                $request->short_description;

            $data->description =
                $request->description;

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

                $path = 'upload/article';

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

            /*
            |--------------------------------------------------------------------------
            | BANNER IMAGE
            |--------------------------------------------------------------------------
            */

            if ($file = $request->file('banner_image')) {

                $path = 'upload/article';

                if (!file_exists(public_path($path))) {

                    mkdir(
                        public_path($path),
                        0777,
                        true
                    );
                }

                $filename =
                    'banner-' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path($path),
                    $filename
                );

                $data->banner_image_url =
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
            Article::find(
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
            Article::find(
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

        return ArticleCategory2::where(
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

                Article::where(
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