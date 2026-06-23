<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\Backend\Alumni;
use App\Models\Backend\AlumniBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlumniController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'alumni';

    public function index(Request $request)
    {
        $items = Alumni::withCount('banners');

        if ($request->filled('search')) {
            $items->where('title', 'like', '%' . trim($request->search) . '%');
        }

        $items = $items->orderByDesc('id')->paginate(10)->withQueryString();

        if ($items->total() > 0) {
            $items->pages = new \stdClass();
            $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        }

        return view("$this->prefix.pages.article.index", $this->viewData([
            'items' => $items,
            'navs' => $this->navs('รายการ'),
        ]));
    }

    public function add()
    {
        return view("$this->prefix.pages.article.add", $this->viewData([
            'navs' => $this->navs('เพิ่มข้อมูล'),
        ]));
    }

    public function insert(Request $request)
    {
        return $this->store($request);
    }

    public function edit($id)
    {
        $data = Alumni::with('banners')->findOrFail($id);

        return view("$this->prefix.pages.article.edit", $this->viewData([
            'data' => $data,
            'navs' => $this->navs('แก้ไขข้อมูล'),
        ]));
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    public function store(Request $request, $id = null)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:on,off'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'banner_images' => ['nullable', 'array'],
            'banner_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_banner_ids' => ['nullable', 'array'],
            'remove_banner_ids.*' => ['integer'],
        ]);

        $filesToDelete = [];
        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            $data = $id ? Alumni::findOrFail($id) : new Alumni();
            $data->title = $validated['title'];
            $data->slug = $data->slug ?: Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));
            $data->short_description = $validated['short_description'] ?? null;
            $data->description = $validated['description'] ?? null;
            $data->published_at = $validated['published_at'] ?? null;
            $data->status = $request->input('status', $data->status ?: 'on');

            if ($request->hasFile('cover_image')) {
                if ($data->cover_image_url) {
                    $filesToDelete[] = $data->cover_image_url;
                }
                $data->cover_image_url = $this->upload($request->file('cover_image'), 'cover');
                $uploadedFiles[] = $data->cover_image_url;
            }

            $data->save();

            if (!empty($validated['remove_banner_ids'])) {
                $banners = $data->banners()->whereIn('id', $validated['remove_banner_ids'])->get();
                foreach ($banners as $banner) {
                    $filesToDelete[] = $banner->image_url;
                    $banner->delete();
                }
            }

            $sortOrder = (int) $data->banners()->max('sort_order');
            foreach ($request->file('banner_images', []) as $file) {
                $imageUrl = $this->upload($file, 'banner');
                $uploadedFiles[] = $imageUrl;
                $data->banners()->create([
                    'image_url' => $imageUrl,
                    'sort_order' => ++$sortOrder,
                ]);
            }

            DB::commit();
            $this->deleteFiles($filesToDelete);

            return view("$this->prefix.alert.success", [
                'url' => url("$this->segment/$this->folder"),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->deleteFiles($uploadedFiles);
            report($e);

            return view("$this->prefix.alert.alert", [
                'url' => url()->current(),
                'title' => 'ไม่สามารถทำรายการได้',
                'text' => 'กรุณาลองใหม่อีกครั้ง',
                'icon' => 'error',
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $item = Alumni::with('banners')->find($request->id);

        if (!$item) {
            return response()->json(['status' => false], 404);
        }

        $files = $item->banners->pluck('image_url')->filter()->all();
        if ($item->cover_image_url) {
            $files[] = $item->cover_image_url;
        }

        $item->delete();
        $this->deleteFiles($files);

        return response()->json(['status' => true]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['status' => ['required', 'in:on,off']]);
        $item = Alumni::find($request->id);

        if (!$item) {
            return response()->json(['status' => false], 404);
        }

        $item->status = $request->status;
        $item->save();

        return response()->json(['status' => true]);
    }

    public function updateSortOrder(Request $request)
    {
        foreach ($request->input('sort', []) as $id => $sort) {
            Alumni::whereKey($id)->update(['sort_order' => (int) $sort]);
        }

        return response()->json(['status' => true]);
    }

    private function viewData(array $data)
    {
        return array_merge([
            'prefix' => $this->prefix,
            'segment' => $this->segment,
            'folder' => $this->folder,
            'moduleTitle' => 'รุ่นพี่ศิษย์เก่า',
            'multipleBanners' => true,
            'showCategories' => false,
        ], $data);
    }

    private function navs($name)
    {
        return [
            ['url' => 'javascript:void(0)', 'name' => 'รุ่นพี่ศิษย์เก่า', 'last' => 0],
            ['url' => "$this->segment/$this->folder", 'name' => $name, 'last' => 1],
        ];
    }

    private function upload($file, $prefix)
    {
        $path = 'upload/alumni';
        if (!is_dir(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        $filename = $prefix . '-' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $filename);

        return $path . '/' . $filename;
    }

    private function deleteFiles(array $paths)
    {
        foreach (array_unique($paths) as $path) {
            $fullPath = public_path($path);
            if ($path && is_file($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
