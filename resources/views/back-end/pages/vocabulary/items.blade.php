@extends('back-end.layout.page')

@section('content')
<div class="card mb-6">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4">
            @if($vocabulary->cover_image_url)
                <img src="{{ asset($vocabulary->cover_image_url) }}" width="120" height="80" class="rounded object-fit-cover" alt="">
            @endif
            <div>
                <div class="text-muted mb-1">บทความคำศัพท์</div>
                <h2 class="mb-2">{{ $vocabulary->title }}</h2>
                @if($vocabulary->mainCategory)
                    <span class="badge badge-light-primary">{{ $vocabulary->mainCategory->name_th }}</span>
                @endif
                @if($vocabulary->subCategory)
                    <span class="badge badge-light-info">{{ $vocabulary->subCategory->name_th }}</span>
                @endif
                <span class="text-muted ms-2">คำศัพท์ทั้งหมด {{ $items->total() }} คำ</span>
            </div>
        </div>
        <a href="{{ url('webpanel/vocabulary/edit/' . $vocabulary->id) }}" class="btn btn-light-warning">แก้ไขบทความ</a>
    </div>
</div>

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <form method="GET" class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-solid w-300px ps-13" placeholder="ค้นหาคำศัพท์ คำอ่าน หรือคำแปล">
            </form>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('webpanel.vocabulary.items.add', $vocabulary) }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> เพิ่มคำศัพท์
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed gy-5">
                <thead>
                    <tr class="text-muted fw-bold">
                        <th class="text-center" style="width:60px">ลำดับ</th>
                        <th>คำศัพท์</th>
                        <th>คำอ่าน</th>
                        <th>คำแปลไทย</th>
                        <th>ประโยคตัวอย่าง</th>
                        <th class="text-center">รูปภาพ</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="text-center">{{ $item->sort_order }}</td>
                            <td class="fw-bold fs-4">{{ $item->japanese_word }}</td>
                            <td>{{ $item->reading ?: '-' }}</td>
                            <td>{{ $item->meaning_th }}</td>
                            <td style="min-width:240px">
                                @if($item->example_japanese)
                                    <div class="fw-semibold">{{ $item->example_japanese }}</div>
                                    @if($item->example_reading)<small class="text-muted d-block">{{ $item->example_reading }}</small>@endif
                                    @if($item->example_thai)<small class="d-block">{{ $item->example_thai }}</small>@endif
                                @else - @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if($item->image_url)
                                    <img src="{{ asset($item->image_url) }}" width="80" height="60"
                                        class="rounded object-fit-cover border" alt="{{ $item->japanese_word }}">
                                @else - @endif
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('webpanel.vocabulary.items.status', [$vocabulary, $item]) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $item->status === 'on' ? 'off' : 'on' }}">
                                    <button class="btn btn-sm {{ $item->status === 'on' ? 'btn-light-success' : 'btn-light-secondary' }}">
                                        {{ $item->status === 'on' ? 'เปิด' : 'ปิด' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('webpanel.vocabulary.items.edit', [$vocabulary, $item]) }}" class="btn btn-sm btn-light-warning">แก้ไข</a>
                                <form method="POST" action="{{ route('webpanel.vocabulary.items.destroy', [$vocabulary, $item]) }}" class="d-inline" onsubmit="return confirm('ต้องการลบคำศัพท์นี้หรือไม่?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light-danger">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-10">ยังไม่มีคำศัพท์ในบทความนี้</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->withQueryString()->links('back-end.layout.pagination') }}
    </div>
</div>

<div class="mt-5"><a href="{{ route('webpanel.vocabulary') }}" class="btn btn-light">กลับหน้าบทความ</a></div>
@endsection
