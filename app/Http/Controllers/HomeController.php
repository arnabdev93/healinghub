<?php

namespace App\Http\Controllers;

use App\Models\BookAppointment;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Category;
use App\Models\TrendingCategory;
use App\Models\Order;
use Auth;
use Storage;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        //New Counts (This Month)

        $newDoctors = User::where('role','doctor')
            ->whereMonth('created_at',Carbon::now()->month)
            ->count();

        $newPatients = User::where('role','customer')
            ->whereMonth('created_at',Carbon::now()->month)
            ->count();

        $newAppointments = BookAppointment::whereMonth('created_at',Carbon::now()->month)
            ->count();


        //Total Counts

        $totalPatients = User::where('role','customer')->count();

        $totalDoctors = User::where('role','doctor')->count();

        $totalAppointments = BookAppointment::count();

        //Activity Graph (Last 6 Months)

        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $results = BookAppointment::where('created_at', '>=', $startDate)
            ->selectRaw('
                MONTHNAME(created_at) as month_name,
                MONTH(created_at) as month_num,
                COUNT(*) as total_appointments,
                SUM(amount) as total_earnings
            ')
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_name');

        $activityData = [];
        for ($i=5; $i>=0; $i--) {
            $monthName = Carbon::now()->subMonths($i)->format('F');
            $monthShort = Carbon::now()->subMonths($i)->format('M');

            $data = $results->get($monthName);

            $activityData[] = [
                'month' => $monthShort,
                'total' => $data ? $data->total_appointments : 0,
                'earnings' => $data ? ($data->total_earnings ?? 0) : 0
            ];
        }

        //Doctor Success Stats (Top Doctors by completed appointments)

        $doctorStats = User::where('role','doctor')
                            ->with('details')
                            ->withCount(['doctorAppointments as completed_appointments' => function($q){
                                $q->where('status','completed');
                            }])
                            ->orderByDesc('completed_appointments')
                            ->take(6)
                            ->get();

        //Doctor List

        $doctorList = User::where('role','doctor')
                            ->with('details')
                            ->latest()
                            ->take(5)
                            ->get();

        //Latest Appointments

        $latestAppointments = BookAppointment::with(['user','doctor'])
            ->latest()
            ->take(5)
            ->get();

        // Products
        $totalProducts = Product::count();

        // Categories
        $totalCategories = Category::count();

        $newCategories = Category::whereMonth('created_at', now()->month)->count();

        // Trending Categories
        $totalTrendingCategories = TrendingCategory::count();

        // Products
        $totalProducts = Product::count();

        // Categories
        $totalCategories = Category::count();

        $newCategories = Category::whereMonth('created_at', now()->month)->count();

        // Trending Categories
        $totalTrendingCategories = TrendingCategory::count();

        // Cart Orders (normal)
        $cartOrders = Order::where('type', 'cart')->count();

        // Prescription Orders
        $prescriptionOrders = Order::where('type', 'prescription')->count();

        // Upcoming Appointments
        $upcomingAppointments = BookAppointment::where('status', 'upcoming')->count();

        // Pending Cart Orders
        $pendingCartOrders = Order::where('type', 'cart')->where('status', 'pending')->count();

        // Pending Prescription Orders
        $pendingPrescriptionOrders = Order::where('type', 'prescription')->where('status', 'pending')->count();


        return view('home',compact(
            'newDoctors',
            'newPatients',
            'newAppointments',
            'totalPatients',
            'totalDoctors',
            'totalAppointments',
            'activityData',
            'doctorStats',
            'doctorList',
            'latestAppointments',
            'totalProducts',
            'totalCategories',
            'newCategories',
            'totalTrendingCategories',
            'cartOrders',
            'prescriptionOrders',
            'upcomingAppointments',
            'pendingCartOrders',
            'pendingPrescriptionOrders'
        ));
    }

    public function getChartData($months)
    {
        $startDate = \Carbon\Carbon::now()->subMonths($months - 1)->startOfMonth();

        $results = BookAppointment::where('created_at', '>=', $startDate)
            ->selectRaw('
                MONTHNAME(created_at) as month_name,
                MONTH(created_at) as month_num,
                COUNT(*) as total_apps,
                SUM(amount) as total_earnings
            ')
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_name');

        $labels = [];
        $appointmentData = [];
        $earningsData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $monthName = $month->format('F');
            $monthShort = $month->format('M');

            $data = $results->get($monthName);

            $labels[] = $monthShort;

            $appointmentData[] = $data ? (int)$data->total_apps : 0;
            $earningsData[] = $data ? (float)($data->total_earnings ?? 0) : 0;
        }

        return response()->json([
            'labels' => $labels,
            'appointments' => $appointmentData,
            'earnings' => $earningsData
        ]);
    }
    public function profile()
    {
        $user = Auth::user();
        return view('profile',compact('user'));
    }
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            // 'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
            // 'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif'
        ]);

        // if($request->hasFile('image')){
        //     if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
        //         Storage::disk('public')->delete($user->profile_image);
        //     }
        //     $user->profile_image = $request->image->store('profile', 'public');
        // }
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('profile')->withSuccess('Profile Updated');
    }
}
