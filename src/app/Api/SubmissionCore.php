<?php
namespace TmlpStats\Api;

use App;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use TmlpStats as Models;
use TmlpStats\Api\Base\AuthenticatedApiBase;

class SubmissionCore extends AuthenticatedApiBase
{
    // Make a new SubmissionCore API.
    public function __construct(Context $context, Guard $auth, Request $request)
    {
        parent::__construct($context, $auth, $request);
    }

    /**
     * Initialize a submission, checking if parameters are valid.
     * @param  Models\Center $center        [description]
     * @param  Carbon        $reportingDate [description]
     * @return [type]                       [description]
     */
    public function initSubmission(Models\Center $center, Carbon $reportingDate)
    {
        $r1 = $this->checkCenterDate($center, $reportingDate);
        if (!$r1['success']) {
            return $r1;
        }

        $localReport = App::make(LocalReport::class);
        $lastValidReport = $localReport->getLastStatsReportSince($center, $reportingDate);
        $team_members = $localReport->getClassList($lastValidReport);
        $withdraw_codes = Models\WithdrawCode::get();

        return [
            'success' => true,
            'lookups' => compact('withdraw_codes', 'team_members'),
        ];
    }

    protected function checkCenterDate(Models\Center $center, Carbon $reportingDate)
    {
        // TODO check center access
        if (false) {
            return ['success' => false, 'error' => 'User not allowed access to submit this report'];
        }

        if ($reportingDate->dayOfWeek !== Carbon::FRIDAY) {
            return ['success' => false, 'error' => 'Reporting date must be a Friday.'];
        }

        // TODO check reporting date is in this center's quarter and so on.

        return ['success' => true];
    }

}