<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\Backend\GeneralNotification;
use App\Models\Backend\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GeneralNotificationController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'general-notifications';

    public function index(Request $request)
    {
        $items = GeneralNotification::query()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('detail', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $this->navs('รายการ'),
        ]);
    }

    public function add()
    {
        return view("$this->prefix.pages.$this->folder.form", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => new GeneralNotification(['status' => 'on']),
            'jobs' => $this->availableJobs(),
            'navs' => $this->navs('เพิ่ม'),
            'action' => url("$this->segment/$this->folder/add"),
        ]);
    }

    public function edit($id)
    {
        return view("$this->prefix.pages.$this->folder.form", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => GeneralNotification::findOrFail($id),
            'jobs' => $this->availableJobs(),
            'navs' => $this->navs('แก้ไข'),
            'action' => url("$this->segment/$this->folder/edit/$id"),
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

    protected function store(Request $request, $id = null)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'content_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'work_id' => [
                'nullable',
                Rule::exists('jobs', 'id')
                    ->where('status', 'on')
                    ->where(function ($query) {
                        $query->whereDate('date', '>=', now()->toDateString());
                    }),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:on,off'],
        ]);

        try {
            DB::beginTransaction();

            $item = $id
                ? GeneralNotification::findOrFail($id)
                : new GeneralNotification();
            $item->fill(collect($validated)->except(['cover_image', 'content_image'])->all());

            foreach (['cover_image', 'content_image'] as $field) {
                if (!$request->hasFile($field)) {
                    continue;
                }

                $oldImage = $item->{$field};
                $item->{$field} = $this->uploadImage($request->file($field), $field);

                if ($oldImage && is_file(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }
            }

            $item->save();

            DB::commit();

            return view("$this->prefix.alert.success", [
                'url' => url("$this->segment/$this->folder"),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return view("$this->prefix.alert.alert", [
                'url' => url()->current(),
                'title' => 'ไม่สามารถบันทึกข้อมูลได้',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $item = GeneralNotification::find($request->id);

        if (!$item) {
            return response()->json(['status' => false]);
        }

        foreach (['cover_image', 'content_image'] as $field) {
            if ($item->{$field} && is_file(public_path($item->{$field}))) {
                unlink(public_path($item->{$field}));
            }
        }

        $item->delete();

        return response()->json(['status' => true]);
    }

    public function updateStatus(Request $request)
    {
        $item = GeneralNotification::find($request->id);

        if (!$item) {
            return response()->json(['status' => false]);
        }

        $item->status = $request->status;
        $item->save();

        return response()->json(['status' => true]);
    }

    protected function navs(string $current): array
    {
        return [
            [
                'url' => 'javascript:void(0)',
                'name' => 'แจ้งเตือนรวม',
                'last' => 0,
            ],
            [
                'url' => "$this->segment/$this->folder",
                'name' => $current,
                'last' => 1,
            ],
        ];
    }

    protected function availableJobs()
    {
        return Job::with('company')
            ->where('status', 'on')
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('title_th', 'asc')
            ->get();
    }

    protected function uploadImage($file, string $field): string
    {
        $path = 'upload/general-notifications';

        if (!is_dir(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        $filename = $field . '-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $filename);

        return $path . '/' . $filename;
    }
}
