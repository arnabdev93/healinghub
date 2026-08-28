<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

use App\Helpers\CustomHelper;
use App\Models\AppointmentStatusLog;
use App\Models\BookAppointment;
use App\Models\BookingSlot;
use App\Models\UserDetail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserReview;
use App\Models\PaymentMethod;
use App\Models\Prescription;
use App\Models\DoctorGoogleToken;
use App\Models\PriceQuote;
use App\Models\User;
use App\Models\PushNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleMeetService;
use App\Services\MeetingService;
use Razorpay\Api\Api;
use App\Models\Setting;
use App\Models\DoctorSettlement;


class ProfileController extends BaseController
{
    public function notificationLists()
    {
        $user_id = Auth::id();
        $notifications = PushNotification::select('id','title','description','created_at')->where('user_id',$user_id)->orderBy('id','DESC')->paginate(40);

        return $this->sendResponse(['notifications'=>$notifications],"Successful");
    }
    public function myProfile()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $details = UserDetail::with(['category'=>function($qry){
                                $qry->select('id','name');
                            }])->where('user_id',$user_id)->first();
        $categories = [];
        if($user->role=='doctor'){
            $categories = Category::select('id','name')->whereIn('id',[1,2])->get();
        }

        if ($details && $details->image) {
            $details->image = asset('storage/' . $details->image);
        }

