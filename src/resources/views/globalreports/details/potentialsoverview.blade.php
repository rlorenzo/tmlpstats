<div class="table-responsive">
    <br/>
    <h4>Applications Overview</h4>
    <table class="table table-condensed table-striped table-hover applicationTable">
        <thead>
        <tr>
            <th class="border-right">Center</th>
            <th class="data-point">T1Q4</th>
            <th class="data-point">Registered</th>
            <th class="data-point">Approved</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reportData as $centerName => $centerData)
            <tr>
                <td class="border-right">{{ $centerName }}</td>
                <td class="data-point">{{ $centerData['total'] }}</td>
                <td class="data-point">{{ $centerData['registered'] }}</td>
                <td class="data-point">{{ $centerData['approved'] }}</td>
            </tr>
        @endforeach
        </tbody>
        {{-- This is pretty janky, but putting this row outside of the tbody causes datatables from including it in the sort --}}
        <tr style="font-weight:bold">
            <td class="border-right">Totals</td>
            <td class="data-point">{{ $totals['total'] }}</td>
            <td class="data-point">{{ $totals['registered'] }}</td>
            <td class="data-point">{{ $totals['approved'] }}</td>
        </tr>
    </table>
</div>
