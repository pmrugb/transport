<table border="1">
    <thead>
        <tr>
            <th>Trip Date</th>
            <th>Transporter</th>
            <th>Vehicle</th>
            <th>Route</th>
            <th>Trips</th>
            <th>Fare Amount</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->calculation_date?->format('Y-m-d') }}</td>
                <td>{{ $payment->transporter?->name }}</td>
                <td>{{ $payment->vehicle?->registration_no }}</td>
                <td>{{ $payment->route?->route_name }}</td>
                <td>{{ $payment->no_of_trips }}</td>
                <td>{{ number_format((float) $payment->fare_amount, 2, '.', '') }}</td>
                <td>{{ number_format((float) $payment->total_amount, 2, '.', '') }}</td>
                <td>{{ $statuses[$payment->status] ?? ucfirst($payment->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
