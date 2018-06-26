@extends('template')

@section('content')
    <h2>{{ $statsReport->center->name }}</h2>

    <div class="row">
        <div class="col-sm-11">
            <h3>Current Stats</h3>
            @include('reports.centergames.week', ['reportData' => $liveData->toArray(), 'liveScoreboard'=>false])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
            <h3>Last submitted stats</h3>
            @include('reports.centergames.week', ['liveScoreboard' => false])
            Updated {{ $statsReport->submittedAt->timezone($statsReport->center->timezone) }}
        </div>
    </div>
    @if ($completedCourses)
    <div class="row">
        <div class="col-sm-12">
                <h4>Course Results:</h4>
                <!--<dl class="dl-horizontal">-->
                    @foreach ($completedCourses as $courseData)
                        <span style="text-decoration: underline">{{ $courseData['type'] }}
                            - {{ $courseData['startDate']->format('M j') }}</span>
                        <dl class="dl-horizontal">
                            <dt>Standard Starts:</dt>
                            <dd>{{ $courseData['currentStandardStarts'] }}</dd>
                            <dt>Reg Fulfillment:</dt>
                            <dd>{{ $courseData['completionStats']['registrationFulfillment'] }}%</dd>
                            <dt>Reg Effectiveness:</dt>
                            <dd>{{ $courseData['completionStats']['registrationEffectiveness'] }}%</dd>
                            <dt>Registrations:</dt>
                            <dd>{{ $courseData['registrations'] }}</dd>
                        </dl>
                    @endforeach
                <!--</dl>-->
        </div>
    </div>
    @endif

    @if ($upcomingCourses)
    <div class="row">
        <div class="col-md-6">
            <h4>Upcoming Courses:</h4>
            <dl class="dl-horizontal">
                @foreach ($upcomingCourses as $courseData)
                    <span style="text-decoration: underline">{{ $courseData['type'] }}
                        - {{ $courseData['startDate']->format('M j') }}</span>
                    <dl class="dl-horizontal">
                        <dt>Standard Starts:</dt>
                        <dd>{{ $courseData['currentStandardStarts'] }}</dd>
                        <dt>Guests Promised:</dt>
                        <dd>{{ (int) $courseData['guestsPromised'] }}</dd>
                        <dt>Guests Invited:</dt>
                        <dd>{{ (int) $courseData['guestsInvited'] }}</dd>
                        <dt>Guests Confirmed:</dt>
                        <dd>{{ (int) $courseData['guestsConfirmed'] }}</dd>
                    </dl>
                @endforeach
            </dl>
        </div>
    </div>
    @endif

</div>
@endsection
