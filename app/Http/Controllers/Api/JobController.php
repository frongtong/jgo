<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Job;
use App\Models\Backend\JobApplication;
use App\Models\Backend\JobApplicationLog;
use App\Models\Backend\Member;
use App\Models\Backend\MemberFavoriteJob;

class JobController extends Controller
{
    protected function activeApplicationForMember(int $memberId): ?JobApplication
    {
        return JobApplication::with(['job.company'])
            ->where('member_id', $memberId)
            ->whereIn('status', JobApplication::activeStatuses())
            ->latest('id')
            ->first();
    }

    // protected function canApplyPayload(Member $member): array
    // {
    //     $activeApplication = $this->activeApplicationForMember($member->id);

    //     return [
    //         'can_apply' => $activeApplication ? false : true,
    //         'active_application' => $activeApplication,
    //         'active_statuses' => JobApplication::activeStatuses(),
    //     ];
    // }

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

            $jobs = Job::with([
                    'company',
                    'province',
                ])
                ->where('status', 'on')
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
