<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CinéHall Ticket - {{ $ticket_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .ticket {
            border: 2px solid #000;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .movie-info {
            margin-bottom: 20px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .details {
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>CinéHall</h1>
            <h2>Electronic Ticket</h2>
            <p>Ticket Number: {{ $ticket_number }}</p>
        </div>

        <div class="movie-info">
            <h3>{{ $movie->title }}</h3>
            <p><strong>Date & Time:</strong> {{ $screening->start_time->format('F j, Y g:i A') }}</p>
            <p><strong>Hall:</strong> {{ $seat->hall->name }}</p>
            <p><strong>Seat:</strong> {{ $seat->row }}{{ $seat->number }}</p>
        </div>

        <div class="qr-code">
            {!! $qr_code !!}
        </div>

        <div class="details">
            <p><strong>Customer:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div class="footer">
            <p>Please present this ticket at the entrance</p>
            <p>© {{ date('Y') }} CinéHall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 