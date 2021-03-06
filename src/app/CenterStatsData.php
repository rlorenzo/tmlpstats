<?php
namespace TmlpStats;

use Carbon\Carbon;
use Eloquence\Database\Traits\CamelCaseModel;
use Illuminate\Database\Eloquent\Model;
use TmlpStats\Traits\CachedRelationships;

class CenterStatsData extends Model
{
    use CamelCaseModel, CachedRelationships;

    protected $table = 'center_stats_data';

    protected $center = null;

    protected $fillable = [
        'reporting_date',
        'stats_report_id',
        'type',
        'cap',
        'cpc',
        't1x',
        't2x',
        'gitw',
        'lf',
        'tdo',
        'points',
        'program_manager_attending_weekend',
        'classroom_leader_attending_weekend',
    ];

    protected $dates = [
        'reporting_date',
    ];

    public function scopeActual($query)
    {
        return $query->whereType('actual');
    }

    public function scopePromise($query)
    {
        return $query->whereType('promise');
    }

    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        return $query->where('reporting_date', '>=', $start)
                     ->where('reporting_date', '<=', $end);
    }

    public function scopeByCenter($query, Center $center)
    {
        $this->center = $center;

        return $query->whereIn('stats_report_id', function ($query) use ($center) {
            $query->select('id')
                ->from('stats_reports')
                ->whereCenterId($center->id);
        });
    }

    public function scopeByQuarter($query, Quarter $quarter)
    {
        return $query->whereIn('stats_report_id', function ($query) use ($quarter) {
            $query->select('id')
                ->from('stats_reports')
                ->whereQuarterId($quarter->id);
        });
    }

    public function scopeReportingDate($query, Carbon $date)
    {
        return $query->whereReportingDate($date);
    }

    public function scopeByStatsReport($query, StatsReport $statsReport)
    {
        return $query->whereStatsReportId($statsReport->id);
    }

    public function scopeOfficial($query)
    {
        return $query->whereIn('stats_report_id', function ($query) {
            $query->select('stats_report_id')
                  ->from('global_report_stats_report');
        });
    }

    public function statsReport()
    {
        return $this->belongsTo('TmlpStats\StatsReport');
    }
}
