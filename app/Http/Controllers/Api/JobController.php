<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Category1;
use App\Models\Backend\Job;
use App\Models\Backend\JobApplication;
use App\Models\Backend\JobApplicationLog;
use App\Models\Backend\Location;
use App\Models\Backend\Member;
use App\Models\Backend\MemberFavoriteJob;

class JobController extends Controller
{
    protected function filterArray(Request $request, array $keys): array
    {
        foreach ($keys as $key) {
            if (!$request->filled($key)) {
                continue;
            }

            $value = $request->input($key);

            if (is_array($value)) {
                return array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
            }

            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        return [];
    }

    protected function monthRange(?string $from, ?string $to): array
    {
        $start = $from ? date('Y-m-01', strtotime($from . '-01')) : null;
        $end = $to ? date('Y-m-t', strtotime($to . '-01')) : null;

        return [$start, $end];
    }

    protected function activeApplicationForMember(int $memberId): ?JobApplication
    {
        return JobApplication::with(['job.company'])
            ->where('member_id', $memberId)
            ->whereIn('status', JobApplication::activeStatuses())
            ->latest('id')
            ->first();
    }

    protected function canApplyPayload(Member $member): array
    {
        $latestStatus = JobApplication::where('member_id', $member->id)
            ->latest('id')
            ->value('status');
        $isActiveStatus = in_array($latestStatus, JobApplication::activeStatuses(), true);

        return [
            'can_apply' => !$isActiveStatus,
            'status' => $latestStatus,
            'active_status' => $isActiveStatus ? $latestStatus : null,
            'statuses' => JobApplication::allStatuses(),
            'active_statuses' => JobApplication::activeStatuses(),
        ];
    }

    public function index(Request $request)
    {
        try {
            $member = $request->user();
            $canApply = $member && $member->type === 'applicant'
                ? !$this->activeApplicationForMember($member->id)
                : false;
            $favoriteIds = $member
                ? MemberFavoriteJob::where('member_id', $member->id)->pluck('job_id')->all()
                : [];

            $query = Job::with([
                    'company',
                    'province',
                    'city',
                    'categories.category1',
                    'categories.category2',
                ])
                ->where('status', 'on');

            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('title_th', 'like', '%' . $search . '%')
                        ->orWhere('title_en', 'like', '%' . $search . '%')
                        ->orWhere('detail', 'like', '%' . $search . '%')
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name_th', 'like', '%' . $search . '%')
                                ->orWhere('name_en', 'like', '%' . $search . '%');
                        });
                });
            }

            $categoryIds = $this->filterArray($request, [
                'category2_ids',
                'category_ids',
                'categories',
                'job_categories',
            ]);

            if ($categoryIds) {
                $query->whereHas('categories', function ($categoryQuery) use ($categoryIds) {
                    $categoryQuery->whereIn('category2_id', $categoryIds);
                });
            }

            $category1Ids = $this->filterArray($request, [
                'category1_ids',
                'main_category_ids',
            ]);

            if ($category1Ids) {
                $query->whereHas('categories', function ($categoryQuery) use ($category1Ids) {
                    $categoryQuery->whereIn('category1_id', $category1Ids);
                });
            }

            $provinceIds = $this->filterArray($request, [
                'province_ids',
                'provinces',
                'province_id',
            ]);

            if ($provinceIds) {
                $query->whereIn('province_id', $provinceIds);
            }

            $jobTypes = $this->filterArray($request, ['job_types', 'job_type']);
            if ($jobTypes) {
                $query->whereIn('job_type', $jobTypes);
            }

            $overtimes = $this->filterArray($request, ['overtimes', 'overtime']);
            if ($overtimes) {
                $query->whereIn('overtime', $overtimes);
            }

            [$monthFrom, $monthTo] = $this->monthRange(
                $request->input('month_from', $request->input('start_month')),
                $request->input('month_to', $request->input('end_month'))
            );

            if ($request->filled('date_from')) {
                $monthFrom = $request->input('date_from');
            }

            if ($request->filled('date_to')) {
                $monthTo = $request->input('date_to');
            }

            if ($monthFrom && $monthTo) {
                $query->whereBetween('date', [$monthFrom, $monthTo]);
            } elseif ($monthFrom) {
                $query->whereDate('date', '>=', $monthFrom);
            } elseif ($monthTo) {
                $query->whereDate('date', '<=', $monthTo);
            }

            $months = $this->filterArray($request, ['months']);
            if ($months) {
                $query->where(function ($monthQuery) use ($months) {
                    foreach ($months as $month) {
                        $monthQuery->orWhere(function ($q) use ($month) {
                            $start = date('Y-m-01', strtotime($month . '-01'));
                            $end = date('Y-m-t', strtotime($month . '-01'));
                            $q->whereBetween('date', [$start, $end]);
                        });
                    }
                });
            }

            $salaryMin = $request->filled('salary_min') ? (int) $request->input('salary_min') : null;
            $salaryMax = $request->filled('salary_max') ? (int) $request->input('salary_max') : null;

            if ($salaryMin !== null || $salaryMax !== null) {
                $query->where(function ($salaryQuery) use ($salaryMin, $salaryMax) {
                    if ($salaryMin !== null && $salaryMax !== null) {
                        $salaryQuery->where(function ($q) use ($salaryMin, $salaryMax) {
                            $q->whereNull('salary_max')
                                ->orWhere('salary_max', '>=', $salaryMin);
                        })->where(function ($q) use ($salaryMax) {
                            $q->whereNull('salary_min')
                                ->orWhere('salary_min', '<=', $salaryMax);
                        });
                    } elseif ($salaryMin !== null) {
                        $salaryQuery->where(function ($q) use ($salaryMin) {
                            $q->where('salary_max', '>=', $salaryMin)
                                ->orWhere('salary_min', '>=', $salaryMin);
                        });
                    } elseif ($salaryMax !== null) {
                        $salaryQuery->where(function ($q) use ($salaryMax) {
                            $q->where('salary_min', '<=', $salaryMax)
                                ->orWhere('salary_max', '<=', $salaryMax);
                        });
                    }
                });
            }

            $jobs = $query
                ->latest()
                ->get()
                ->map(function ($job) use ($favoriteIds, $canApply) {
                    $job->is_favorite = in_array($job->id, $favoriteIds);
                    $job->can_apply = $canApply;

                    return $job;
                });

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลสำเร็จ',
                'results' => $jobs,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function filters(Request $request)
    {
        try {
            $categories = Category1::with(['category2' => function ($query) {
                    $query->where('status', 'on')->orderBy('name_th');
                }])
                ->where('status', 'on')
                ->orderBy('name_th')
                ->get();

            $provinces = Location::whereNull('parent_id')
                ->orderBy('name')
                ->get();

            $months = Job::where('status', 'on')
                ->whereNotNull('date')
                ->selectRaw("DATE_FORMAT(date, '%Y-%m') as value, MIN(date) as start_date, MAX(date) as end_date, COUNT(*) as total")
                ->groupBy('value')
                ->orderBy('value')
                ->get()
                ->map(function ($month) {
                    return [
                        'value' => $month->value,
                        'label' => date('F Y', strtotime($month->value . '-01')),
                        'start_date' => $month->start_date,
                        'end_date' => $month->end_date,
                        'total' => (int) $month->total,
                    ];
                });

            $salaryMin = Job::where('status', 'on')
                ->whereNotNull('salary_min')
                ->min('salary_min');

            $salaryMax = Job::where('status', 'on')
                ->whereNotNull('salary_max')
                ->max('salary_max');

            $jobTypes = Job::where('status', 'on')
                ->whereNotNull('job_type')
                ->where('job_type', '!=', '')
                ->distinct()
                ->orderBy('job_type')
                ->pluck('job_type')
                ->values();

            $overtimes = Job::where('status', 'on')
                ->whereNotNull('overtime')
                ->where('overtime', '!=', '')
                ->distinct()
                ->orderBy('overtime')
                ->pluck('overtime')
                ->values();

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลตัวกรองงานสำเร็จ',
                'results' => [
                    'categories' => $categories,
                    'months' => $months,
                    'provinces' => $provinces,
                    'salary_range' => [
                        'min' => $salaryMin ? (int) $salaryMin : 0,
                        'max' => $salaryMax ? (int) $salaryMax : 0,
                    ],
                    'job_types' => $jobTypes,
                    'overtimes' => $overtimes,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function favorite(Request $request)
    {
        $member = $request->user();

        if ($member->type !== 'applicant') {
            return response()->json([
                'status' => false,
                'message' => 'เฉพาะสมาชิกผู้สมัครงานเท่านั้น',
            ], 403);
        }

        $validated = $request->validate([
            'job_id' => ['required', 'integer', 'exists:jobs,id'],
        ]);

        MemberFavoriteJob::firstOrCreate([
            'member_id' => $member->id,
            'job_id' => $validated['job_id'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'เพิ่มงานที่สนใจสำเร็จ',
        ]);
    }

    public function favoriteList(Request $request)
    {
        $member = $request->user() ;

        if ($member->type !== 'applicant') {
            return response()->json([
                'status' => false,
                'message' => 'เฉพาะสมาชิกผู้สมัครงานเท่านั้น',
            ], 403);
        }

        $favorites = MemberFavoriteJob::with([
                'job.company',
                'job.province',
            ])
            ->where('member_id', $member->id)
            ->latest('id')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'job_id' => $favorite->job_id,
                    'created_at' => $favorite->created_at,
                    'job' => $favorite->job,
                ];
            });

        return response()->json([
            'status' => true,
            'results' => [
               
                'favorites' => $favorites,
            ],
        ]);
    }

    public function unfavorite(Request $request, int $jobId)
    {
        $member = $request->user();

        if ($member->type !== 'applicant') {
            return response()->json([
                'status' => false,
                'message' => 'เฉพาะสมาชิกผู้สมัครงานเท่านั้น',
            ], 403);
        }

        MemberFavoriteJob::where('member_id', $member->id)
            ->where('job_id', $jobId)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'ลบงานที่สนใจสำเร็จ',
        ]);
    }

    public function apply(Request $request)
    {
        $member = Member::with([
            'profile',
            'applicationDetail',
        ])->findOrFail($request->user()->id);

        if ($member->type !== 'applicant') {
            return response()->json([
                'status' => false,
                'message' => 'เฉพาะสมาชิกผู้สมัครงานเท่านั้น',
            ], 403);
        }

        $validated = $request->validate([
            'job_id' => ['required', 'integer', 'exists:jobs,id'],
            'note' => ['nullable', 'string'],
        ]);

        $activeApplication = $this->activeApplicationForMember($member->id);
        if ($activeApplication) {
            return response()->json([
                'status' => false,
                'message' => 'ยังไม่สามารถสมัครงานใหม่ได้ เนื่องจากมีใบสมัครที่ยังไม่สิ้นสุดสถานะ',
                'results' => [
                    'job_application_permission' => $this->canApplyPayload($member),
                ],
            ], 422);
        }

        $job = Job::with('company')->findOrFail($validated['job_id']);

        $existingSameJob = JobApplication::where('member_id', $member->id)
            ->where('job_id', $job->id)
            ->whereIn('status', JobApplication::activeStatuses())
            ->exists();

        if ($existingSameJob) {
            return response()->json([
                'status' => false,
                'message' => 'มีใบสมัครงานนี้อยู่แล้ว',
            ], 422);
        }

        $profile = $member->profile;
        $gender = $profile->gender ?? $request->gender;

        $genderMap = [
            'ชาย' => 'male',
            'หญิง' => 'female',
            'อื่นๆ' => 'other',
            'อื่น ๆ' => 'other',
            'male' => 'male',
            'female' => 'female',
            'other' => 'other',
        ];

        if ($gender === 'ชาย') {
            $gender = 'male';
        } elseif ($gender === 'หญิง') {
            $gender = 'female';
        } elseif (in_array($gender, ['อื่นๆ', 'อื่น ๆ'], true)) {
            $gender = 'other';
        } else {
            $gender = $genderMap[$gender] ?? 'other';
        }
        DB::beginTransaction();

        try {
            $application = JobApplication::create([
                'member_id' => $member->id,
                'job_id' => $job->id,
                'first_name' => $profile->first_name_th ?? $member->name ?? '',
                'last_name' => $profile->last_name_th ?? '',
                'gender' => $gender,
                'age' => $profile->age ?? null,
                'phone' => $profile->phone ?? '',
                'email' => $member->email ?? ($profile->email_contact ?? ''),
                'line_id' => $profile->line_id ?? '',
                'province_id' => $profile->province_id ?? null,
                'city_id' => $profile->district_id ?? null,
                'address' => $profile->current_address ?? '',
                'education' => data_get($member->applicationDetail, 'education_extra.current_level_other', ''),
                'work_experience' => json_encode(data_get($member->applicationDetail, 'work_family.work_experiences', []), JSON_UNESCAPED_UNICODE),
                'japanese_level' => data_get($member->applicationDetail, 'language_training.japanese.level', ''),
                'resume_file' => null,
                'note' => $validated['note'] ?? null,
                'status' => JobApplication::STATUS_NEW,
            ]);

            JobApplicationLog::create([
                'application_id' => $application->id,
                'old_status' => null,
                'new_status' => JobApplication::STATUS_NEW,
                'remark' => 'สมัครงานผ่าน API',
                'created_by' => null,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'สมัครงานสำเร็จ',
                'results' => [
                    'application' => $application->load('job.company'),
                  
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json([
                'status' => false,
                'message' => 'ไม่สามารถสมัครงานได้',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function applications(Request $request)
    {
        $member = $request->user();

        if ($member->type !== 'applicant') {
            return response()->json([
                'status' => false,
                'message' => 'เฉพาะสมาชิกผู้สมัครงานเท่านั้น',
            ], 403);
        }

        $applications = JobApplication::with(['job.company'])
            ->where('member_id', $member->id)
            ->latest('id')
            ->get();

        return response()->json([
            'status' => true,
            'results' => [
                
                'applications' => $applications,
            ],
        ]);
    }
}
