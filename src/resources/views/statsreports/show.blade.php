@extends('template')

@section('headers')
    <style type="text/css">
        table.centerStatsTable {
            width: 95%;
        }
        .centerStatsTable th, .centerStatsTable td {
            text-align: center;
        }
        .centerStatsTable td {
             width: 4em;
        }

        .centerStatsSummaryTable th, .centerStatsSummaryTable td {
            text-align: center;
            width: 4em;
        }
        table.centerStatsSummaryTable {
            width: 400px;
        }
        table {
            empty-cells: show;
        }

        .table-hover>tbody>tr:hover>td, .table-hover>tbody>tr:hover>th {
            background-color: #DDDDDD;
        }
    </style>
@endsection

@section('content')

@if ($statsReport)
    <h2>{{ $statsReport->center->name }} - {{ $statsReport->reportingDate->format('F j, Y') }}</h2>
    <a href="{{ \URL::previous() }}"><< See All</a><br/><br/>

    {!! Form::open(['url' => "statsreports", 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'reportSelectorForm']) !!}
    <div class="form-group">
        {!! Form::label('report_id', 'Other Reports:', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('report_id', $otherStatsReports, $statsReport->id, ['class' => 'form-control reportSelector']) !!}

            @if ($globalReport)
                <br/>
                <a href="{{ url("globalreports/{$globalReport->id}") }}">View Regional Report</a>
                <br/><br/>
            @endif
        </div>
    </div>
    {!! Form::close() !!}

    <div id="content">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li class="active"><a href="#summary-tab" data-toggle="tab">Weekly Summary</a></li>
            <li><a href="#overview-tab" data-toggle="tab">Report Details</a></li>
            <li><a href="#centerstats-tab" data-toggle="tab">Center Games</a></li>
            <li><a href="#classlist-tab" data-toggle="tab">Team Members</a></li>
            <li><a href="#tmlpregistrations-tab" data-toggle="tab">Team Expansion</a></li>
            <li><a href="#courses-tab" data-toggle="tab">Courses</a></li>
            <li><a href="#contactinfo-tab" data-toggle="tab">Contact Info</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="summary-tab">
                <h3>Week Summary</h3>
                <div id="summary-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="overview-tab">
                <h3>Report Details</h3>
                @include('statsreports.details.overview', ['statsReport' => $statsReport, 'sheetUrl' => $sheetUrl])
                <h3>Results</h3>
                <div id="results-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="centerstats-tab">
                <h3>Center Games</h3>
                <div id="centerstats-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="classlist-tab">
                <h3>Team Members</h3>
                <div id="classlist-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="tmlpregistrations-tab">
                <h3>Team Expansion</h3>
                <div id="tmlpregistrations-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="courses-tab">
                <h3>Courses</h3>
                <div id="courses-container">
                    @include('partials.loading')
                </div>
            </div>
            <div class="tab-pane" id="contactinfo-tab">
                <h3>Contact Info</h3>
                <div id="contactinfo-container">
                    @include('partials.loading')
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#tabs').tab();

            $('select.reportSelector').change(function() {
                var baseUrl = "{{ url('statsreports') }}";
                var newReport = $('.reportSelector option:selected').val();
                window.location.replace(baseUrl + '/' + newReport);
            });

            // Fetch Summary
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/summary') }}",
                success: function(response) {
                    $("#summary-container").html(response);
                }
            });

            // Fetch Validation Results
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/results') }}",
                success: function(response) {
                    $("#results-container").html(response);
                }
            });

            // Fetch Center Stats data
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/centerstats') }}",
                success: function(response) {
                    $("#centerstats-container").html(response);
                }
            });

            // Fetch Classlist
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/classlist') }}",
                success: function(response) {
                    $("#classlist-container").html(response);
                }
            });

            // Fetch Team Registrations
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/tmlpregistrations') }}",
                success: function(response) {
                    $("#tmlpregistrations-container").html(response);
                }
            });

            // Fetch Courses
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/courses') }}",
                success: function(response) {
                    $("#courses-container").html(response);
                }
            });

            // Fetch Contact Info
            $.ajax({
                type: "GET",
                url: "{{ url('/statsreports/' . $statsReport->id . '/contactinfo') }}",
                success: function(response) {
                    $("#contactinfo-container").html(response);
                }
            });
        });
    </script>

@else
    <p>Unable to find report.</p>
@endif

@endsection