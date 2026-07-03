<?php

namespace App\Services;

use App\Models\Backend\GeneralNotification;
use App\Models\Backend\JobApplication;
use App\Models\Backend\Member;
use Carbon\Carbon;

class MemberNotificationService
{
    public function forMember(Member $member): array
    {
        return collect()
            ->merge($this->interviewReminders($member))
            ->merge($this->generalNotices())
            ->sortBy('notification_date')
            ->values()
            ->all();
    }

    protected function interviewReminders(Member $member)
    {
        if ($member->type !== 'applicant') {
            return collect();
        }

        $today = Carbon::today();
        $monthEnd = $today->copy()->addMonth();

        return JobApplication::with(['job.company'])
            ->where('member_id', $member->id)
            ->whereNotNull('interview_date')
            ->whereDate('interview_date', '>=', $today)
            ->whereDate('interview_date', '<=', $monthEnd)
            ->get()
            ->filter(function ($application) use ($today) {
                $interviewDate = Carbon::parse($application->interview_date)->startOfDay();

                return $today->betweenIncluded(
                    $interviewDate->copy()->subDays(3),
                    $interviewDate
                );
            })
            ->map(function ($application) {
                $interviewDate = Carbon::parse($application->interview_date)->format('Y-m-d');
                $jobTitle = $application->job->title_th
                    ?? $application->job->title_en
                    ?? 'งานที่สมัคร';

                return [
                    'id' => 'interview_' . $application->id,
                    'type' => 'interview_reminder',
                    'title' => 'แจ้งเตือนวันสัมภาษณ์งาน',
                    'detail' => 'คุณมีนัดสัมภาษณ์งาน ' . $jobTitle . ' วันที่ ' . $interviewDate,
                    'notification_date' => Carbon::today()->format('Y-m-d'),
                    'interview_date' => $interviewDate,
                    'interview_time' => $application->interview_time,
                    'interview_location' => $application->interview_location,
                    'application_id' => $application->id,
                    'job_id' => $application->job_id,
                ];
            });
    }

    protected function generalNotices()
    {
        $today = Carbon::today();
        $monthEnd = $today->copy()->addMonth();

        return GeneralNotification::where('status', 'on')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->whereDate('start_date', '<=', $monthEnd)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($notice) {
                return [
                    'id' => 'general_' . $notice->id,
                    'type' => 'general_notice',
                    'title' => $notice->title,
                    'detail' => $notice->detail,
                    'notification_date' => optional($notice->start_date)->format('Y-m-d'),
                    'start_date' => optional($notice->start_date)->format('Y-m-d'),
                    'end_date' => optional($notice->end_date)->format('Y-m-d'),
                ];
            });
    }
}
