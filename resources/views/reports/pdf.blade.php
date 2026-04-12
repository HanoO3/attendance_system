<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 11px; 
            margin: 0;
            padding: 20px;
        }
        
        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2d3748;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #718096;
            font-size: 12px;
        }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        
        th, td { 
            border: 1px solid #e2e8f0; 
            padding: 8px; 
            text-align: center; 
        }
        
        th { 
            background-color: #2d3748; 
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f7fafc;
        }

        /* Badge Styling */
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        .bg-success { background-color: #48bb78; color: #fff; }
        .bg-warning { background-color: #FFC107; color: #000; } 
        .bg-danger { background-color: #f56565; color: #fff; }
        .bg-orange { background-color: #fd7e14; color: #fff; }

        /* Percentage Text Colors */
        .text-success { color: #48bb78; font-weight: bold; }
        .text-danger { color: #f56565; font-weight: bold; }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Students Attendance Report</h1>
        <p>
            <!-- FIX: $department is already a string name, so use directly -->
            Department: <b>{{ $department ?? 'N/A' }}</b>
            
            <!-- Subject Name Added -->
            | Subject: <b>{{ $subject->name ?? 'N/A' }}</b>

            @if($session)
                | Session: <b>{{ $session }}</b>
            @endif

            @if($semester)
                | Semester: <b>{{ $semester }}</b>
            @endif
            
            <br>
            Duration: <b>{{ $start_date }}</b> to <b>{{ $end_date }}</b>
        </p>
    </div>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Roll No</th>
                <th>Name</th>
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
                <td>{{ $s->roll_number }}</td>
                <td style="text-align: left;">{{ $s->student_name }}</td>
                <td>{{ $s->total_classes }}</td>
                <td><span class="badge bg-success">{{ $s->present_count }}</span></td>
                <td><span class="badge bg-warning">{{ $s->late_count }}</span></td>
                <td><span class="badge bg-orange">{{ $s->leave_count ?? 0 }}</span></td>
                <td><span class="badge bg-danger">{{ $s->absent_count }}</span></td>
                <td>
                    @if($s->percentage >= 75)
                        <span class="text-success">{{ $s->percentage }}%</span>
                    @else
                        <span class="text-danger">{{ $s->percentage }}%</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        Generated on: {{ date('d M Y - h:i A') }}
    </div>

</body>
</html>