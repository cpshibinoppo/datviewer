<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <a href="{{ url('/download') }}" class="btn btn-primary">Download Excel</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">data</th>
                <th scope="col">Check in</th>
                <th scope="col">Check out</th>
                <th scope="col">Working houre</th>
            </tr>
        </thead>
        <tbody>
            @php
                use Carbon\Carbon;

                // Initialize a new array to group data by staff_id and date
                $groupedData = [];

                foreach ($data as $item) {
                    $date = Carbon::parse($item->date_time)->format('Y-m-d');
                    $staffId = $item->staff_id;

                    // Group data by staff_id and date
                    if (!isset($groupedData[$staffId])) {
                        $groupedData[$staffId] = [];
                    }

                    if (!isset($groupedData[$staffId][$date])) {
                        $groupedData[$staffId][$date] = [];
                    }

                    $groupedData[$staffId][$date][] = $item->col_1; // Store col_1 (time) for each entry
                }

            @endphp

            @foreach ($groupedData as $staffId => $dates)
                @php
                    // Define staff names
                    $name = '';
                    if ($staffId == 1) {
                        $name = 'Abdul bari';
                    } elseif ($staffId == 2) {
                        $name = 'Fayiz';
                    } elseif ($staffId == 3) {
                        $name = 'Fasna';
                    } elseif ($staffId == 4) {
                        $name = 'Arshad';
                    } elseif ($staffId == 5) {
                        $name = 'Nabeel';
                    } elseif ($staffId == 6) {
                        $name = 'Farseen';
                    } else {
                        $name = 'n/a';
                    }
                @endphp

                @foreach ($dates as $date => $times)
                    @php
                        // Sort the times to get the earliest and latest times for check-in and check-out
                        sort($times);

                        // Create check-in and check-out dates based on actual date
                        $checkIn = Carbon::parse($date . ' ' . $times[0]); // First entry is check-in
                        $checkOut = count($times) > 1 ? Carbon::parse($date . ' ' . $times[count($times) - 1]) : null; // Last entry is check-out

                        // Define expected check-in and check-out times for that specific date
                        $expectedCheckIn = Carbon::parse($date . ' 09:30:00'); // Expected check-in time
                        $expectedCheckOut = Carbon::parse($date . ' 18:30:00'); // Expected check-out time

                        // Check if check-in is late or early
                        if ($checkIn->gt($expectedCheckIn)) {
                            $checkInStatus = ' - Late'; // Late if later than 09:30
                        } elseif ($checkIn->lt($expectedCheckIn)) {
                            $checkInStatus = ' - Early'; // Early if earlier than 09:30
                        } else {
                            $checkInStatus = ' - On Time'; // On Time if exactly 09:30
                        }

                        // Check if check-out is extra time or early leave
                        if ($checkOut) {
                            if ($checkOut->gt($expectedCheckOut)) {
                                $checkOutStatus = ' - Extra Time'; // Overtime if later than 18:30
                            } elseif ($checkOut->lt($expectedCheckOut)) {
                                $checkOutStatus = ' - Early Leave'; // Early if earlier than 18:30
                            } else {
                                $checkOutStatus = ' - On Time'; // On Time if exactly 18:30
                            }

                            // Calculate total hours and minutes worked
                            $totalDuration = $checkIn->diff($checkOut);
                            $hoursWorked = $totalDuration->h; // Get hours
                            $minutesWorked = $totalDuration->i; // Get minutes
                            $timeWorked = $hoursWorked . ' hours and ' . $minutesWorked . ' minutes';
                        } else {
                            $checkOutStatus = 'N/A';
                            $timeWorked = 'N/A';
                        }
                    @endphp
                    @if ($name != 'n/a')
                        <tr>
                            <th scope="row">{{ $staffId }}</th>
                            <td>{{ $name }}</td>
                            <td>{{ $date }}</td>
                            <td>{{ $checkIn->format('H:i:s') }}{{ $checkInStatus }}</td>
                            <td>{{ $checkOut ? $checkOut->format('H:i:s') : 'N/A' }}{{ $checkOut ? $checkOutStatus : '' }}
                            </td>
                            <td>{{ $timeWorked }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach

        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
