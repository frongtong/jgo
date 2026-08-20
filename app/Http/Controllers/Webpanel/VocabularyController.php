<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Vocabulary;
use App\Models\Backend\VoCategory1;
use App\Models\Backend\VoCategory2;

class VocabularyController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'vocabulary';

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $paginate = Arr::get($parameters, 'total', 15);

        $query = Vocabulary::with([
            'mainCategory',
            'subCategory'
        ])->withCount('items');

        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'LIKE',
                    '%' . trim($search) . '%'
                );
            });
        }

        return $query
            ->orderBy('id', 'desc')
            ->paginate($paginate);
    }

    public function index(Request $request)
    {
        $items = $this->items($request);

        $items->pages = new Vocabulary();
        $items->pages->start =
            ($items->perPage() * $items->currentPage())
            - $items->perPage();

        $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'คำศัพท์',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder",
                'name' => 'รายการ',
                'last' => 1
            ]
        ];
        $mainCategories = VoCategory1::orderBy(
            'name_th',
            'asc'
        )->get();

        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'mainCategories' => $mainCategories,
            'navs' => $navs
        ]);
    }

    public function add()
    {
        $mainCategories = VoCategory1::orderBy(
            'name_th',
            'asc'
        )->get();

        $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'คำศัพท์',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder/add",
                'name' => 'Add',
                'last' => 1
            ]
        ];

        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'mainCategories' => $mainCategories,
            'navs' => $navs
        ]);
    }

    public function edit($id)
    {
        $data = Vocabulary::findOrFail($id);

        $mainCategories = VoCategory1::orderBy(
            'name_th',
            'asc'
        )->get();

        $subCategories = VoCategory2::where(
            'category1_id',
            $data->main_category_id
        )->get();

    $navs = [

            [
                'url' => 'javascript:void(0)',
                'name' => 'คำศัพท์',
                'last' => 0
            ],

            [
                'url' => "$this->segment/$this->folder/edit",
                'name' => 'Edit',
                'last' => 1
            ]
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'mainCategories' => $mainCategories,
            'subCategories' => $subCategories,
            'data' => $data,
            'navs' => $navs
        ]);
    }

    public function insert(Request $request)
    {
        return $this->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    public function store(Request $request, $id = null)
    {
        try {

            DB::beginTransaction();

            if ($id) {

                $data = Vocabulary::findOrFail($id);

            } else {

                $data = new Vocabulary();
            }

            $data->title = $request->title;

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

                $path = 'upload/vocabulary';

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
            | PDF FILE
            |--------------------------------------------------------------------------
            */

            if ($file = $request->file('pdf_file')) {

                $path = 'upload/vocabulary';

                $filename =
                    'pdf-' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path($path),
                    $filename
                );

                $data->pdf_file_url =
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

    public function destroy(Request $request)
    {
        $item = Vocabulary::find($request->id);

        if ($item) {

            $item->load('items');

            foreach ($item->items as $vocabularyItem) {
                if ($vocabularyItem->word_audio_url) {
                    @unlink(public_path($vocabularyItem->word_audio_url));
                }

                if ($vocabularyItem->example_audio_url) {
                    @unlink(public_path($vocabularyItem->example_audio_url));
                }

                if ($vocabularyItem->image_url) {
                    @unlink(public_path($vocabularyItem->image_url));
                }
            }

            @unlink(
                public_path(
                    $item->cover_image_url
                )
            );

            @unlink(
                public_path(
                    $item->pdf_file_url
                )
            );

            $item->delete();

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function getSubCategory($id)
    {
        $items = VoCategory2::where(
            'category1_id',
            $id
        )->orderBy(
            'name_th',
            'asc'
        )->get();

        return response()->json($items);
    }

    public function updateStatus(Request $request)
    {
        $item = Vocabulary::find($request->id);

        if ($item) {
            $item->status = $request->status;
            $item->save();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
    
}
