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

                <div class="col-md-12"><hr><h4 class="mb-5">รูปภาพคำศัพท์</h4></div>
                <div class="col-md-6 mb-5">
                    <label class="form-label">รูปภาพ</label>
                    @if($data->image_url)
                        <div class="mb-3">
                            <img src="{{ asset($data->image_url) }}" width="200" height="140"
                                class="rounded object-fit-cover border" alt="{{ $data->japanese_word }}">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">รองรับไฟล์ JPG, JPEG, PNG และ WEBP ขนาดไม่เกิน 5 MB</div>
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
