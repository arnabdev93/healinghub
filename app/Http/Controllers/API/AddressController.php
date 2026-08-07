<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\PaymentMethod;

use Validator;
use Auth;
class AddressController extends BaseController
{
    public function index()
    {
        $user_id = Auth::id();
        $addresses = Address::select('id','name','address','country','state','city','pincode','building','receipent_name','phone','latitude','longitude')->where('user_id',$user_id)->orderBy('is_delivery','DESC')->orderBy('id','DESC')->get()->makeHidden(['location']);
        $wallet_amount = 0;
        $payment_methods = PaymentMethod::select('display_name','slug')->where('status',1)->get();
        $result = [
            'wallet_amount' => $wallet_amount,
            'payment_methods' => $payment_methods,
            'addresses' => $addresses
        ];
        return $this->sendResponse($result, 'Successfull');
    }
    public function store(Request $request)
    {
        $rules = [
            'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'receipent_name' => 'nullable',
            'mobile' => 'nullable',
            'type' => 'nullable',
            'address' => 'required|string|min:3',
            'building_number' => 'nullable',
            'country' => 'nullable',
            'state' => 'nullable',
            'pincode' => 'required|min:6',
            'address_id' => 'nullable'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user = Auth::user();
            $user_id = $user->id;
            
            $address_id = $request->address_id;
            if($address_id){
                $address = Address::where('id',$address_id)->where('user_id',$user_id)->first();
                if(!$address){
                    return $this->sendError("Address not found");
                }
            }else{
                $address = new Address;
                $address->user_id = $user_id;
            }
            $address->address = $request->address;
            $address->pincode = $request->pincode;
            $address->state = $request->state ?? 'West Bengal';
            $address->country = $request->country ?? 'India';
            $address->city = $request->city;
            $address->latitude = (string) $request->latitude;
            $address->longitude = (string) $request->longitude;
            $address->building = $request->building_number;
            $address->receipent_name = $request->receipent_name ?? $user->name;
            $address->phone = $request->mobile ?? $user->mobile;
            $address->name = $request->type ?? 'Home';
            $address->save();

            $result = [
                'address_id' => $address->id
            ];
            return $this->sendResponse($result, 'Successfull');
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }
}
