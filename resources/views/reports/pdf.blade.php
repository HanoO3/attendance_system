<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a202c;
            padding: 24px;
            background: #fff;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 3px solid #6366f1;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 6px;
            letter-spacing: .02em;
        }
        .meta-row {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .meta-item {
            display: table-cell;
            text-align: center;
            padding: 6px 10px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .meta-item strong {
            display: block;
            font-size: 11px;
            color: #111827;
            font-weight: 800;
            margin-top: 2px;
        }
        .meta-label {
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-size: 9px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th {
            background-color: #111827;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: .08em;
            padding: 9px 8px;
            text-align: center;
            border: 1px solid #374151;
        }
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: center;
            color: #374151;
            font-size: 11px;
        }
        td.name-col { text-align: left; font-weight: 600; color: #111827; }
        tr:nth-child(even) td { background-color: #f9fafb; }
        tr:hover td { background-color: #eef2ff; }

        /* BADGES */
        .badge {
            padding: 3px 7px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 10px;
            display: inline-block;
        }
        .badge-green  { background-color: #d1fae5; color: #065f46; }
        .badge-red    { background-color: #fee2e2; color: #991b1b; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
        .badge-orange { background-color: #ffedd5; color: #9a3412; }
        .pct-good { color: #059669; font-weight: 800; }
        .pct-bad  { color: #dc2626; font-weight: 800; }

        /* SUMMARY ROW */
        .summary-row td {
            background: #f0fdf4;
            font-weight: 700;
            color: #065f46;
            border-top: 2px solid #10b981;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .footer strong { color: #6366f1; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Student Attendance Report</h1>

        <div class="meta-row">
            <div class="meta-item">
                <span class="meta-label">Department</span>
                <strong>{{ $department ?? 'N/A' }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">Subject</span>
                <strong>{{ $subject->name ?? 'N/A' }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">Semester</span>
                <strong>{{ $semester ?? 'N/A' }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">Session</span>
                <strong>{{ $session ?? 'N/A' }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">Duration</span>
                <strong>{{ $start_date }} &mdash; {{ $end_date }}</strong>
            </div>
            <div class="meta-item">
                <span class="meta-label">Total Students</span>
                <strong>{{ count($students) }}</strong>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Roll No</th>
                <th style="text-align:left;">Student Name</th>
                <th>Total</th>
                <th>Present</th>
                <th>Late</th>
                <th>Leave</th>
                <th>Absent</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $s->roll_number }}</strong></td>
                <td class="name-col">{{ $s->student_name }}</td>
                <td>{{ $s->total_classes }}</td>
                <td><span class="badge badge-green">{{ $s->present_count }}</span></td>
                <td><span class="badge badge-yellow">{{ $s->late_count }}</span></td>
                <td><span class="badge badge-orange">{{ $s->leave_count ?? 0 }}</span></td>
                <td><span class="badge badge-red">{{ $s->absent_count }}</span></td>
                <td>
                    @if($s->percentage >= 75)
                        <span class="pct-good">{{ $s->percentage }}%</span>
                    @else
                        <span class="pct-bad">{{ $s->percentage }}%</span>
                    @endif
                </td>
            </tr>
            @endforeach

            @php
                $totalPresent = collect($students)->sum('present_count');
                $totalAbsent  = collect($students)->sum('absent_count');
                $totalLate    = collect($students)->sum('late_count');
                $totalLeave   = collect($students)->sum('leave_count');
                $totalClasses = collect($students)->sum('total_classes');
            @endphp
            <tr class="summary-row">
                <td colspan="3" style="text-align:left;font-weight:800;">TOTAL</td>
                <td>{{ $totalClasses }}</td>
                <td>{{ $totalPresent }}</td>
                <td>{{ $totalLate }}</td>
                <td>{{ $totalLeave }}</td>
                <td>{{ $totalAbsent }}</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Generated by <strong>AttendTrack</strong> &mdash; {{ date('d M Y, h:i A') }}
    </div>

</body>
</html>