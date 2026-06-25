@php
$profile = $row?->profile;
$detail = $applicationDetail ? $applicationDetail->toArray() : [];
$training = $row?->trainingCourses?->first();

$d = function ($path, $default = '') use ($detail) {
return old('application_detail.' . $path, data_get($detail, $path, $default));
};

$p = function ($field, $default = '') use ($profile) {
return old($field, data_get($profile, $field, $default));
};

$m = function ($field, $default = '') use ($row) {
return old($field, data_get($row, $field, $default));
};

$checked = function ($path, $value, $default = null) use ($d) {
return (string) $d($path, $default) === (string) $value ? 'checked' : '';
};

$profileChecked = function ($field, $value, $default = null) use ($p) {
return (string) $p($field, $default) === (string) $value ? 'checked' : '';
};

$selected = function ($field, $value, $default = null) use ($p) {
return (string) $p($field, $default) === (string) $value ? 'selected' : '';
};

$detailSelected = function ($path, $value, $default = null) use ($d) {
return (string) $d($path, $default) === (string) $value ? 'selected' : '';
};

$educationValue = function ($key, $field, $default = '') use ($educationData) {
return old($key . '.' . $field, data_get($educationData, $key . '.' . $field, $default));
};

$months = [
'01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
'05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
'09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม',
];

$educationBlocks = [
'lower_secondary' => 'มัธยมศึกษาตอนต้น',
'upper_secondary' => 'มัธยมศึกษาตอนปลาย/ปวช.',
'vocational' => 'ปวส.',
'bachelor' => 'ปริญญาตรี/กศน.',
];

$workExperiences = old('application_detail.work_family.work_experiences', data_get($detail, 'work_family.work_experiences', [[]]));
$spouseChildren = old('application_detail.work_family.spouse_children', data_get($detail, 'work_family.spouse_children', [[]]));
$familyMembers = old('application_detail.work_family.family_members', data_get($detail, 'work_family.family_members', [[]]));

