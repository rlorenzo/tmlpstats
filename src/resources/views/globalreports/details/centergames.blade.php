<?php
    $includeActual = isset($includeActual)
        ? $includeActual
        : false;

    $includeOriginal = isset($includeOriginal)
        ? $includeOriginal
        : false;

    $games = ['cap','cpc','t1x','t2x','gitw','lf'];
    $gameSpan = 1;

    if ($includeActual) {
        $gameSpan++;
    }

    if ($includeOriginal) {
        $gameSpan++;
    }
?>
<br>
<h5>
    @if ($includeOriginal)
        Data for last week of the quarter
    @else
        Data from this week
    @endif
</h5>
<div class="table-responsive">
    <table class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th rowspan="2" class="border-right" style="vertical-align: middle">Center</th>
            @foreach ($games as $game)
                <th colspan="{{ $gameSpan }}" class="data-point border-right">{{ strtoupper($game) }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($games as $game)
                @if ($includeOriginal)
                    <th class="data-point">Original</th>
                @endif
                <th class="data-point {{ $includeActual ? '' : 'border-right' }}">
                    @if ($includeOriginal)
                        New Promise
                    @else
                        Promise
                    @endif
                </th>
                @if ($includeActual)
                    <th class="data-point border-right">Actual</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($reportData as $centerName => $centerData)
        <tr>
            <th class="border-right">
                {{ $centerName }}
            </th>
            @foreach ($games as $game)
                <?php
                    $gameData = $centerData['games'][$game];

                    if (!$includeOriginal) {
                        $repromiseClass = '';
                    } else {
                        $gameData['original'] = $gameData['original'] ?? $gameData['promise'];
                        $repromiseClass = ($gameData['promise'] < $gameData['original'])
                            ? 'bg-danger'
                            : 'success';

                        if ($gameData['promise'] != $gameData['original'] && !$includeActual && $repromiseClass == 'success') {
                            $repromiseClass = 'bg-green';
                        }
                    }

                    if ($includeActual) {
                        $actualClass = ($gameData['promise'] > $gameData['actual'])
                            ? 'bg-danger'
                            : 'success';
                    }
                ?>
                @if ($includeOriginal)
                    <td class="data-point">{{ $gameData['original'] }}{{ ($game == 'gitw') ? '%' : '' }}</td>
                @endif
                <td class="data-point {{ $includeActual ? '' : 'border-right' }} {{ $repromiseClass }}">
                    {{ $gameData['promise'] }}{{ ($game == 'gitw') ? '%' : '' }}
                </td>
                @if ($includeActual)
                    <td class="data-point border-right {{ $actualClass }}">
                        @if (isset($gameData['actual']))
                            {{ $gameData['actual'] }}{{ ($game == 'gitw') ? '%' : '' }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                @endif
            @endforeach
        </tr>
        @endforeach
        <tr class="border-top {{ $includeOriginal ? '' : 'border-bottom' }}">
            <th class="border-right">Totals</th>
            @foreach ($games as $game)
                @if ($includeOriginal)
                    <td class="data-point">{{ $totals[$game]['original'] }}{{ ($game == 'gitw') ? '%' : '' }}</td>
                @endif
                <td class="data-point {{ $includeActual ? '' : 'border-right' }}">{{ $totals[$game]['promise'] }}{{ ($game == 'gitw') ? '%' : '' }}</td>
                @if ($includeActual)
                    <?php
                        $actualClass = '';
                        if (!$includeOriginal) {
                            $actualClass = ($totals[$game]['promise'] >  $totals[$game]['actual'])
                                ? 'bg-danger'
                                : 'success';
                        }
                    ?>
                    <td class="data-point border-right {{ $actualClass }}">
                        {{ isset($totals[$game]['actual']) ? $totals[$game]['actual'] : '&nbsp;' }}{{ (isset($totals[$game]['actual']) && $game == 'gitw') ? '%' : '' }}
                    </td>
                @endif
            @endforeach
        </tr>
        @if ($includeOriginal)
            <tr class="border-bottom">
                <th class="border-right">Change</th>
                @foreach ($games as $game)
                    <?php
                        $changeClass = $totals[$game]['delta'] < 0
                            ? 'bg-danger'
                            : 'success';
                    ?>
                    <td colspan="2" class="data-point {{ $includeActual ? '' : 'border-right' }} {{ $changeClass }}">{{ $totals[$game]['delta'] }}{{ ($game == 'gitw') ? '%' : '' }}</td>
                    @if ($includeActual)
                        <td class="data-point border-right {{ $changeClass }}">
                            @if (isset($totals[$game]['promise']) && isset($totals[$game]['actual']))
                                {{ $totals[$game]['actual'] - $totals[$game]['promise'] }}{{ ($game == 'gitw') ? '%' : '' }}
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="border-bottom">
                <th class="border-right">% Change</th>
                @foreach ($games as $game)
                    <?php
                        $changeClass = $totals[$game]['delta'] < 0
                            ? 'bg-danger'
                            : 'success';
                    ?>
                    <td colspan="2" class="data-point border-right {{ $changeClass }}">
                        {{ round(($totals[$game]['delta'] / $totals[$game]['original']) * 100) }}%
                    </td>
                @endforeach
            </tr>
        @endif
        </tbody>
    </table>
</div>
