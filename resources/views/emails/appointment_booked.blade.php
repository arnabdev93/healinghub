<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { background: #4CAF50; color: white; padding: 15px; border-radius: 6px 6px 0 0; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; }
        .body { padding: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .label { color: #666; font-size: 14px; }
        .value { font-weight: bold; font-size: 14px; color: #333; }
        .meet-btn-wrap { text-align: center; margin-top: 25px; }
        .meet-btn { display: inline-block; padding: 12px 28px; background: #1a73e8; color: white; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: bold; }
        .footer { margin-top: 25px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h2>Appointment Confirmed</h2>
    </div>

    <div class="body">
        <p>Hello <strong>{{ $patient->name }}</strong>,</p>
        <p>Your appointment has been successfully booked. Details are below:</p>

        <div class="detail-row">
            <span class="label">Appointment No</span>
            <span class="value">{{ $appointment->appointment_no }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Doctor</span>
            <span class="value">Dr. {{ $doctor->name }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Date</span>
            <span class="value">{{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Time</span>
            <span class="value">{{ \Carbon\Carbon::createFromFormat('H:i', $appointment->booking_time)->format('h:i A') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Type</span>
            <span class="value">{{ ucfirst($appointment->appointment_type) }} Consultation</span>
        </div>
        <div class="detail-row">
            <span class="label">Amount</span>
            <span class="value">₹{{ $appointment->amount }}</span>
        </div>

        @if($meetingLink)
        <div class="meet-btn-wrap">
            <a href="{{ $meetingLink }}" class="meet-btn">Join Google Meet</a>
        </div>
        @endif
    </div>

    <div class="footer">
        Please join the meeting on time. This is an automated email, please do not reply.
    </div>

</div>
</body>
</html>
