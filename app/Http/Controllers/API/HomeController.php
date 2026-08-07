<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

use App\Models\TrendingCategory;
use App\Models\Category;
use App\Models\Banner;
use App\Models\BookAppointment;
use App\Models\User;
use App\Models\Product;
class HomeController extends BaseController
{
    public function dashboard()
    {
        $top_doctors = $trending_categories = $categories = $banners = [];
        $cart_count = 0;
        $trending_categories = TrendingCategory::select('id','name','image')->where('status',1)->get();
        $banners = Banner::select('id','image')->where('status',1)->get();
        $categories = Category::select('id','name','image')->whereNull('parent_id')->where('status',1)->get();
        $top_doctors = User::select('id', 'name')
                            ->where('role', 'doctor')
                            ->with(['details' => function($query) {
                                $query->select('user_id', 'image', 'specialist');
                            }])
                            ->inRandomOrder()
                            ->limit(10)
                            ->get()
                            ->map(function ($doctor) {
                                if ($doctor->details && $doctor->details->image) {
                                    $doctor->details->image = asset('storage/' . $doctor->details->image);
                                }
                                return $doctor;
                            });
        $upcoming_appointment = null;
        $user = auth('api')->user();
        if ($user) {
            $upcoming_appointment = BookAppointment::with([
                                    'user' => function($query) {
                                        $query->select('id', 'name'); 
                                    },
                                    'user.details' => function($query) {
                                        $query->select('user_id', 'image'); 
                                    },
                                    'doctor' => function($query) {
                                        $query->select('id', 'name');
                                    },
                                    'doctor.details' => function($query) {
                                        $query->select('user_id', 'image', 'specialist');
                                    }
                                ])
                                ->where(function($query) use ($user) {
                                    $query->where('user_id', $user->id)
                                        ->orWhere('doctor_id', $user->id);
                                })
                                ->where('booking_date', '>=', now()->toDateString())
                                ->where('status', '!=', 'cancelled')
                                ->orderBy('booking_date', 'asc')
                                ->orderBy('appointment_no', 'asc')
                                ->first();
        }
        if ($upcoming_appointment) {

            if ($upcoming_appointment->user && $upcoming_appointment->user->details) {
                $img = $upcoming_appointment->user->details->image;
                $upcoming_appointment->user->details->image = $img ? asset('storage/'.$img) : null;
            }

            if ($upcoming_appointment->doctor && $upcoming_appointment->doctor->details) {
                $img = $upcoming_appointment->doctor->details->image;
                $upcoming_appointment->doctor->details->image = $img ? asset('storage/'.$img) : null;
            }
        }
        $result = [
            'cart_count' => $cart_count,
            'banners' => $banners,
            'categories' => $categories,
            'trending_categories' => $trending_categories,
            'top_doctors' => $top_doctors,
            'upcoming_appointment' => $upcoming_appointment
        ];
        return $this->sendResponse($result, 'Successfull');
    }
    public function subCategories(Request $request)
    {
        $category_id = $request->category_id;
        $name = $request->name;
        $subCategoryQry = Category::select('id','name','image')->where('parent_id',$category_id)->where('status',1);
        if($name){
            $subCategoryQry->where('name','LIKE','%'.$name.'%');
        }
        $sub_categories = $subCategoryQry->orderBy('id','DESC')->get();
        $result = [
            'sub_categories' => $sub_categories
        ];
        return $this->sendResponse($result, 'Successfull');
    }

    public function products(Request $request)
    {
        $perPage = $request->input('per_page', 40);
        $url = asset('storage/');

        $productQry = Product::with(['prices' => function($qry) {
                $qry->select('id', 'product_id', 'pack_size', 'price', 'special_price')->limit(1);
            }])
            ->select('id', 'name', 'image', 'category_id', 'status')
            ->selectRaw("CASE 
                WHEN LENGTH(description) > 0 
                THEN CONCAT(SUBSTRING_INDEX(description, ' ', 15), 
                    IF(LENGTH(description) - LENGTH(REPLACE(description, ' ', '')) > 15, '...', ''))
                ELSE ''
            END as description")
            ->where('status', 1);

        $target_ids = [];
        if ($request->sub_category_id) {
            $target_ids[] = $request->sub_category_id;
        } elseif ($request->category_id) {
            $target_ids = Category::where('parent_id', $request->category_id)->pluck('id')->toArray();
            $target_ids[] = $request->category_id;
        }
        if (!empty($target_ids)) {
            $productQry->whereIn('category_id', array_unique($target_ids));
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $productQry->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }

        $products = $productQry->orderBy('id', 'DESC')->paginate($perPage);

        $products->getCollection()->transform(function ($product) use ($url) {
            $product->image = $product->image ? $url . '/' . $product->image : asset('images/default-product.png');
            return $product;
        });

        $result = $products->toArray();

        return $this->sendResponse($result, 'Successful');
    }

    public function productDetails($id)
    {
        $product = Product::with(['prices'=>function($qry){
                                $qry->select('id','product_id','pack_size','price','special_price');
                            },'images'=>function($qry){
                                $qry->select('id','product_id','image');
                            }])->where('id',$id)->first();
        $result = [
            'product' => $product
        ];
        return $this->sendResponse($result, 'Successfull');
    }
}