        $result = [
            'user' => $user,
            'details' => $details,
            'categories' => $categories
        ];
        return $this->sendResponse($result, 'Successfull');
    }

    public function profileUpdate(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $role = $user->role;
        if($role=='customer'){
            $rules = [
                'name' => 'required|min:3',
                'gender' => 'required|in:Male,Female',
                'age' => 'required|numeric|min:0.1|max:150',
                'weight' => 'required|numeric|min:1|max:150',
                'heart_rate' => 'nullable',
                'bp' => 'nullable',
                'calories' => 'nullable',
                'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif'
            ];
        }else{
            $rules = [
                'name' => 'required|min:3',
                'category_id' => 'required|in:1,2',
                'specialist' => 'nullable|string',
                'bank_name' => 'nullable|required_with:bank_ifsc_code,bank_acc_no',
                'bank_acc_no' => 'nullable|required_with:bank_name,bank_ifsc_code',
                'bank_ifsc_code' => 'nullable|required_with:bank_name,bank_acc_no',
                'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif'
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user->name = $request->name;
            $user->save();

            $details = UserDetail::where('user_id',$user_id)->first();
            if($role=='doctor'){
                $details->category_id = $request->category_id;
                $details->specialist = $request->specialist;
                $details->bank_name = $request->bank_name;
                $details->bank_acc_no = $request->bank_acc_no;
                $details->bank_ifsc_code = $request->bank_ifsc_code;
                $details->upi = $request->upi;
                $details->about = $request->about;
                if($request->hasFile('image')){
                    if($details->image){
                        CustomHelper::removeExistingFileFromStorage($details->image);//This is a helper function
                    }
                    $details->image = $request->image->store('user','public');
                }
            }else{
                $details->gender = $request->gender;
                $details->age = $request->age;
                $details->weight = $request->weight;
                $details->heart_rate = $request->heart_rate;
                $details->bp = $request->bp;
                $details->calories = $request->calories;
                if($request->hasFile('image')){
                    if($details->image){
                        CustomHelper::removeExistingFileFromStorage($details->image);//This is a helper function
                    }
                    $details->image = $request->image->store('user','public');
                }
            }
            $details->save();

            return $this->sendResponse([], "Successfull");
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }

    //------------------- Coustomer part------------------------------//

    public function getDoctors($category_id)
    {
        $validator = Validator::make(
            ['category_id' => $category_id],
            ['category_id' => 'required|exists:categories,id']
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctors = User::where('role', 'doctor')
                ->whereHas('details', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->with([
                    'details:id,user_id,specialist,consult_fee_phone,consult_fee_vdo,image',
                    'bookingSlots:id,user_id,weekday'
                ])
                ->get(['id', 'name']);
        $doctorIds = $doctors->pluck('id');
        $ratings = UserReview::whereIn('receiver_id', $doctorIds)
        ->selectRaw('receiver_id, AVG(rating) as avg_rating, COUNT(*) as review_count')
        ->groupBy('receiver_id')
        ->get()
        ->keyBy('receiver_id');

        $formattedDoctors = $doctors->map(function ($doctor) use ($ratings) {

        $rating = $ratings->get($doctor->id);

            return [
                'doctor_id' => $doctor->details->user_id,
                'doctor_name' => $doctor->name,
                'specialist' => $doctor->details->specialist ?? null,
                'doctor_image' => asset('storage/'.$doctor->details->image ?? null),
                'consult_fee_phone' => $doctor->details->consult_fee_phone ?? null,
                'consult_fee_video' => $doctor->details->consult_fee_vdo ?? null,
                'available_weekdays' => $doctor->bookingSlots
                    ->pluck('weekday')
                    ->unique()
                    ->values(),
                'average_rating' => $rating ? round($rating->avg_rating, 1) : 0,
                'review_count' => $rating ? $rating->review_count : 0,
            ];
        });

        $doctors_list = [
            "doctors" => $formattedDoctors
        ];

        return $this->sendResponse((object)$doctors_list, "Successfull");
    }

    public function showDoctor($doctor_id)
    {
        $validator = Validator::make(
            ['doctor_id' => $doctor_id],
            ['doctor_id' => 'required|exists:users,id']
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = User::where('role', 'doctor')
            ->where('id', $doctor_id)
            ->with(['details:id,user_id,specialist,about,image,consult_fee_phone,consult_fee_vdo'])
            ->first(['id', 'name', 'email', 'mobile']);

        if (!$doctor) {
            return $this->sendError('Doctor not found');
        }
        $ratingData = UserReview::where('receiver_id', $doctor->id)
        ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
        ->first();

        $response = [
            'id' => $doctor->details->user_id,
            'name' => $doctor->name,
            'email' => $doctor->email,
            'mobile' => $doctor->mobile,
            'audio_call_fee' => $doctor->details->consult_fee_phone,
            'video_call_fee' => $doctor->details->consult_fee_vdo,
            'image' => asset('storage/'.$doctor->details->image ?? null),
            'speciality' => $doctor->details->specialist ?? null,
            'about' => $doctor->details->about ?? null,
            'average_rating' => $ratingData && $ratingData->review_count > 0 ? round($ratingData->avg_rating, 1) : 0,
            'review_count' => $ratingData ? $ratingData->review_count : 0,
        ];

        return $this->sendResponse($response, "Successful");
    }



    // public function bookAppoinment(Request $request, GoogleMeetService $googleMeetService)
    // {
    //     $rules = [
    //         'doctor_id' => 'required|exists:users,id',
    //         'booking_date' => 'required|date|after_or_equal:today',
    //         'booking_time' => 'required|date_format:H:i',
    //         'appointment_type' => 'required|in:audio,video',
    //         'notes' => 'nullable|string|max:1000'
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first());
    //     }

    //     $doctor = User::where('id', $request->doctor_id)
    //         ->where('role', 'doctor')
    //         ->with('details')
    //         ->first();

    //     if (!$doctor) {
    //         return $this->sendError('Invalid doctor selected');
    //     }

    //     $weekday = Carbon::parse($request->booking_date)->format('l');

    //     $slot = BookingSlot::where('user_id', $doctor->id)
    //         ->where('weekday', $weekday)
    //         ->first();

    //     if (!$slot) {
    //         return $this->sendError("Doctor is not available on {$weekday}");
    //     }

    //     if (!in_array($request->booking_time, $slot->times)) {
    //         return $this->sendError("Selected time is not available");
    //     }

    //     $userAlreadyBooked = BookAppointment::where('user_id', Auth::id())
    //         ->where('doctor_id', $doctor->id)
    //         ->where('booking_date', $request->booking_date)
    //         ->where('booking_time', $request->booking_time)
    //         ->where('status', '!=', 'cancelled')
    //         ->exists();

    //     if ($userAlreadyBooked) {
    //         return $this->sendError("You have already booked this time slot.");
    //     }

    //     $alreadyBooked = BookAppointment::where('doctor_id', $doctor->id)
    //         ->where('booking_date', $request->booking_date)
    //         ->where('booking_time', $request->booking_time)
    //         ->where('status', '!=', 'cancelled')
    //         ->exists();

    //     if ($alreadyBooked) {
    //         return $this->sendError("This time slot is already booked");
    //     }

    //     if (!$doctor->details) {
    //         return $this->sendError("Doctor pricing not set");
    //     }

    //     $amount = $request->appointment_type === 'audio'
    //         ? $doctor->details->consult_fee_phone
    //         : $doctor->details->consult_fee_vdo;

    //     if (!$amount) {
    //         return $this->sendError("Consultation fee not available");
    //     }
    //     //Google meet link gnerate
    //     $meetingLink = null;

    //     if ($request->appointment_type === 'video') {

    //         $tokenPath = storage_path('app/google-calendar-token.json');

    //         if (!file_exists($tokenPath)) {
    //             return $this->sendError('Google Meet not configured yet.');
    //         }

    //         $meetingLink = $googleMeetService->createMeeting(
    //             $request->booking_date,
    //             $request->booking_time
    //         );
    //     }

    //     // $meetingLink = null;

    //     // if ($request->appointment_type === 'video') {
    //     //     $tokenExists =DoctorGoogleToken::where('doctor_id', $doctor->id)->exists();

    //     //     if (!$tokenExists) {
    //     //         return $this->sendError('This doctor has not connected their Google account yet.');
    //     //     }

    //     //     $meetingLink = $googleMeetService->createMeeting(
    //     //         $request->booking_date,
    //     //         $request->booking_time,
    //     //         $doctor->id
    //     //     );

    //     //     if (!$meetingLink) {
    //     //         return $this->sendError('Failed to create meeting. Please try again.');
    //     //     }
    //     // }

    //     $appointment = BookAppointment::create([
    //         'user_id' => Auth::id(),
    //         'appointment_no' => BookAppointment::generateAppointmentNo(),
    //         'doctor_id' => $doctor->id,
    //         'booking_date' => $request->booking_date,
    //         'weekday' => $weekday,
    //         'booking_time' => $request->booking_time,
    //         'appointment_type' => $request->appointment_type,
    //         'meeting_link' => $meetingLink,
    //         'amount' => $amount,
    //         'notes' => $request->notes,
    //         'status' => 'upcoming'
    //     ]);

    //     //payment method starts



    //     //payment method ends

    //     AppointmentStatusLog::create([
    //         'appointment_id'   => $appointment->id,
    //         'changed_by'       => Auth::id(),
    //         'note'             => 'new appoiment book by customer',
    //         'new_status'       => $appointment->status,
    //         'changed_at'       => now(),
    //     ]);

    //     return $this->sendResponse([], "Appointment booked successfully");
    // }
    public function createAppointmentRazorpayOrder(Request $request)
    {
        $rules = [
            'doctor_id' => 'required|exists:users,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|in:audio,video',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = User::where('id', $request->doctor_id)->where('role', 'doctor')->with('details')->first();
        if (!$doctor) {
            return $this->sendError('Invalid doctor selected');
        }

        $weekday = Carbon::parse($request->booking_date)->format('l');
        $slot = BookingSlot::where('user_id', $doctor->id)->where('weekday', $weekday)->first();
        if (!$slot) {
            return $this->sendError("Doctor is not available on {$weekday}");
        }
        if (!in_array($request->booking_time, $slot->times)) {
            return $this->sendError("Selected time is not available");
        }

        $alreadyBooked = BookAppointment::where('doctor_id', $doctor->id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('status', '!=', 'cancelled')
            ->exists();
        if ($alreadyBooked) {
            return $this->sendError("This time slot is already booked");
        }

        if (!$doctor->details) {
            return $this->sendError("Doctor pricing not set");
        }
        $amount = $request->appointment_type === 'audio'
            ? $doctor->details->consult_fee_phone
            : $doctor->details->consult_fee_vdo;
        if (!$amount) {
            return $this->sendError("Consultation fee not available");
        }

        try {
            $pay_amount = round($amount * 100);

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $razorpay_order = $api->order->create([
                'receipt'  => 'apmt_rcpt_'.Auth::id().'_'.time(),
                'amount'   => $pay_amount,
                'currency' => 'INR',
                'notes'    => [
                    'type'             => 'appointment',
                    'user_id'          => Auth::id(),
                    'doctor_id'        => $request->doctor_id,
                    'booking_date'     => $request->booking_date,
                    'booking_time'     => $request->booking_time,
                    'appointment_type' => $request->appointment_type,
                    'notes_text'       => $request->notes ?? '',
                ],
            ]);

            return $this->sendResponse([
                'razorpay_order_id' => $razorpay_order['id'],
                'razorpay_key'      => config('services.razorpay.key'),
                'amount'            => $pay_amount,
            ], 'Razorpay order created');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function bookAppoinment(Request $request, GoogleMeetService $googleMeetService)
    {
        $rules = [
            'doctor_id' => 'required|exists:users,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|in:audio,video',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required',
            'razorpay_payment_id' => 'required_if:payment_method,upi',
            'razorpay_order_id' => 'required_if:payment_method,upi',
            'razorpay_signature' => 'required_if:payment_method,upi',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = User::where('id', $request->doctor_id)
            ->where('role', 'doctor')
            ->with('details')
            ->first();

        if (!$doctor) {
            return $this->sendError('Invalid doctor selected');
        }

        $weekday = Carbon::parse($request->booking_date)->format('l');

        $slot = BookingSlot::where('user_id', $doctor->id)
            ->where('weekday', $weekday)
            ->first();

        if (!$slot) {
            return $this->sendError("Doctor is not available on {$weekday}");
        }

        if (!in_array($request->booking_time, $slot->times)) {
            return $this->sendError("Selected time is not available");
        }

        $userAlreadyBooked = BookAppointment::where('user_id', Auth::id())
            ->where('doctor_id', $doctor->id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($userAlreadyBooked) {
            return $this->sendError("You have already booked this time slot.");
        }

        $alreadyBooked = BookAppointment::where('doctor_id', $doctor->id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($alreadyBooked) {
            return $this->sendError("This time slot is already booked");
        }

        if (!$doctor->details) {
            return $this->sendError("Doctor pricing not set");
        }

        $amount = $request->appointment_type === 'audio'
            ? $doctor->details->consult_fee_phone
            : $doctor->details->consult_fee_vdo;

        if (!$amount) {
            return $this->sendError("Consultation fee not available");
        }

        //Google meet link gnerate
        $meetingLink = null;

        if ($request->appointment_type === 'video') {

            $tokenPath = storage_path('app/google-calendar-token.json');

            if (!file_exists($tokenPath)) {
                return $this->sendError('Google Meet not configured yet.');
            }

            $meetingLink = $googleMeetService->createMeeting(
                $request->booking_date,
                $request->booking_time
            );
        }



        //payment method starts
        if($request->payment_method == 'upi'){
            try {
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id'   => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature,
                ]);
            } catch (\Exception $e) {
                return $this->sendError("Payment verification failed");
            }
        }
        //payment method ends

        DB::beginTransaction();
        try {
            $appointment = BookAppointment::create([
                'user_id' => Auth::id(),
                'appointment_no' => BookAppointment::generateAppointmentNo(),
                'doctor_id' => $doctor->id,
                'booking_date' => $request->booking_date,
                'weekday' => $weekday,
                'booking_time' => $request->booking_time,
                'appointment_type' => $request->appointment_type,
                'meeting_link' => $meetingLink,
                'amount' => $amount,
                'notes' => $request->notes,
                'status' => 'upcoming'
            ]);

            $payment_method_id = PaymentMethod::where('slug', $request->payment_method)->value('id');

            Payment::create([
                'appointment_id'    => $appointment->id,
                'user_id'           => Auth::id(),
                'doctor_id'         => $doctor->id,
                'payment_method_id' => $payment_method_id,
                'transaction_id'    => $request->razorpay_payment_id,
                'amount'            => $appointment->amount,
                'status'            => $request->payment_method == 'upi' ? 'paid' : 'pending',
                'paid_at'           => $request->payment_method == 'upi' ? now() : null,
            ]);

            AppointmentStatusLog::create([
                'appointment_id'   => $appointment->id,
                'changed_by'       => Auth::id(),
                'note'             => 'new appoiment book by customer',
                'new_status'       => $appointment->status,
                'changed_at'       => now(),
            ]);

            DB::commit();
            return $this->sendResponse([], "Appointment booked successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
    public function submitReview(Request $request)
    {
        $rules = [
            'appointment_id' => 'required|exists:book_appointments,id',
            'rating' => 'required|numeric|min:1|max:5',
            // 'comment' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $customer = Auth::user();

        $appointment = BookAppointment::where('id', $request->appointment_id)
            ->where('user_id', $customer->id)
            ->first();

        if (!$appointment) {
            return $this->sendError('Appointment not found or access denied.');
        }

        if ($appointment->status !== 'completed') {
            return $this->sendError('You can only review after the appointment is completed.');
        }

        $alreadyReviewed = UserReview::where('appointment_id', $appointment->id)
            ->where('sender_id', $customer->id)
            ->exists();

        if ($alreadyReviewed) {
            return $this->sendError('You have already submitted a review for this appointment.');
        }
        $user_review = new UserReview;
        $user_review->appointment_id = $appointment->id;
        $user_review->sender_id = $customer->id;
        $user_review->receiver_id = $appointment->doctor_id;
        $user_review->rating = $request->rating;
        // $user_review->comment = $request->comment;
        $user_review->save();

        return $this->sendResponse([], 'Review submitted successfully.');
    }



    public function getAvalableSlots(Request $request)
    {
        $rules = [
            'doctor_id' => 'required|exists:users,id',
            'day' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = User::where('id', $request->doctor_id)
            ->where('role', 'doctor')
            ->first();

        if (!$doctor) {
            return $this->sendError('Invalid doctor');
        }

        $input = $request->day;
        $weekday = null;
        $date = null;

        if (strtotime($input)) {
            try {
                $date = Carbon::parse($input)->format('Y-m-d');
                $weekday = Carbon::parse($input)->format('l');
            } catch (\Exception $e) {
                return $this->sendError('Invalid date format');
            }
        } else {
            $weekday = ucfirst(strtolower($input));
        }

        $slotQuery = BookingSlot::where('user_id', $doctor->id);

        $slotQuery->where('weekday', $weekday);

        $slot = $slotQuery->first();

        if (!$slot) {
            return $this->sendError('No slots available for selected day');
        }

        $bookedTimes = BookAppointment::where('doctor_id', $doctor->id)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('booking_time')
            ->map(function ($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();
        echo "<pre>";
        print_r($bookedTimes);
        exit;

        $timesWithStatus = collect($slot->times)->map(function ($time) use ($bookedTimes) {
            $formattedTime = \Carbon\Carbon::parse($time)->format('H:i');
            return [
                'time' => $formattedTime,
                'is_available' => !in_array($time, $bookedTimes)
            ];
        });

        return $this->sendResponse([
            'date' => $date,
            'slots' => $timesWithStatus
        ],  "Available slots fetched successfully");
    }

    public function getMyBookingsDetails(Request $request)
    {
        $rules =[
            'booking_id' => 'required|integer|exists:book_appointments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();

        $booking = BookAppointment::where('id', $request->query('booking_id')) // changed
            ->where('user_id', $user->id)
            ->with([
                'doctor:id,name',
                'doctor.details:user_id,specialist,image'
            ])
            ->first();

        if (!$booking) {
            return $this->sendError("Appointment not found");
        }

        if ($booking->doctor && $booking->doctor->details) {
            $booking->doctor->details->makeHidden('image');
        }

        return $this->sendResponse($booking, "Appointment details retrieved successfully.");
    }

    public function cancelAppointment(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();
        $user_id = $user->id;

        $booking = BookAppointment::select('id','status','booking_date','booking_time')->where('id', $request->booking_id)
                        ->where(function($query) use($user_id) {
                            $query->where('user_id',$user_id)
                                ->orWhere('doctor_id',$user_id);
                        })->first();
        if (!$booking) {
            return $this->sendError("Appointment not found");
        }


        $booking_time = $booking->booking_date . ' ' . $booking->booking_time;

        if($user->role=='customer'){
            if (now()->diffInHours($booking_time, false) < 2) {
                return $this->sendError("Cancellations are only allowed 2 hours before the start time.");
            }
        }
        $booking->status = 'cancelled';
        $booking->save();

        AppointmentStatusLog::create([
            'appointment_id'   => $booking->id,
            'changed_by'       => $user_id,
            'note'             => 'Booking Cancelled',
            'new_status'       => 'cancelled',
            'changed_at'       => now(),
        ]);

        return $this->sendResponse([], "Appointment cancelled successfully.");
    }

    public function requestForPrescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'appointment_id' => 'required|exists:book_appointments,id',
            'notes' => 'nullable|string',
            'images' => 'required|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();

        if ($user->role !== 'customer') {
            return $this->sendError("Only customers can submit prescription.");
        }

        DB::beginTransaction();

        try {

            $order = Order::create([
                'orderno' => 'HBC-'.time(),
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'appointment_id' => $request->appointment_id,
                'type' => 'prescription',
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            foreach ($request->file('images') as $image) {

                $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $path = $image->storeAs('prescriptions',$filename,'public');

                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => 'Prescription',
                    'image' => $path
                ]);
            }

            DB::commit();

            return $this->sendResponse($order,'Prescription submitted successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }

    // public function getMyPrescriptions(Request $request)
    // {
    //     $user = Auth::user();

    //     $prescriptions = Prescription::with([
    //             'doctor:id,name',
    //             'customer:id,name'
    //         ])
    //         ->where('customer_id', $user->id)
    //         ->latest()
    //         ->get();

    //     if (!$prescriptions) {
    //         return $this->sendError("No prescription found.");
    //     }

    //     $data = $prescriptions->map(function ($item) use ($user) {

    //         $uploadedBy = ($item->customer_id == $user->id)
    //             ? $item->customer->name
    //             : $item->doctor->name;

    //         $uploadedType = ($item->customer_id == $user->id)
    //             ? 'customer'
    //             : 'doctor';

    //         return [
    //             'prescription_id' => $item->id,
    //             'image'           => asset('storage/'.$item->image),
    //             'notes'           => $item->notes,

    //             'doctor_name'     => $item->doctor->name ?? '',
    //             'customer_name'   => $item->customer->name ?? '',

    //             'uploaded_by'     => $uploadedBy,
    //             'uploaded_type'   => $uploadedType,

    //             'created_at'      => $item->created_at,
    //             'updated_at'      => $item->updated_at,
    //         ];
    //     });

    //     return $this->sendResponse($data,'Prescription list fetched successfully');
    // }

    public function getMyPrescriptions(Request $request)
    {
        $user = Auth::user();

        $prescriptions = Prescription::with([
                'doctor:id,name',
                'customer:id,name'
            ])
            ->where('customer_id', $user->id)
            ->get();

        $prescriptionData = $prescriptions->map(function ($item) use ($user) {

            $uploadedBy = ($item->customer_id == $user->id)
                ? $item->customer->name
                : $item->doctor->name;

            $uploadedType = ($item->customer_id == $user->id)
                ? 'customer'
                : 'doctor';

            return [
                'type'            => 'prescription',
                'id'              => $item->id,
                'image'           => $item->image ? asset('storage/'.$item->image) : null,
                'notes'           => $item->notes,

                'doctor_name'     => $item->doctor->name ?? '',
                'customer_name'   => $item->customer->name ?? '',

                'uploaded_by'     => $uploadedBy,
                'uploaded_type'   => $uploadedType,

                'created_at'      => $item->created_at,
            ];
        });

        // $orders = Order::with(['items'])
        //     ->where('user_id', $user->id)
        //     ->latest()
        //     ->get();

        // $orderData = $orders->flatMap(function ($order) {

        //     return $order->items->map(function ($item) use ($order) {
        //         return [
        //             'type'        => 'order_item',
        //             'id'          => $item->id,
        //             'name'        => $item->name,
        //             'image'       => $item->image_path,
        //             'order_id'    => $order->id,
        //             'notes'       => $order->notes ?? null,
        //             'created_at'  => $order->created_at,
        //         ];
        //     });
        // });

        // $finalData = $prescriptionData
        //     ->concat($orderData)
        //     ->sortByDesc('created_at')
        //     ->values();

        if ($prescriptionData->isEmpty()) {
            return $this->sendError("No data found.");
        }

        return $this->sendResponse(
            $prescriptionData,
            'Data fetched successfully'
        );
    }

    //-----------------------Coustomer part--------------------------//

    public function getAppointmentsList(Request $request)
    {
        $rules = [
            'status' => 'required|in:upcoming,completed,cancelled'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();
        $query = BookAppointment::where('status', $request->query('status'))
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc');

        if ($user->role === 'customer') {

            $query->where('user_id', $user->id)
                ->with([
                    'doctor:id,name',
                    'doctor.details:user_id,specialist,image'
                ]);

            $bookings = $query->get([
                'id',
                'doctor_id',
                'booking_date',
                'weekday',
                'booking_time',
                'appointment_type',
                'amount',
                'status',
                'meeting_link',
            ]);

            $data = $bookings->map(function ($booking) {
                if ($booking->doctor && $booking->doctor->details) {
                    $booking->doctor->details->makeHidden(['image', 'user_id']);
                }

                return [
                    'appointment_id' => $booking->id,
                    'doctor_id' => $booking->doctor_id,
                    'doctor_name' => $booking->doctor->name ?? null,
                    'doctor_image' => $booking->doctor->details->image_path ?? null,
                    'specialist' => $booking->doctor->details->specialist ?? null,
                    'booking_date' => $booking->booking_date,
                    'weekday' => $booking->weekday,
                    'slot_time' => Carbon::parse($booking->booking_time)->format('H:i'),
                    'appointment_type' => $booking->appointment_type,
                    'amount' => $booking->amount,
                    'status' => $booking->status,
                    'meeting_link' => $booking->meeting_link,
                ];
            });

        } elseif ($user->role === 'doctor') {

            $query->where('doctor_id', $user->id)
                ->with(['customer:id,name',
                'customer.details:user_id,image']);

            $bookings = $query->get([
                'id',
                'user_id',
                'booking_date',
                'weekday',
                'booking_time',
                'appointment_type',
                'notes',
                'amount',
                'status',
                'meeting_link',
            ]);

            $data = $bookings->map(function ($booking) {
                return [
                    'appointment_id' => $booking->id,
                    'customer_id' => $booking->customer->id ?? null,
                    'customer_name' => $booking->customer->name ?? null,
                    'customer_image'=>$booking->customer->details->image_path ?? null,
                    'booking_date' => $booking->booking_date,
                    'weekday' => $booking->weekday,
                    'slot_time' => Carbon::parse($booking->booking_time)->format('H:i'),
                    'appointment_type' => $booking->appointment_type,
                    'notes' => $booking->notes,
                    'amount' => $booking->amount,
                    'status' => $booking->status,
                    'meeting_link' => $booking->meeting_link,
                ];
            });

        } else {
            return $this->sendError('Unauthorized role.');
        }

        return $this->sendResponse([
            'appointments' => $data
        ], "Appointment list fetched successfully");
    }

    public function getAppointmentDetails(Request $request)
    {
        $rules = [
            'appointment_id' => 'required|exists:book_appointments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();

        $query = BookAppointment::where('id', $request->query('appointment_id'))
            ->with([
                'doctor:id,name',
                'doctor.details:user_id,specialist,image',
                'customer:id,name',
                'customer.details:user_id,image',
                'prescriptions'
            ]);

        // Role-based filter
        if ($user->role === 'customer') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        } else {
            return $this->sendError('Unauthorized role.');
        }

        $appointment = $query->first();

        if (!$appointment) {
            return $this->sendError('Appointment not found or access denied.');
        }

        //  Base response
        $response = [
            'appointment_id' => $appointment->id,
            'booking_date' => $appointment->booking_date,
            'weekday' => $appointment->weekday,
            'slot_time' => Carbon::parse($appointment->booking_time)->format('H:i'),
            'appointment_type' => $appointment->appointment_type,
            'notes' => $appointment->notes,
            'amount' => $appointment->amount,
            'status' => $appointment->status,
            'meeting_link'     => $appointment->meeting_link,
        ];

        $response['doctor'] = [
            'id' => $appointment->doctor->id ?? null,
            'name' => $appointment->doctor->name ?? null,
            'specialist' => $appointment->doctor->details->specialist ?? null,
            'image' => $appointment->doctor->details->image_path ?? null, // from accessor
        ];

        $response['customer'] = [
            'id' => $appointment->customer->id ?? null,
            'name' => $appointment->customer->name ?? null,
            'image' => $appointment->customer->details->image_path ?? null, // from accessor
        ];

        $response['prescription_notes'] = null;
        $response['prescription_images'] = [];

        if ($appointment->status === "completed" && $appointment->prescriptions->count() > 0) {

            $response['prescription_notes'] = $appointment->prescriptions->first()->notes ?? null;

            $response['prescription_images'] = $appointment->prescriptions->map(function ($p) {
                return [
                    'image_url' => $p->image ? asset('storage/' . $p->image) : null
                ];
            });
        }

        return $this->sendResponse($response, "Appointment details fetched successfully");
    }

    //-----------------------Doctor part--------------------------//

    public function createBookingSlot(Request $request)
    {
        $rules = [
            'weekday' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'slot_duration' => 'required|integer|min:1',
            'times' => 'required|array|min:1',
            'times.*' => 'required|date_format:H:i',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return $this->sendError('Only doctors can create booking slots.');
        }

        $existingSlot = $user->bookingSlots()->where('weekday', $request->weekday)->first();

        if ($existingSlot) {
            $mergedTimes = array_unique(array_merge(
                $existingSlot->times,
                $request->times
            ));

            sort($mergedTimes);

            $existingSlot->update([
                'times' => $mergedTimes,
                'slot_duration' => $request->slot_duration
            ]);

        } else {
            $user->bookingSlots()->create([
                'weekday' => $request->weekday,
                'times' => $request->times,
                'slot_duration' => $request->slot_duration
            ]);
        }

        return $this->sendResponse([], "Successfull");
    }

    public function getBookingSlots()
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return $this->sendError('Only doctors can view booking slots.');
        }

        $slots = $user->bookingSlots()
            ->select('id', 'weekday', 'times', 'slot_duration')
            ->get();

        $formattedSlots = $slots->map(function ($slot) {
            return [
                'id' => $slot->id,
                'weekday' => $slot->weekday,
                'slot_duration' => $slot->slot_duration,
                'times' => $slot->times,
            ];
        });

        return $this->sendResponse(
            (object)['slots' => $formattedSlots],
            "Successful"
        );
    }

    public function updateBookingSlot(Request $request, $slot_id)
    {
        $validator = Validator::make(
            array_merge($request->all(), ['slot_id' => $slot_id]),
            [
                'slot_id' => 'required|exists:booking_slots,id',
                'times' => 'required|array|min:1',
                'times.*' => 'required|date_format:H:i',
                'slot_duration' => 'required|integer|min:1',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return $this->sendError('Only doctors can update booking slots.');
        }

        $slot = BookingSlot::where('id', $slot_id)
                            ->where('user_id', $user->id)
                            ->first();

        if (!$slot) {
            return $this->sendError('Slot not found for this weekday.');
        }

        $newTimes = array_unique($request->times);
        sort($newTimes);

        $slot->update([
            'times' => $newTimes,
            'slot_duration' => $request->slot_duration
        ]);

        return $this->sendResponse([], "Slot updated successfully");
    }

    public function getDoctorAppointmentsDetails(Request $request)
    {
        $rules = [
            'appointment_id' => 'required|exists:book_appointments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can access appointment details.');
        }

        $appointment = BookAppointment::where('id', $request->query('appointment_id')) // changed
            ->where('doctor_id', $doctor->id)
            ->with([
                'customer:id,name',
                'prescriptions'
            ])
            ->first();

        if (!$appointment) {
            return $this->sendError('Appointment not found or access denied.');
        }

        $response = [
            'appoiment_id' => $appointment->id,
            'customer_id' => $appointment->customer->id ?? null,
            'customer_name' => $appointment->customer->name ?? null,
            'booking_date' => $appointment->booking_date,
            'weekday' => $appointment->weekday,
            'slot_time' => $appointment->booking_time,
            'notes' => $appointment->notes,
            'appointment_type' => $appointment->appointment_type,
            'amount' => $appointment->amount,
            'status' => $appointment->status,
        ];

        if ($appointment->status === "completed" && $appointment->prescriptions->count() > 0) {
            $prescriptionImages = $appointment->prescriptions->map(function ($prescription) {
                return [
                    'image_url' => asset('storage/' . $prescription->image),
                ];
            });

            $response['prescription_notes'] = $appointment->prescriptions->first()->notes ?? null;
            $response['prescription_images'] = $prescriptionImages;
        }

        return $this->sendResponse($response, "Appointment details fetched successfully");
    }

    public function uploadPrescriptionByDoctor(Request $request)
    {
        $rules = [
            'appointment_id' => 'required|exists:book_appointments,id',
            'notes' => 'nullable|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can upload prescriptions.');
        }

        $appointment = BookAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return $this->sendError('Appointment not found or access denied.');
        }

        DB::beginTransaction();

        try {

            foreach ($request->file('images') as $image) {

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $path = $image->storeAs('user-prescription',$filename,'public');

                Prescription::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $doctor->id,
                    'customer_id' => $appointment->user_id,
                    'notes' => $request->notes,
                    'image' => $path
                ]);
            }

            // $appointment->update([
            //     'status' => 'completed'
            // ]);

            AppointmentStatusLog::create([
                'appointment_id'   => $appointment->id,
                'changed_by'       => $doctor->id,
                'note'             => 'Completed by doctor and presciption uploaded',
                'new_status'       => $appointment->status,
                'changed_at'       => now(),
            ]);

            DB::commit();

            return $this->sendResponse([],'Prescription uploaded and appointment completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function cancelAppointmentByDoctor(Request $request)
    {
        $rules = [
            'appointment_id' => 'required|exists:book_appointments,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can cancel appointments.');
        }

        $appointment = BookAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return $this->sendError('Appointment not found or access denied.');
        }

        if ($appointment->status === 'completed') {
            return $this->sendError('Completed appointments cannot be cancelled.');
        }

        if ($appointment->status === 'cancelled') {
            return $this->sendError('Appointment is already cancelled.');
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        AppointmentStatusLog::create([
            'appointment_id'   => $appointment->id,
            'changed_by'       => $doctor->id,
            'note'             => 'cancel by doctor',
            'new_status'       => $appointment->status,
            'changed_at'       => now(),
        ]);

        return $this->sendResponse([], 'Appointment cancelled successfully.');
    }

    // public function calculateEarningsForDoctor(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'filter_type' => 'required|in:monthly,yearly',
    //         'month' => 'required_if:filter_type,monthly|string',
    //         'year' => 'required|digits:4|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first());
    //     }

    //     $doctor = Auth::user();

    //     if ($doctor->role !== 'doctor') {
    //         return $this->sendError('Only doctors can access earnings.');
    //     }

    //     $query = BookAppointment::with('user')
    //         ->where('doctor_id', $doctor->id)
    //         ->where('status', 'completed');

    //     if ($request->filter_type === 'monthly') {

    //         try {
    //             $monthNumber = Carbon::parse("1 " . $request->month)->month;
    //         } catch (\Exception $e) {
    //             return $this->sendError("Invalid month name.");
    //         }

    //         $query->whereMonth('booking_date', $monthNumber)
    //             ->whereYear('booking_date', $request->year);
    //     }

    //     if ($request->filter_type === 'yearly') {
    //         $query->whereYear('booking_date', $request->year);
    //     }

    //     $appointments = $query->orderBy('booking_date', 'desc')->get();

    //     $totalAmount = $appointments->sum('amount');

    //     $completedList = $appointments->map(function ($appointment) {
    //         return [
    //             'customer_name' => $appointment->user->name ?? 'N/A',
    //             'appointment_date' => $appointment->booking_date,
    //             'appointment_time' => $appointment->booking_time,
    //             'amount' => $appointment->amount,
    //         ];
    //     });

    //     return $this->sendResponse([
    //         'completed_appointments' => $completedList,
    //         'total_completed_income' => $totalAmount,
    //         'total_completed_count' => $appointments->count()
    //     ], "Doctor earnings fetched successfully.");
    // }
    public function calculateEarningsForDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter_type' => 'required|in:monthly,yearly',
            'month' => 'required_if:filter_type,monthly|string',
            'year' => 'required|digits:4|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can access earnings.');
        }

        $monthNumber = null;

        $query = BookAppointment::with('user')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed');

        if ($request->filter_type === 'monthly') {
            try {
                $monthNumber = Carbon::parse("1 " . $request->month)->month;
            } catch (\Exception $e) {
                return $this->sendError("Invalid month name.");
            }

            $query->whereMonth('booking_date', $monthNumber)
                ->whereYear('booking_date', $request->year);
        }

        if ($request->filter_type === 'yearly') {
            $query->whereYear('booking_date', $request->year);
        }

        $appointments = $query->orderBy('booking_date', 'desc')->get();

        $totalAmount = $appointments->sum('amount');

        $earningPercentage = Setting::where('item_key', 'earning_percentage')
            ->value('item_value') ?? 0;

        $platformShare = round($totalAmount * $earningPercentage / 100, 2);
        $doctorShare = round($totalAmount - $platformShare, 2);

        $isSettled = DoctorSettlement::where('doctor_id', $doctor->id)
            ->where('filter_type', $request->filter_type)
            ->where('year', $request->year)
            ->when($request->filter_type === 'monthly', function ($q) use ($monthNumber) {
                $q->where('month', $monthNumber);
            })
            ->exists();

        $completedList = $appointments->map(function ($appointment) {
            return [
                'customer_name' => $appointment->user->name ?? 'N/A',
                'appointment_date' => $appointment->booking_date,
                'appointment_time' => $appointment->booking_time,
                'amount' => $appointment->amount,
            ];
        });

        return $this->sendResponse([
            'completed_appointments' => $completedList,
            'total_completed_income' => $totalAmount,
            'total_completed_count' => $appointments->count(),
            'earning_percentage' => $earningPercentage,
            'platform_share' => $platformShare,
            'doctor_share' => $doctorShare,
            'is_settled' => $isSettled,
        ], "Doctor earnings fetched successfully.");
    }

    //----------------------Payment Method-----------------------------//

    public function paymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:book_appointments,id',
            'payment_method' => 'required|exists:payment_methods,id',
            'transaction_id' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {

            DB::beginTransaction();

            $appointment = BookAppointment::where('id',$request->appointment_id)
                            ->lockForUpdate()
                            ->first();

            if(!$appointment){
                DB::rollBack();
                return $this->sendError('Appointment not found');
            }

            $existingPayment = Payment::where('appointment_id',$appointment->id)
                                ->where('status','paid')
                                ->lockForUpdate()
                                ->first();

            if($existingPayment){
                DB::rollBack();
                return $this->sendError('Payment already completed for this appointment');
            }

            $payment = Payment::create([

                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'doctor_id' => $appointment->doctor_id,
                'payment_method_id' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'amount' => $appointment->amount,
                'status' => 'paid',
                'paid_at' => now()

            ]);

            DB::commit();

            return $this->sendResponse([
                'appointment_id' => $appointment->id,
                'transaction_id' => $payment->transaction_id,
                'user_id' => $payment->user_id,
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at
            ], "Payment successful.");

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->sendError('Payment failed. Please try again.');

        }
    }

    public function paymentHistory(Request $request)
    {
        $payments = Payment::with('method')
                    ->where('user_id',$request->user()->id)
                    ->latest()
                    ->get();

        if(!$payments){

            return $this->sendError('Appointment not found');
        }

        return $this->sendResponse([
            'data' => $payments
        ], "Payment history fetch successful.");

    }

    public function paymentMethodDropdown()
    {

        $methods = PaymentMethod::where('status',1)
                    ->select('id','display_name','slug')
                    ->get();

        if(!$methods){

            return $this->sendError('Appointment not found');
        }

        return $this->sendResponse([
            'data' => $methods
        ], 'Payment methods fetched successfully');

    }


}