$workExperiences = count($workExperiences ?: []) ? $workExperiences : [[]];
$spouseChildren = count($spouseChildren ?: []) ? $spouseChildren : [[]];
$familyMembers = count($familyMembers ?: []) ? $familyMembers : [[]];
@endphp

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-2">กรุณาตรวจสอบข้อมูล</div>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลส่วนตัว</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-6">
                    <label class="form-label">อัปโหลดรูปตัวเอง</label>
                    @if($profile?->profile_image)
                    <div class="mb-3">
                        <img src="{{ asset($profile->profile_image) }}" class="w-150px rounded">
                    </div>
                    @endif
                    <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">ประเภทงานที่สมัคร</label>
                        <select name="application_detail[personal][application_type]" class="form-select">
                            <option value="">เลือก</option>
                            <option value="caregiver" {{ $detailSelected('personal.application_type', 'caregiver') }}>ผู้ดูแล</option>
                            <option value="worker" {{ $detailSelected('personal.application_type', 'worker') }}>แรงงาน/ฝึกงาน</option>
                            <option value="other" {{ $detailSelected('personal.application_type', 'other') }}>อื่น ๆ</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">เลขที่บัตรประชาชน</label>
                        <input type="text" name="citizen_id" class="form-control" value="{{ $p('citizen_id') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">วันเดือนปีเกิด</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ $p('birth_date') }}">
                    </div>
                    <div class="col-md-2 mb-5">
                        <label class="form-label">อายุ</label>
                        <input type="number" name="age" class="form-control" value="{{ $p('age') }}">
                    </div>
                    <div class="col-md-3 mb-5">
                        <label class="form-label">เพศ</label>
                        <select name="gender" class="form-select">
                            <option value="">เลือก</option>
                            <option value="ชาย" {{ $selected('gender', 'ชาย') }}>ชาย</option>
                            <option value="หญิง" {{ $selected('gender', 'หญิง') }}>หญิง</option>
                            <option value="อื่นๆ" {{ $selected('gender', 'อื่นๆ') }}>อื่น ๆ</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-5">
                        <label class="form-label">ศาสนา</label>
                        <input type="text" name="application_detail[personal][religion]" class="form-control" value="{{ $d('personal.religion') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-5">
                        <label class="form-label required">คำนำหน้า</label>
                        <select name="title_th" class="form-select">
                            <option value="">เลือก</option>
                            <option value="นาย" {{ $selected('title_th', 'นาย') }}>นาย</option>
                            <option value="นาง" {{ $selected('title_th', 'นาง') }}>นาง</option>
                            <option value="นางสาว" {{ $selected('title_th', 'นางสาว') }}>นางสาว</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-5">
                        <label class="form-label required">ชื่อ</label>
                        <input type="text" name="first_name_th" class="form-control" value="{{ $p('first_name_th') }}">
                    </div>
                    <div class="col-md-5 mb-5">
                        <label class="form-label required">นามสกุล</label>
                        <input type="text" name="last_name_th" class="form-control" value="{{ $p('last_name_th') }}">
                    </div>
                    <div class="col-md-2 mb-5">
                        <label class="form-label required">คำนำหน้า</label>
                        <select name="title_en" class="form-select">
                            <option value="">เลือก</option>
                            <option value="Mr." {{ $selected('title_en', 'Mr.') }}>Mr.</option>
                            <option value="Mrs." {{ $selected('title_en', 'Mrs.') }}>Mrs.</option>
                            <option value="Miss" {{ $selected('title_en', 'Miss') }}>Miss</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-5">
                        <label class="form-label required">ชื่อ (ภาษาอังกฤษ)</label>
                        <input type="text" name="first_name_en" class="form-control" value="{{ $p('first_name_en') }}">
                    </div>
                    <div class="col-md-5 mb-5">
                        <label class="form-label required">นามสกุล (ภาษาอังกฤษ)</label>
                        <input type="text" name="last_name_en" class="form-control" value="{{ $p('last_name_en') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">ชื่อเล่น</label>
                        <input type="text" name="nickname" class="form-control" value="{{ $p('nickname') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" class="form-control" value="{{ $p('phone') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">Line ID</label>
                        <input type="text" name="line_id" class="form-control" value="{{ $p('line_id') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="facebook" class="form-control" value="{{ $p('facebook') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">Email</label>
                        <input type="email" name="email_contact" class="form-control" value="{{ $p('email_contact') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">เบอร์โทรศัพท์ผู้ปกครอง</label>
                        <input type="text" name="emergency_phone" class="form-control" value="{{ $p('emergency_phone') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">สถานภาพการสมรส</label>
                        <select name="marital_status" class="form-select">
                            <option value="">เลือก</option>
                            <option value="โสด" {{ $selected('marital_status', 'โสด') }}>โสด</option>
                            <option value="สมรส" {{ $selected('marital_status', 'สมรส') }}>สมรส</option>
                            <option value="หย่า" {{ $selected('marital_status', 'หย่า') }}>หย่า</option>
                            <option value="หม้าย" {{ $selected('marital_status', 'หม้าย') }}>หม้าย</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">ที่พักอาศัย</label>
                        <select name="application_detail[personal][housing_type]" class="form-select">
                            <option value="">เลือก</option>
                            <option value="บ้านตัวเอง" {{ $detailSelected('personal.housing_type', 'บ้านตัวเอง') }}>บ้านตัวเอง</option>
                            <option value="บ้านเช่า" {{ $detailSelected('personal.housing_type', 'บ้านเช่า') }}>บ้านเช่า</option>
                            <option value="อื่นๆ" {{ $detailSelected('personal.housing_type', 'อื่นๆ') }}>อื่น ๆ</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label required">พักอาศัยอยู่กับ</label>
                        <input type="text" name="application_detail[personal][living_with]" class="form-control" value="{{ $d('personal.living_with') }}">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label required">ที่อยู่ตามบัตรประชาชน</label>
                    <textarea name="house_registration_address" class="form-control" rows="4">{{ $p('house_registration_address') }}</textarea>
                </div>
                <div class="form-check mb-3">
                    <input type="hidden" name="same_as_house_registration" value="0">
                    <input class="form-check-input" type="checkbox" name="same_as_house_registration" value="1" id="sameAddress" {{ $p('same_as_house_registration') ? 'checked' : '' }}>
                    <label class="form-check-label" for="sameAddress">ที่อยู่ปัจจุบันเหมือนที่อยู่ตามบัตรประชาชน</label>
                </div>
                <div class="mb-5">
                    <label class="form-label required">ที่อยู่ปัจจุบัน</label>
                    <textarea name="current_address" class="form-control" rows="4">{{ $p('current_address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลการศึกษา</h2>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="studying[id]" value="{{ $educationValue('studying', 'id') }}">
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">วิทยาลัย/โรงเรียน</label>
                        <input type="text" name="studying[institution_name]" class="form-control" value="{{ $educationValue('studying', 'institution_name') }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">สาขาที่เรียน</label>
                        <input type="text" name="studying[major]" class="form-control" value="{{ $educationValue('studying', 'major') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">ระดับการศึกษาปัจจุบัน</label>
                        <select name="studying[education_level]" class="form-select">
                            <option value="">เลือก</option>
                            @foreach(['upper_secondary' => 'ม.6', 'vocational' => 'ปวช.', 'high_vocational' => 'ปวส.', 'bachelor' => 'ปริญญาตรี', 'other' => 'อื่นๆ'] as $levelValue => $levelLabel)
                            <option value="{{ $levelValue }}" {{ $educationValue('studying', 'education_level') == $levelValue ? 'selected' : '' }}>{{ $levelLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mb-5">
                        <label class="form-label">อื่น ๆ (โปรดระบุ)</label>
                        <input type="text" name="application_detail[education_extra][current_level_other]" class="form-control" value="{{ $d('education_extra.current_level_other') }}">
                    </div>
                </div>

                @foreach($educationBlocks as $key => $label)
                <div class="border rounded p-4 mb-5">
                    <input type="hidden" name="{{ $key }}[id]" value="{{ $educationValue($key, 'id') }}">
                    <h4 class="mb-4">{{ $label }}</h4>

                    @if($key === 'vocational')
                    <div class="mb-4">
                        @foreach(['ปกติ', 'ทวิภาคี'] as $type)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="{{ $key }}[education_type]" value="{{ $type }}" {{ $educationValue($key, 'education_type') == $type ? 'checked' : '' }}>
                            <span class="form-check-label">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ชื่อสถาบัน</label>
                            <input type="text" name="{{ $key }}[institution_name]" class="form-control" value="{{ $educationValue($key, 'institution_name') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">สาย/สาขา</label>
                            <input type="text" name="{{ $key }}[major]" class="form-control" value="{{ $educationValue($key, 'major') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มเรียน (เดือน)</label>
                            <select name="{{ $key }}[start_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $educationValue($key, 'start_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มเรียน (ปี พ.ศ.)</label>
                            <input type="number" name="{{ $key }}[start_year]" class="form-control" value="{{ $educationValue($key, 'start_year') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">จบการศึกษา (เดือน)</label>
                            <select name="{{ $key }}[end_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $educationValue($key, 'end_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">จบการศึกษา (ปี พ.ศ.)</label>
                            <input type="number" name="{{ $key }}[end_year]" class="form-control" value="{{ $educationValue($key, 'end_year') }}">
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="mb-5">
                    <label class="form-label">กรณีไม่เรียนตามเกณฑ์/หยุดพักการเรียน/หยุดเรียนกลางคัน</label>
                    <textarea name="application_detail[education_extra][study_gap_reason]" class="form-control" rows="4">{{ $d('education_extra.study_gap_reason') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลระดับภาษาและการอบรม</h2>
                </div>
            </div>
            <div class="card-body">
                @foreach(['japanese' => 'ระดับภาษา (N)', 'other_language' => 'ระดับภาษาอื่น'] as $key => $label)
                <div class="border rounded p-4 mb-5">
                    <h4 class="mb-4">{{ $label }}</h4>
                    <div class="row">
                        <div class="col-md-3 mb-5">
                            <label class="form-label">ระดับภาษา</label>
                            <input type="text" name="application_detail[language_training][{{ $key }}][level]" class="form-control" value="{{ $d('language_training.' . $key . '.level') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">คะแนน</label>
                            <input type="text" name="application_detail[language_training][{{ $key }}][score]" class="form-control" value="{{ $d('language_training.' . $key . '.score') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ชื่อสถาบัน</label>
                            <input type="text" name="application_detail[language_training][{{ $key }}][institution_name]" class="form-control" value="{{ $d('language_training.' . $key . '.institution_name') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">วันที่ออกใบสอบวัดระดับ</label>
                            <input type="date" name="application_detail[language_training][{{ $key }}][certificate_date]" class="form-control" value="{{ $d('language_training.' . $key . '.certificate_date') }}">
                        </div>
                    </div>
                </div>
                @endforeach

                <h4 class="mb-4">ประวัติการศึกษาด้านบริบาล</h4>
                @foreach(['care_1', 'care_2'] as $careKey)
                <div class="border rounded p-4 mb-5">
                    <div class="mb-4">
                        @foreach(['RN', 'PN', 'NA'] as $program)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="application_detail[language_training][care][{{ $careKey }}][program]" value="{{ $program }}" {{ $checked('language_training.care.' . $careKey . '.program', $program) }}>
                            <span class="form-check-label">{{ $program }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">สถาบัน</label>
                            <input type="text" name="application_detail[language_training][care][{{ $careKey }}][institution_name]" class="form-control" value="{{ $d('language_training.care.' . $careKey . '.institution_name') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มเรียน (เดือน)</label>
                            <select name="application_detail[language_training][care][{{ $careKey }}][start_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $d('language_training.care.' . $careKey . '.start_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มเรียน (ปี พ.ศ.)</label>
                            <input type="number" name="application_detail[language_training][care][{{ $careKey }}][start_year]" class="form-control" value="{{ $d('language_training.care.' . $careKey . '.start_year') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">จบการศึกษา (เดือน)</label>
                            <select name="application_detail[language_training][care][{{ $careKey }}][end_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $d('language_training.care.' . $careKey . '.end_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">จบการศึกษา (ปี พ.ศ.)</label>
                            <input type="number" name="application_detail[language_training][care][{{ $careKey }}][end_year]" class="form-control" value="{{ $d('language_training.care.' . $careKey . '.end_year') }}">
                        </div>
                    </div>
                </div>
                @endforeach

                <input type="hidden" name="training[training_id]" value="{{ old('training.training_id', $training?->training_id) }}">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <label class="form-label">อื่น ๆ</label>
                        <input type="text" name="training[program_type]" class="form-control" value="{{ old('training.program_type', $training?->program_type) }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">ชื่อสถาบัน</label>
                        <input type="text" name="training[institution_name]" class="form-control" value="{{ old('training.institution_name', $training?->institution_name) }}">
                    </div>
                    <div class="col-md-2 mb-5">
                        <label class="form-label">เริ่มเรียน</label>
                        <input type="month" name="training[start_month_year]" class="form-control" value="{{ old('training.start_month_year', $training?->start_month_year) }}">
                    </div>
                    <div class="col-md-2 mb-5">
                        <label class="form-label">จบการศึกษา</label>
                        <input type="month" name="training[end_month_year]" class="form-control" value="{{ old('training.end_month_year', $training?->end_month_year) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลการทำงานและครอบครัว</h2>
                </div>
            </div>
            <div class="card-body">
                <h4 class="mb-4">ประวัติการทำงาน</h4>
                @foreach($workExperiences as $index => $work)
                <div class="border rounded p-4 mb-5">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ชื่อบริษัท</label>
                            <input type="text" name="application_detail[work_family][work_experiences][{{ $index }}][company_name]" class="form-control" value="{{ data_get($work, 'company_name') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ประเภทธุรกิจ</label>
                            <input type="text" name="application_detail[work_family][work_experiences][{{ $index }}][business_type]" class="form-control" value="{{ data_get($work, 'business_type') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ตำแหน่งงานที่ทำ</label>
                            <input type="text" name="application_detail[work_family][work_experiences][{{ $index }}][position]" class="form-control" value="{{ data_get($work, 'position') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ลักษณะงานที่ทำ</label>
                            <input type="text" name="application_detail[work_family][work_experiences][{{ $index }}][job_description]" class="form-control" value="{{ data_get($work, 'job_description') }}">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">ประเภทงานที่ทำ</label>
                            <select name="application_detail[work_family][work_experiences][{{ $index }}][employment_type]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach(['ประจำ', 'ชั่วคราว', 'พาร์ทไทม์', 'อื่นๆ'] as $type)
                                <option value="{{ $type }}" {{ data_get($work, 'employment_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">เงินเดือน</label>
                            <input type="text" name="application_detail[work_family][work_experiences][{{ $index }}][salary]" class="form-control" value="{{ data_get($work, 'salary') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มทำงาน (เดือน)</label>
                            <select name="application_detail[work_family][work_experiences][{{ $index }}][start_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ data_get($work, 'start_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">เริ่มทำงาน (ปี พ.ศ.)</label>
                            <input type="number" name="application_detail[work_family][work_experiences][{{ $index }}][start_year]" class="form-control" value="{{ data_get($work, 'start_year') }}">
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">ทำงานถึง (เดือน)</label>
                            <select name="application_detail[work_family][work_experiences][{{ $index }}][end_month]" class="form-select">
                                <option value="">เลือก</option>
                                @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ data_get($work, 'end_month') == $monthValue ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5">
                            <label class="form-label">ทำงานถึง (ปี พ.ศ.)</label>
                            <input type="number" name="application_detail[work_family][work_experiences][{{ $index }}][end_year]" class="form-control" value="{{ data_get($work, 'end_year') }}">
                        </div>
                    </div>
                </div>
                @endforeach

                <h4 class="mb-4">แฟน/คู่สมรส/ลูก</h4>
                <div class="js-repeat-group" data-repeat-name="spouse_children">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-light-primary js-repeat-add">+ เพิ่มข้อมูล</button>
                    </div>
                    @foreach($spouseChildren as $index => $person)
                    <div class="border rounded p-4 mb-5 js-repeat-item" data-repeat-index="{{ $index }}">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-sm btn-light-danger js-repeat-remove">ลบ</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" name="application_detail[work_family][spouse_children][{{ $index }}][first_name]" class="form-control" value="{{ data_get($person, 'first_name') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" name="application_detail[work_family][spouse_children][{{ $index }}][last_name]" class="form-control" value="{{ data_get($person, 'last_name') }}">
                            </div>
                            <div class="col-md-4 mb-5">
                                <label class="form-label">ความสัมพันธ์</label>
                                <input type="text" name="application_detail[work_family][spouse_children][{{ $index }}][relationship]" class="form-control" value="{{ data_get($person, 'relationship') }}">
                            </div>
                            <div class="col-md-2 mb-5">
                                <label class="form-label">อายุ</label>
                                <input type="number" name="application_detail[work_family][spouse_children][{{ $index }}][age]" class="form-control" value="{{ data_get($person, 'age') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">อาชีพ/การศึกษา</label>
                                <input type="text" name="application_detail[work_family][spouse_children][{{ $index }}][occupation_or_education]" class="form-control" value="{{ data_get($person, 'occupation_or_education') }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h4 class="mb-4">สมาชิกในครอบครัว</h4>
                <div class="js-repeat-group" data-repeat-name="family_members">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-light-primary js-repeat-add">+ เพิ่มข้อมูล</button>
                    </div>
                    @foreach($familyMembers as $index => $person)
                    <div class="border rounded p-4 mb-5 js-repeat-item" data-repeat-index="{{ $index }}">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-sm btn-light-danger js-repeat-remove">ลบ</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][first_name]" class="form-control" value="{{ data_get($person, 'first_name') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][last_name]" class="form-control" value="{{ data_get($person, 'last_name') }}">
                            </div>
                            <div class="col-md-4 mb-5">
                                <label class="form-label">ความสัมพันธ์</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][relationship]" class="form-control" value="{{ data_get($person, 'relationship') }}">
                            </div>
                            <div class="col-md-2 mb-5">
                                <label class="form-label">อายุ</label>
                                <input type="number" name="application_detail[work_family][family_members][{{ $index }}][age]" class="form-control" value="{{ data_get($person, 'age') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">อาชีพ</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][occupation]" class="form-control" value="{{ data_get($person, 'occupation') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">ตำแหน่ง</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][position]" class="form-control" value="{{ data_get($person, 'position') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">สถานที่ทำงาน/ชื่อบริษัท</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][workplace]" class="form-control" value="{{ data_get($person, 'workplace') }}">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">จังหวัด</label>
                                <input type="text" name="application_detail[work_family][family_members][{{ $index }}][province]" class="form-control" value="{{ data_get($person, 'province') }}">
                            </div>
                            <div class="col-md-12 mb-0">
                                <label class="form-label">หากบิดา/มารดา หย่าร้างกันหรือเสียชีวิต ให้กรอกข้อมูลผู้สำรองร่วม</label>
                                <textarea name="application_detail[work_family][family_members][{{ $index }}][remark]" class="form-control" rows="3">{{ data_get($person, 'remark') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h4 class="mb-4">ที่อยู่ที่ติดต่อได้ของญาติพี่น้องหรือเพื่อน และคนรู้จักที่ประเทศไทย</h4>
                <div class="mb-5">
                    <label class="form-label d-block">มีหรือไม่</label>
                    @foreach(['ไม่มี', 'มี'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[work_family][thai_contact][has_contact]" value="{{ $option }}" {{ $checked('work_family.thai_contact.has_contact', $option, 'ไม่มี') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="row">
                    @foreach([
                    'first_name' => 'ชื่อ',
                    'last_name' => 'นามสกุล',
                    'relationship' => 'ความสัมพันธ์',
                    'age' => 'อายุ',
                    'address' => 'ที่อยู่',
                    'japan_years' => 'อยู่ญี่ปุ่นกี่ปี',
                    'phone' => 'เบอร์โทร',
                    'visa_type' => 'ไปด้วยวีซ่าอะไร',
                    ] as $key => $label)
                    <div class="{{ in_array($key, ['address']) ? 'col-md-12' : 'col-md-6' }} mb-5">
                        <label class="form-label">{{ $label }}</label>
                        @if($key === 'address')
                        <textarea name="application_detail[work_family][thai_contact][{{ $key }}]" class="form-control" rows="3">{{ $d('work_family.thai_contact.' . $key) }}</textarea>
                        @else
                        <input type="text" name="application_detail[work_family][thai_contact][{{ $key }}]" class="form-control" value="{{ $d('work_family.thai_contact.' . $key) }}">
                        @endif
                    </div>
                    @endforeach
                    <div class="col-md-12 mb-0">
                        <label class="form-label">ข้อมูลอื่น ๆ</label>
                        <textarea name="application_detail[work_family][thai_contact][other_detail]" class="form-control" rows="4">{{ $d('work_family.thai_contact.other_detail') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลร่างกายและสุขภาพ</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach([
                    'height_cm' => 'ส่วนสูง (cm.)',
                    'weight_kg' => 'น้ำหนัก (kg.)',
                    'shoulder_width_cm' => 'ความกว้างบ่า (cm.)',
                    'waist_inch' => 'รอบเอว (นิ้ว)',
                    ] as $key => $label)
                    <div class="col-md-3 mb-5">
                        <label class="form-label required">{{ $label }}</label>
                        <input type="text" name="application_detail[health][body][{{ $key }}]" class="form-control" value="{{ $d('health.body.' . $key) }}">
                    </div>
                    @endforeach
                </div>

                <div class="mb-5">
                    <label class="form-label d-block required">โรคเสื้อ</label>
                    @foreach(['S','M','L','XL'] as $size)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][body][shirt_size]" value="{{ $size }}" {{ $checked('health.body.shirt_size', $size) }}>
                        <span class="form-check-label">{{ $size }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="mb-5">
                    <label class="form-label d-block required">กรุ๊ปเลือด</label>
                    @foreach(['A','B','O','AB'] as $blood)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][body][blood_type]" value="{{ $blood }}" {{ $checked('health.body.blood_type', $blood) }}>
                        <span class="form-check-label">{{ $blood }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">ค่าสายตา (ซ้าย)</label>
                        <label class="form-check mb-2">
                            <input type="hidden" name="application_detail[health][body][left_eye_normal]" value="0">
                            <input class="form-check-input" type="checkbox" name="application_detail[health][body][left_eye_normal]" value="1" {{ $d('health.body.left_eye_normal') ? 'checked' : '' }}>
                            <span class="form-check-label">ปกติ</span>
                        </label>
                        <input type="text" name="application_detail[health][body][left_eye]" class="form-control" value="{{ $d('health.body.left_eye') }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">ค่าสายตา (ขวา)</label>
                        <label class="form-check mb-2">
                            <input type="hidden" name="application_detail[health][body][right_eye_normal]" value="0">
                            <input class="form-check-input" type="checkbox" name="application_detail[health][body][right_eye_normal]" value="1" {{ $d('health.body.right_eye_normal') ? 'checked' : '' }}>
                            <span class="form-check-label">ปกติ</span>
                        </label>
                        <input type="text" name="application_detail[health][body][right_eye]" class="form-control" value="{{ $d('health.body.right_eye') }}">
                    </div>
                </div>

                <div class="mb-5">

                    <label class="form-label d-block required">
                        สวมแว่นตา หรือคอนแทคเลนส์
                        <span class="text-danger">*</span>
                    </label>

                    @foreach([
                    'ไม่สวมแว่นตาและคอนแทคเลนส์',
                    'แว่นตา',
                    'คอนแทคเลนส์'
                    ] as $option)

                    <label class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="application_detail[health][body][eyewear_type]"
                            value="{{ $option }}"
                            {{ $checked('health.body.eyewear_type', $option) }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>

                    @endforeach

                </div>

                <div class="row">

                    <div class="col-md-6 mb-4">
                        <label class="form-label">ซ้าย</label>
                        <input
                            type="text"
                            class="form-control"
                            name="application_detail[health][body][vision_left]"
                            placeholder="(สั้น -) (ยาว +)"
                            value="{{ $d('health.body.vision_left') }}">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">ขวา</label>
                        <input
                            type="text"
                            class="form-control"
                            name="application_detail[health][body][vision_right]"
                            placeholder="(สั้น -) (ยาว +)"
                            value="{{ $d('health.body.vision_right') }}">
                    </div>

                </div>
                @foreach([
                'color_vision' => 'ตาบอดสี',
                'dominant_hand' => 'ถนัดมือ',
                ] as $key => $label)
                <div class="mb-5">
                    <label class="form-label d-block required">{{ $label }}</label>
                    @foreach(['ไม่มี', 'มี', 'ซ้าย', 'ขวา'] as $option)
                    @if(in_array($key, ['dominant_hand', 'dominant_leg']) && !in_array($option, ['ซ้าย', 'ขวา']))
                    @continue
                    @endif
                    @if(!in_array($key, ['dominant_hand', 'dominant_leg']) && !in_array($option, ['ไม่มี', 'มี']))
                    @continue
                    @endif
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][body][{{ $key }}]" value="{{ $option }}" {{ $checked('health.body.' . $key, $option) }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
                @endforeach

                <h4 class="mb-4">ประวัติการเจ็บป่วย</h4>
                @foreach([
                'operation' => 'ทำศัลยกรรมหรือไม่ *',
                'admission' => 'ประวัติการเจ็บป่วย *',
                'serious_accident' => 'ประวัติการเกิดอุบัติเหตุ',
                'chronic_family' => 'เคยกระดูกหัก *',
                ] as $key => $label)
                <div class="border rounded p-4 mb-4">
                    <label class="form-label d-block required">{{ $label }}</label>
                    @foreach($key === 'chronic_family' ? ['ไม่เคยกระดูกหัก', 'เคยกระดูกหัก', 'เคยดามเหล็ก', 'มีเหล็กดาม'] : ['ไม่มี', 'มี'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][history][{{ $key }}][answer]" value="{{ $option }}" {{ $checked('health.history.' . $key . '.answer', $option, $key === 'chronic_family' ? 'ไม่เคยกระดูกหัก' : 'ไม่มี') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                    <textarea name="application_detail[health][history][{{ $key }}][detail]" class="form-control mt-3" rows="3" placeholder="รายละเอียด">{{ $d('health.history.' . $key . '.detail') }}</textarea>
                </div>
                @endforeach

                <h4 class="mb-4">โรคประจำตัว</h4>
                @foreach([
                'epilepsy' => 'โรคลมชัก/ลมบ้าหมู',
                'anemia' => 'โรคเลือดจาง',
                'hepatitis_b' => 'โรคไวรัสตับอักเสบบี',
                'other' => 'อื่น ๆ',
                ] as $key => $label)
                <div class="border rounded p-4 mb-4">
                    <label class="form-label d-block required">{{ $label }}</label>
                    @foreach(['ไม่ป่วย', 'ป่วย'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][chronic_diseases][{{ $key }}][answer]" value="{{ $option }}" {{ $checked('health.chronic_diseases.' . $key . '.answer', $option, 'ไม่ป่วย') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                    <textarea name="application_detail[health][chronic_diseases][{{ $key }}][detail]" class="form-control mt-3" rows="3" placeholder="รายละเอียด">{{ $d('health.chronic_diseases.' . $key . '.detail') }}</textarea>
                </div>
                @endforeach

                <h4 class="mb-4">อื่น ๆ</h4>
                <div class="border rounded p-4 mb-4">
                    <label class="form-label d-block required">ดื่มเหล้า *</label>
                    @foreach(['ไม่ดื่ม', 'ดื่มประจำ', 'ดื่มบางครั้ง'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][other][alcohol_status]" value="{{ $option }}" {{ $checked('health.other.alcohol_status', $option, 'ไม่ดื่ม') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                    <input type="text" name="application_detail[health][other][alcohol_detail]" class="form-control mt-3" placeholder="ปริมาณ / ความถี่ในการดื่ม" value="{{ $d('health.other.alcohol_detail') }}">
                </div>

                <div class="border rounded p-4 mb-4">
                    <label class="form-label d-block required">สูบบุหรี่ *</label>
                    @foreach(['ไม่สูบ', 'สูบเป็นประจำ', 'สูบบางครั้ง'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][other][smoking_status]" value="{{ $option }}" {{ $checked('health.other.smoking_status', $option, 'ไม่สูบ') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                    <input type="text" name="application_detail[health][other][smoking_detail]" class="form-control mt-3" placeholder="เฉลี่ยวันละ (มวน)" value="{{ $d('health.other.smoking_detail') }}">
                </div>

                <div class="border rounded p-4 mb-0">
                    <label class="form-label d-block required">รอยสัก *</label>
                    @foreach(['ไม่มี', 'มี'] as $option)
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="application_detail[health][other][tattoo_status]" value="{{ $option }}" {{ $checked('health.other.tattoo_status', $option, 'ไม่มี') }}>
                        <span class="form-check-label">{{ $option }}</span>
                    </label>
                    @endforeach
                    <textarea name="application_detail[health][other][tattoo_detail]" class="form-control mt-3" rows="3" placeholder="รายละเอียด">{{ $d('health.other.tattoo_detail') }}</textarea>
                    <input type="text" name="application_detail[health][other][tattoo_size]" class="form-control mt-3" placeholder="ขนาด" value="{{ $d('health.other.tattoo_size') }}">
                    <input type="file" name="tattoo_attachment" class="form-control mt-3" accept="image/jpeg,image/png,image/webp,application/pdf">
                    @if($d('health.other.tattoo_attachment_path'))
                    <div class="small mt-2">
                        ไฟล์เดิม:
                        <a href="{{ asset($d('health.other.tattoo_attachment_path')) }}" target="_blank">เปิดไฟล์</a>
                    </div>
                    @endif
                </div>

                <div class="card card-flush py-4 mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>ข้อมูลเพิ่มเติม</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-5">
                            <label class="form-label d-block required">เคยไปญี่ปุ่นหรือไม่ *</label>
                            @foreach(['ไม่เคยไป', 'เคยไป'] as $option)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="application_detail[additional][been_to_japan][answer]" value="{{ $option }}" {{ $checked('additional.been_to_japan.answer', $option, 'ไม่เคยไป') }}>
                                <span class="form-check-label">{{ $option }}</span>
                            </label>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-5">
                                <label class="form-label">วัตถุประสงค์ในการเดินทางไปประเทศญี่ปุ่น</label>
                                <input type="text" name="application_detail[additional][japan_purpose]" class="form-control" value="{{ $d('additional.japan_purpose') }}">
                            </div>
                            <div class="col-md-4 mb-5">
                                <label class="form-label">ไปในวีซ่าประเภทอะไร</label>
                                <input type="text" name="application_detail[additional][visa_type]" class="form-control" value="{{ $d('additional.visa_type') }}">
                            </div>
                            <div class="col-md-4 mb-5">
                                <label class="form-label">ระยะเวลาที่อยู่ประเทศญี่ปุ่น</label>
                                <input type="text" name="application_detail[additional][japan_stay_duration]" class="form-control" value="{{ $d('additional.japan_stay_duration') }}">
                            </div>
                        </div>

                        @foreach([
                        'passport' => ['label' => 'มีพาสปอร์ตหรือไม่ *', 'options' => ['ไม่มี', 'มี']],
                        'changed_first_name' => ['label' => 'เคยเปลี่ยนชื่อหรือไม่ *', 'options' => ['ไม่เคย', 'เคย']],
                        'changed_last_name' => ['label' => 'เคยเปลี่ยนนามสกุลหรือไม่ *', 'options' => ['ไม่เคย', 'เคย']],
                        'applied_japan_other_company' => ['label' => 'เคยสมัครงานไปญี่ปุ่นกับบริษัทอื่นหรือไม่ *', 'options' => ['ไม่เคย', 'เคยสมัคร']],
                        'criminal_case' => ['label' => 'เคยต้องคดีอาญาหรือไม่ *', 'options' => ['ไม่เคย', 'เคย']],
                        ] as $key => $item)
                        <div class="border rounded p-4 mb-5">
                            <label class="form-label d-block required">{{ $item['label'] }}</label>
                            @foreach($item['options'] as $option)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="application_detail[additional][{{ $key }}][answer]" value="{{ $option }}" {{ $checked('additional.' . $key . '.answer', $option) }}>
                                <span class="form-check-label">{{ $option }}</span>
                            </label>
                            @endforeach
                            @if($key === 'changed_first_name')
                            <input type="number" name="application_detail[additional][{{ $key }}][count]" class="form-control mt-3" placeholder="จำนวนครั้ง" value="{{ $d('additional.' . $key . '.count') }}">
                            @elseif($key === 'criminal_case')
                            <textarea name="application_detail[additional][{{ $key }}][detail]" class="form-control mt-3" rows="3" placeholder="รายละเอียด">{{ $d('additional.' . $key . '.detail') }}</textarea>
                            @endif
                        </div>
                        @endforeach

                        <div class="border rounded p-4 mb-5">
                            <label class="form-label d-block required">ใบอนุญาตขับขี่ *</label>
                            @foreach(['ไม่มี', 'มี'] as $option)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="application_detail[additional][driver_license][answer]" value="{{ $option }}" {{ $checked('additional.driver_license.answer', $option, 'ไม่มี') }}>
                                <span class="form-check-label">{{ $option }}</span>
                            </label>
                            @endforeach
                            <div class="mt-3">
                                @foreach(['รถจักรยานยนต์', 'รถยนต์'] as $type)
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="application_detail[additional][driver_license][types][]" value="{{ $type }}" {{ in_array($type, (array) $d('additional.driver_license.types', [])) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $type }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        @foreach([
                        'self_introduction' => 'แนะนำตัวเอง',
                        'hobby' => 'งานอดิเรกที่ชอบ',
                        'special_ability' => 'ความสามารถพิเศษที่มี',
                        'good_habit' => 'นิสัยข้อดีของตัวเอง *',
                        'bad_habit' => 'นิสัยข้อเสียของตัวเอง *',
                        ] as $key => $label)
                        <div class="mb-5">
                            <label class="form-label">{{ $label }}</label>
                            <textarea name="application_detail[additional][{{ $key }}]" class="form-control" rows="3">{{ $d('additional.' . $key) }}</textarea>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card card-flush py-4 mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <h2>ภาระที่ต้องรับผิดชอบ</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border rounded p-4 mb-5">
                        <label class="form-label d-block required">พ่อแม่เห็นด้วยกับการเข้าร่วมโครงการหรือไม่ *</label>
                        @foreach(['ไม่เห็นด้วย', 'เห็นด้วย'] as $option)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="application_detail[responsibility][parent_approval]" value="{{ $option }}" {{ $checked('responsibility.parent_approval', $option) }}>
                            <span class="form-check-label">{{ $option }}</span>
                        </label>
                        @endforeach
                        <input type="text" name="application_detail[responsibility][parents_live_with_when_japan]" class="form-control mt-3" placeholder="พ่อแม่อยู่กับใครหากเราไปญี่ปุ่น" value="{{ $d('responsibility.parents_live_with_when_japan') }}">
                    </div>

                    <div class="border rounded p-4 mb-5">
                        <label class="form-label d-block required">มีบุตรหรือไม่ *</label>
                        @foreach(['ไม่มี', 'มี'] as $option)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="application_detail[responsibility][has_children][answer]" value="{{ $option }}" {{ $checked('responsibility.has_children.answer', $option, 'ไม่มี') }}>
                            <span class="form-check-label">{{ $option }}</span>
                        </label>
                        @endforeach
                        <input type="text" name="application_detail[responsibility][has_children][caretaker]" class="form-control mt-3" placeholder="ใครดูแลบุตร" value="{{ $d('responsibility.has_children.caretaker') }}">
                    </div>

                    <div class="border rounded p-4 mb-5">
                        <label class="form-label d-block required">มีหนี้สินที่ต้องรับผิดชอบหรือไม่ *</label>
                        @foreach(['ไม่มี', 'มี'] as $option)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="application_detail[responsibility][debt][answer]" value="{{ $option }}" {{ $checked('responsibility.debt.answer', $option, 'ไม่มี') }}>
                            <span class="form-check-label">{{ $option }}</span>
                        </label>
                        @endforeach
                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <input type="text" name="application_detail[responsibility][debt][lender]" class="form-control" placeholder="กู้จาก" value="{{ $d('responsibility.debt.lender') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="application_detail[responsibility][debt][purpose]" class="form-control" placeholder="กู้มาทำอะไร" value="{{ $d('responsibility.debt.purpose') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="application_detail[responsibility][debt][amount]" class="form-control" placeholder="จำนวนที่กู้ (บาท)" value="{{ $d('responsibility.debt.amount') }}">
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">ภาระทางการทหาร (เฉพาะผู้ชาย)</h4>

                    <div class="border rounded p-4 mb-5">

                        <label class="form-label d-block">เกณฑ์ทหาร</label>
                        @foreach(['ร.ด.', 'เกณฑ์แล้ว', 'ยังไม่เกณฑ์'] as $option)
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="application_detail[responsibility][military_status]"
                                value="{{ $option }}"
                                {{ $checked('responsibility.military_status', $option) }}>
                            <span class="form-check-label">{{ $option }}</span>
                        </label>
                        @endforeach

                        <label class="form-label d-block mt-4">ภาระทางการทหาร</label>

                        @foreach([
                        'territorial_defense_year' => 'เรียนรักษาดินแดน(รด.) ปีที่',
                        'preparing_conscription_year' => 'อยู่ระหว่างเตรียมเกณฑ์ทหาร ปี',
                        'passed_conscription_year' => 'ผ่านการเกณฑ์ทหารมาแล้วเมื่อ ปี',
                        'deferred_conscription_year' => 'ผ่อนผันทหารแล้วเมื่อ ปี',
                        'not_due_conscription_year' => 'ยังไม่ถึงกำหนดเกณฑ์ทหาร ปี',
                        ] as $key => $label)

                        <div class="mb-3">
                            <label class="form-check mb-2">
                                <input class="form-check-input" type="radio"
                                    name="application_detail[responsibility][military][selected_type]"
                                    value="{{ $key }}"
                                    {{ $checked('responsibility.military.selected_type', $key) }}>
                                <span class="form-check-label">{{ $label }}</span>
                            </label>

                            @if($key == 'territorial_defense_year')
                            <input type="text"
                                name="application_detail[responsibility][military][{{ $key }}]"
                                class="form-control"
                                value="{{ $d('responsibility.military.' . $key) }}">
                            @endif
                        </div>

                        @endforeach

                    </div>


                    <h4 class="mb-3">(กรณีสมัครตรง) หลักทรัพย์ค้ำประกัน / จำนอง</h4>

                    <div class="border rounded p-4 mb-0">

                        @foreach(['เงินสด', 'โฉนดที่ดิน', 'อื่น ๆ'] as $option)
                        <div class="mb-3">
                            <label class="form-label">{{ $option }}</label>
                            <input type="text"
                                name="application_detail[guarantor][asset_detail][{{ $option }}]"
                                class="form-control"
                                value="{{ $d('guarantor.asset_detail.' . $option) }}">
                        </div>
                        @endforeach

                        <div class="mb-3">
                            <label class="form-label">เจ้าของกรรมสิทธิ์ที่ดิน</label>
                            <input type="text"
                                name="application_detail[guarantor][asset_owner]"
                                class="form-control"
                                value="{{ $d('guarantor.asset_owner') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">ประเภทที่ดิน</label>
                            @foreach(['ที่ทำกิน', 'ที่บ้าน'] as $option)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="application_detail[guarantor][land_type]"
                                    value="{{ $option }}"
                                    {{ $checked('guarantor.land_type', $option) }}>
                                <span class="form-check-label">{{ $option }}</span>
                            </label>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เนื้อที่ (ไร่)</label>
                            <input type="text"
                                name="application_detail[guarantor][land_area_rai]"
                                class="form-control"
                                value="{{ $d('guarantor.land_area_rai') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">ปัจจุบัน</label>
                            @foreach(['จำนอง', 'ปลดจำนอง'] as $option)
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="application_detail[guarantor][mortgage_status]"
                                    value="{{ $option }}"
                                    {{ $checked('guarantor.mortgage_status', $option) }}>
                                <span class="form-check-label">{{ $option }}</span>
                            </label>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div class="card card-flush py-4 mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <h2>เป้าหมาย</h2>
                    </div>
                </div>
                <div class="card-body">
                    @foreach([
                    'japan_goal' => 'เป้าหมายในการไปฝึกงาน ณ ประเทศญี่ปุ่นคืออะไร ทำไมต้องอยากไปญี่ปุ่น',
                    'return_plan' => 'เป้าหมายหลังกลับมาคืออะไร',
                    'after_three_years' => 'เมื่อครบสัญญา 3 ปี หลังกลับจากประเทศญี่ปุ่นแล้วจะทำอะไร',
                    ] as $key => $label)
                    <div class="mb-5">
                        <label class="form-label">{{ $label }}</label>
                        <textarea name="application_detail[goals][{{ $key }}]" class="form-control" rows="4">{{ $d('goals.' . $key) }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


    </div>
    <div class="col-lg-4">

        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลระบบ</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <label class="form-label">Member Code</label>
                    <input type="text" class="form-control" value="{{ $row?->member_code ?? 'Auto Generate' }}" disabled>
                </div>
                <div class="mb-0">
                    <label class="form-label">วันที่สมัคร</label>
                    <input type="text" class="form-control" value="{{ $row?->apply_date ? date('d/m/Y H:i', strtotime($row->apply_date)) : date('d/m/Y H:i') }}" disabled>
                </div>
            </div>
        </div>
        <div class="card card-flush py-4 mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h2>ข้อมูลเข้าสู่ระบบ</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ $m('username') }}" placeholder="เว้นว่างเพื่อสร้างอัตโนมัติ">
                </div>
                <div class="mb-5">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ $row ? 'เว้นว่างหากไม่เปลี่ยนรหัสผ่าน' : 'เว้นว่างเพื่อสร้างอัตโนมัติ' }}">
                </div>
                <div class="mb-5">
                    <label class="form-label">Email Login</label>
                    <input type="email" name="email" class="form-control" value="{{ $m('email') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $m('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $m('status') == 'active' ? 'selected' : '' }}>Approved</option>
                        <option value="inactive" {{ $m('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        @if($row)
        <div class="card card-flush py-4 mb-5">
            <div class="card-header align-items-center">
                <div class="card-title">
                    <h2>ข้อมูลผู้ปกครอง ({{ $row->parents->count() }})</h2>
                </div>
                <div class="card-toolbar">
                    <button type="submit" class="btn btn-sm btn-light-primary" form="create-parent-form">สร้างผู้ปกครองเพิ่ม</button>
                </div>
            </div>
            <div class="card-body">
                @if(session('parent_credentials'))
                <div class="alert alert-success">
                    <div class="fw-bold mb-2">สร้างบัญชีสำเร็จ กรุณาบันทึกรหัสผ่านนี้ทันที</div>
                    <div>Username: <code>{{ session('parent_credentials.username') }}</code></div>
                    <div>Email: <code>{{ session('parent_credentials.email') }}</code></div>
                    <div>Password: <code>{{ session('parent_credentials.password') }}</code></div>
                </div>
                @endif

                @forelse($row->parents as $parent)
                <div class="border rounded p-4 mb-4">
                    <div class="fw-bold">{{ $parent->username }}</div>
                    <div class="text-muted small">{{ $parent->member_code }}</div>
                    <div class="small mt-2">Email: {{ $parent->email }}</div>
                    <div class="small text-muted">สร้างเมื่อ {{ $parent->created_at ? $parent->created_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
                @empty
                <div class="alert alert-warning mb-0">ไม่พบข้อมูลผู้ปกครอง</div>
                @endforelse
            </div>
        </div>
        @endif

    </div>
    <script>
        document.addEventListener('click', function(event) {
            const addButton = event.target.closest('.js-repeat-add');
            if (addButton) {
                const group = addButton.closest('.js-repeat-group');
                const items = group.querySelectorAll('.js-repeat-item');
                const source = items[items.length - 1];
                const clone = source.cloneNode(true);
                const nextIndex = items.length;

                clone.dataset.repeatIndex = nextIndex;
                clone.querySelectorAll('input, textarea, select').forEach(function(field) {
                    if (field.name) {
                        field.name = field.name.replace(/\[(spouse_children|family_members)\]\[\d+\]/, '[$1][' + nextIndex + ']');
                    }

                    if (field.type === 'checkbox' || field.type === 'radio') {
                        field.checked = false;
                    } else {
                        field.value = '';
                    }
                });

                group.appendChild(clone);
                return;
            }

            const removeButton = event.target.closest('.js-repeat-remove');
            if (removeButton) {
                const group = removeButton.closest('.js-repeat-group');
                const items = group.querySelectorAll('.js-repeat-item');
                if (items.length <= 1) {
                    items[0].querySelectorAll('input, textarea, select').forEach(function(field) {
                        if (field.type === 'checkbox' || field.type === 'radio') {
                            field.checked = false;
                        } else {
                            field.value = '';
                        }
                    });
                    return;
                }

                removeButton.closest('.js-repeat-item').remove();
            }
        });
    </script>
