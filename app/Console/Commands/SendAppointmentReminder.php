<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookAppointment;
use App\Models\User;
use App\Models\PushNotification;
use App\Helpers\FirbasePushHelper;
use Carbon\Carbon;

class SendAppointmentReminder extends Command
{
    protected $signature = 'appointment:reminder';
    protected $description = 'Notify customer and doctor 30 minutes before an upcoming appointment';

    public function handle()
    {
        $now = Carbon::now();

        $windowStart = $now->copy()->addMinutes(25);
        $windowEnd   = $now->copy()->addMinutes(35);

        $appointments = BookAppointment::where('status', 'upcoming')
            ->where('reminder_sent', false)
            ->whereRaw("TIMESTAMP(booking_date, booking_time) BETWEEN ? AND ?", [
                $windowStart->format('Y-m-d H:i:s'),
                $windowEnd->format('Y-m-d H:i:s'),
            ])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments to remind.');
            return;
        }

        $userIds = $appointments->pluck('user_id')
            ->merge($appointments->pluck('doctor_id'))
            ->unique();
        $fcmTokens = User::whereIn('id', $userIds)->pluck('fcm_token', 'id');

        $notifications = [];
        $firebase_push = new FirbasePushHelper;

        foreach ($appointments as $appointment) {
            $timeLabel = Carbon::parse($appointment->booking_time)->format('g:i A');

            // Customer message
            $customerTitle = "Upcoming Appointment Reminder";
            $customerDesc = "Your appointment #{$appointment->appointment_no} starts at {$timeLabel} today. Please be on time.";

            $notifications[] = [
                'user_id' => $appointment->user_id,
                'title' => $customerTitle,
                'description' => $customerDesc,
                'data' => json_encode([
                    'type' => 'appointment_reminder',
                    'appointment_id' => $appointment->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $customerToken = $fcmTokens[$appointment->user_id] ?? null;
            if ($customerToken) {
                $firebase_push->sendFribasePushNotification([$customerToken], [
                    'title' => $customerTitle,
                    'message' => $customerDesc,
                    'type' => 'appointment_reminder',
                ]);
            }

            // Doctor message
            $doctorTitle = "Upcoming Appointment Reminder";
            $doctorDesc = "You have an appointment #{$appointment->appointment_no} starting at {$timeLabel} today.";

            $notifications[] = [
                'user_id' => $appointment->doctor_id,
                'title' => $doctorTitle,
                'description' => $doctorDesc,
                'data' => json_encode([
                    'type' => 'appointment_reminder',
                    'appointment_id' => $appointment->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $doctorToken = $fcmTokens[$appointment->doctor_id] ?? null;
            if ($doctorToken) {
                $firebase_push->sendFribasePushNotification([$doctorToken], [
                    'title' => $doctorTitle,
                    'message' => $doctorDesc,
                    'type' => 'appointment_reminder',
                ]);
            }

            $appointment->reminder_sent = true;
            $appointment->save();
        }

        PushNotification::insert($notifications);
        $this->info(count($appointments) . ' appointment reminders sent.');
    }
}
