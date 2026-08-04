@extends('back-end.layout.page')

@section('content')
@php($isEdit = $data->exists)
<form method="POST" enctype="multipart/form-data"
    action="{{ $isEdit ? route('webpanel.vocabulary.items.update', [$vocabulary, $data]) : route('webpanel.vocabulary.items.store', $vocabulary) }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>{{ $isEdit ? 'แก้ไขคำศัพท์' : 'เพิ่มคำศัพท์' }} — {{ $vocabulary->title }}</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <label class="form-label required">คำศัพท์ภาษาญี่ปุ่น</label>
                    <input type="text" name="japanese_word" class="form-control fs-3" required maxlength="255"
                        value="{{ old('japanese_word', $data->japanese_word) }}" placeholder="工場">
                </div>
                <div class="col-md-4 mb-5">
                    <label class="form-label">คำอ่าน</label>
                    <input type="text" name="reading" class="form-control" maxlength="255"
                        value="{{ old('reading', $data->reading) }}" placeholder="こうじょう">
                </div>
                <div class="col-md-4 mb-5">
                    <label class="form-label required">คำแปลภาษาไทย</label>
                    <input type="text" name="meaning_th" class="form-control" required maxlength="255"
                        value="{{ old('meaning_th', $data->meaning_th) }}" placeholder="โรงงาน">
                </div>

                <div class="col-md-12"><hr><h4 class="mb-5">ประโยคตัวอย่าง</h4></div>
                <div class="col-md-12 mb-5">
                    <label class="form-label">ประโยคภาษาญี่ปุ่น</label>
                    <textarea name="example_japanese" class="form-control" rows="2" placeholder="工場で働いています。">{{ old('example_japanese', $data->example_japanese) }}</textarea>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">คำอ่านประโยค</label>
                    <textarea name="example_reading" class="form-control" rows="2" placeholder="こうじょうではたらいています。">{{ old('example_reading', $data->example_reading) }}</textarea>
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">คำแปลประโยคภาษาไทย</label>
                    <textarea name="example_thai" class="form-control" rows="2" placeholder="ฉันทำงานอยู่ในโรงงาน">{{ old('example_thai', $data->example_thai) }}</textarea>
                </div>

                <div class="col-md-12"><hr><h4 class="mb-5">ไฟล์เสียง</h4></div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">เสียงคำศัพท์</label>
                    @if($data->word_audio_url)
                        <div class="mb-2"><audio controls src="{{ asset($data->word_audio_url) }}"></audio></div>
                    @endif
                    <input type="file" name="word_audio" class="form-control" accept=".mp3,.wav,.m4a,.ogg">
                </div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">เสียงประโยคตัวอย่าง</label>
                    @if($data->example_audio_url)
                        <div class="mb-2"><audio controls src="{{ asset($data->example_audio_url) }}"></audio></div>
                    @endif
                    <input type="file" name="example_audio" class="form-control" accept=".mp3,.wav,.m4a,.ogg">
                </div>

                <div class="col-md-3 mb-5">
                    <label class="form-label">ลำดับแสดงผล</label>
                    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $data->sort_order ?? 0) }}">
                </div>
                <div class="col-md-3 mb-5">
                    <label class="form-label required">สถานะ</label>
                    <select name="status" class="form-select" required>
                        <option value="on" {{ old('status', $data->status) === 'on' ? 'selected' : '' }}>เปิดใช้งาน</option>
                        <option value="off" {{ old('status', $data->status) === 'off' ? 'selected' : '' }}>ปิดใช้งาน</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-5">
        <a href="{{ route('webpanel.vocabulary.items', $vocabulary) }}" class="btn btn-light">ย้อนกลับ</a>
        <button class="btn btn-primary">บันทึกคำศัพท์</button>
    </div>
</form>
@endsection
