<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\Backend\Vocabulary;
use App\Models\Backend\VocabularyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class VocabularyItemController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'vocabulary';

    public function index(Vocabulary $vocabulary, Request $request)
    {
        $items = $vocabulary->items()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($query) use ($search) {
                    $query->where('japanese_word', 'like', "%$search%")
                        ->orWhere('reading', 'like', "%$search%")
                        ->orWhere('meaning_th', 'like', "%$search%");
                });
            })
            ->paginate(30);

        return view("$this->prefix.pages.$this->folder.items", $this->viewData($vocabulary, [
            'items' => $items,
        ]));
    }

    public function create(Vocabulary $vocabulary)
    {
        return view("$this->prefix.pages.$this->folder.item-form", $this->viewData($vocabulary, [
            'data' => new VocabularyItem(),
        ]));
    }

    public function store(Request $request, Vocabulary $vocabulary)
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($request, $validated, $vocabulary) {
            $validated['image_url'] = $this->storeImage($request);
            $validated['status'] = 'on';
            $validated['sort_order'] = ($vocabulary->items()->max('sort_order') ?? 0) + 1;
            $vocabulary->items()->create($validated);
        });

        return redirect()->route('webpanel.vocabulary.items', $vocabulary)
            ->with('success', 'เพิ่มคำศัพท์เรียบร้อยแล้ว');
    }

    public function edit(Vocabulary $vocabulary, VocabularyItem $vocabularyItem)
    {
        $this->ensureBelongsToArticle($vocabulary, $vocabularyItem);

        return view("$this->prefix.pages.$this->folder.item-form", $this->viewData($vocabulary, [
            'data' => $vocabularyItem,
        ]));
    }

    public function update(Request $request, Vocabulary $vocabulary, VocabularyItem $vocabularyItem)
    {
        $this->ensureBelongsToArticle($vocabulary, $vocabularyItem);
        $validated = $this->validateData($request);

        DB::transaction(function () use ($request, $validated, $vocabularyItem) {
            if ($request->hasFile('image')) {
                $this->deletePublicFile($vocabularyItem->image_url);
                $validated['image_url'] = $this->storeImage($request);
            }

            $vocabularyItem->update($validated);
        });

        return redirect()->route('webpanel.vocabulary.items', $vocabulary)
            ->with('success', 'บันทึกคำศัพท์เรียบร้อยแล้ว');
    }

    public function destroy(Vocabulary $vocabulary, VocabularyItem $vocabularyItem)
    {
        $this->ensureBelongsToArticle($vocabulary, $vocabularyItem);

        DB::transaction(function () use ($vocabularyItem) {
            $this->deletePublicFile($vocabularyItem->word_audio_url);
            $this->deletePublicFile($vocabularyItem->example_audio_url);
            $this->deletePublicFile($vocabularyItem->image_url);
            $vocabularyItem->delete();
        });

        return back()->with('success', 'ลบคำศัพท์เรียบร้อยแล้ว');
    }

    public function updateStatus(Request $request, Vocabulary $vocabulary, VocabularyItem $vocabularyItem)
    {
        $this->ensureBelongsToArticle($vocabulary, $vocabularyItem);
        $validated = $request->validate([
            'status' => ['required', Rule::in(['on', 'off'])],
        ]);
        $vocabularyItem->update($validated);

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'japanese_word' => ['required', 'string', 'max:255'],
            'reading' => ['nullable', 'string', 'max:255'],
            'meaning_th' => ['required', 'string', 'max:255'],
            'example_japanese' => ['nullable', 'string'],
            'example_reading' => ['nullable', 'string'],
            'example_thai' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function storeImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $path = 'upload/vocabulary-items';
        File::ensureDirectoryExists(public_path($path));
        $file = $request->file('image');
        $filename = 'image-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $filename);

        return $path . '/' . $filename;
    }

    private function ensureBelongsToArticle(Vocabulary $vocabulary, VocabularyItem $vocabularyItem)
    {
        abort_unless($vocabularyItem->vocabulary_id === $vocabulary->id, 404);
    }

    private function deletePublicFile($path)
    {
        if ($path && File::isFile(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    private function viewData(Vocabulary $vocabulary, array $data = [])
    {
        return array_merge([
            'prefix' => $this->prefix,
            'segment' => $this->segment,
            'folder' => $this->folder,
            'vocabulary' => $vocabulary,
            'navs' => [
                ['url' => route('webpanel.vocabulary'), 'name' => 'บทความคำศัพท์', 'last' => 0],
                ['url' => route('webpanel.vocabulary.items', $vocabulary), 'name' => $vocabulary->title, 'last' => 1],
            ],
        ], $data);
    }
}
