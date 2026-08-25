<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     securityScheme="passport",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     * @OA\Get(
     *     path="/healinghub/api/app-settings",
     *     tags={"Basic API"},
     *     summary="Get App Settings",
     *     description="Retrieve application settings",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Success message"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="App settings data"
     *             )
     *         )
     *     ),
     * )
     * @OA\Post(
     *     path="/healinghub/api/send-otp",
     *     tags={"Basic API"},
     *     summary="Send OTP",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile"},
     *             @OA\Property(
     *                 property="mobile",
     *                 type="string",
     *                 example="7890026841",
     *                 description="Customer mobile number"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully to your mobile",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP sent successfully"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             )
     *         )
     *     ),
     * )
     * @OA\Post(
     *     path="/healinghub/api/otp-verify",
     *     tags={"Basic API"},
     *     summary="OTP Verification",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile", "otp", "type"},
     *             @OA\Property(
     *                 property="mobile",
     *                 type="string",
     *                 example="7890026841",
     *                 description="Customer mobile number"
     *             ),
     *             @OA\Property(
     *                 property="otp",
     *                 type="string",
     *                 example="1111",
     *                 description="OTP received on mobile"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP Verified Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP verified successfully"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             )
     *         )
     *     ),
     * )
     * @OA\Post(
     *     path="/healinghub/api/sign-up",
     *     tags={"Basic API"},
     *     summary="Customer Registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile", "name", "email"},
     *             @OA\Property(property="mobile",type="string",example="7890026841"),
     *             @OA\Property(property="name",type="string",example="Jawed Afteab"),
     *             @OA\Property(property="email",type="string",example="jawed@cb.com"),
     *             @OA\Property(property="gender",type="string",example="Male/Female"),
     *             @OA\Property(property="age",type="string",example="35"),
     *             @OA\Property(property="weight",type="string",example="75"),
     *             @OA\Property(property="heart_rate",type="string",example=""),
     *             @OA\Property(property="bp",type="string",example=""),
     *             @OA\Property(property="calories",type="string",example=""),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Signup Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Signup successfully"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             )
     *         )
     *     ),
     * )
     * @OA\Post(
     * path="/healinghub/api/fcm-version-update",
     * tags={"Basic API"},
     * summary="FCM token, AppVerion Update",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="fcm_token", type="string", example="jdfhgjdmnbmfkjghkdgkldklsnkdjgkldg"),
     *             @OA\Property(property="app_version", type="string", example="Android_Customer_1.0"),
     *         )
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Signup Successful",
     *          @OA\JsonContent()
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Get(
     * path="/healinghub/api/notifications",
     * tags={"Basic API"},
     * summary="Notification Lists",
     *      @OA\Response(
     *          response=200,
     *          description="Signup Successful",
     *          @OA\JsonContent()
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Post(
     *     path="/healinghub/api/meets",
     *     tags={"Basic API"},
     *     summary="Meet Create API",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property( property="title",type="string",example="Weekly Standup"),
     *             @OA\Property( property="description",type="string",example="Team sync meeting"),
     *             @OA\Property( property="start_time",type="string",example="2025-05-15 10:00:00"),
     *             @OA\Property( property="end_time",type="string",example="2025-05-15 11:00:00"),
     *             @OA\Property(
     *                 property="attendees",
     *                 type="array",
     *                 @OA\Items(type="string", example="tanushriroul95@gmail.com"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully to your mobile",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP sent successfully"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             )
     *         )
     *     ),
     * )
     * @OA\Get(
     * path="/healinghub/api/dashboard",
     * tags={"Customer API"},
     * security={{"passport": {}}},
     * summary="Customer Dashboard",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     * )
     * @OA\Get(
     * path="/healinghub/api/sub-categories",
     * tags={"Customer API"},
     * summary="Sub Categories",
     *      @OA\Parameter(
     *          name="category_id",description="Parent Category ID",in="query",style="form",explode=false,example="1",
     *      ),
     *      @OA\Parameter(
     *          name="name",description="Search by Sub-Category Name",in="query",style="form",explode=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       )
     * )
     * @OA\Get(
    *     path="/healinghub/api/products",
    *     tags={"Customer API"},
    *     summary="Product Lists",
    *     description="Get product list with category, subcategory, and search filter",
    *
    *     @OA\Parameter(
    *         name="category_id",
    *         in="query",
    *         description="Parent Category ID",
    *         required=false,
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *
    *     @OA\Parameter(
    *         name="sub_category_id",
    *         in="query",
    *         description="Sub Category ID",
    *         required=false,
    *         @OA\Schema(type="integer", example=5)
    *     ),
    *
    *     @OA\Parameter(
    *         name="search",
    *         in="query",
    *         description="Search products by name",
    *         required=false,
    *         @OA\Schema(type="string", example="paracetamol")
    *     ),
    *
    *     @OA\Parameter(
    *         name="per_page",
    *         in="query",
    *         description="Number of products per page",
    *         required=false,
    *         @OA\Schema(type="integer", example=20)
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Successful",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Successful"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *
    *                 @OA\Property(
    *                     property="current_page",
    *                     type="integer",
    *                     example=1
    *                 ),
    *
    *                 @OA\Property(
    *                     property="data",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="id", type="integer", example=1),
    *                         @OA\Property(property="name", type="string", example="Paracetamol Tablet"),
    *                         @OA\Property(property="image", type="string", example="http://example.com/storage/product.jpg"),
    *                         @OA\Property(property="category_id", type="integer", example=2),
    *                         @OA\Property(property="description", type="string", example="Short product description..."),
    *
    *                         @OA\Property(
    *                             property="prices",
    *                             type="array",
    *                             @OA\Items(
    *                                 type="object",
    *                                 @OA\Property(property="id", type="integer", example=1),
    *                                 @OA\Property(property="product_id", type="integer", example=1),
    *                                 @OA\Property(property="pack_size", type="string", example="10 tablets"),
    *                                 @OA\Property(property="price", type="number", example=100),
    *                                 @OA\Property(property="special_price", type="number", example=80)
    *                             )
    *                         )
    *                     )
    *                 ),
    *
    *                 @OA\Property(property="per_page", type="integer", example=20),
    *                 @OA\Property(property="total", type="integer", example=100)
    *             )
    *         )
    *     )
    * )
     * @OA\Get(
     * path="/healinghub/api/products/{id}",
     * tags={"Customer API"},
     * summary="Product Details",
     *      @OA\Parameter(
     *          name="id",description="Product ID",in="path",style="form",explode=false,example="1",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       )
     * )
     * @OA\Get(
     * path="/healinghub/api/my-profile",
     * tags={"Basic API"},
     * summary="My Profile",
     *      @OA\Response(
     *          response=200,
     *          description="Signup Successful",
     *          @OA\JsonContent()
     *       ),
     *     security={{"passport": {}}},
     * )
     * @OA\Post(
     * path="/healinghub/api/profile-update",
     * tags={"Basic API"},
     * summary="Profile Update",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",type="string",description="User Name",example="Jawed Aftab"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",type="string",description="Male/Female. Required for Customer",example="Male"
     *                 ),
     *                 @OA\Property(
     *                    property="specialist",
     *                    type="string",
     *                    description="specialist",
     *                    example="Homeopathy"
     *                 ),
     *                 @OA\Property(
     *                     property="age",type="string",description="Age required for Customer",example="34"
     *                 ),
     *                 @OA\Property(
     *                     property="weight",type="string",description="Weight required for Customer",example="77"
     *                 ),
     *                 @OA\Property(
     *                     property="heart_rate",type="string",description="For Customer Only"
     *                 ),
     *                 @OA\Property(
     *                     property="bp",type="string",description="For Customer Only"
     *                 ),
     *                 @OA\Property(
     *                     property="calories",type="string",description="For Customer Only"
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",type="string",description="1/2 Required for Doctor Only",example="2"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_name",type="string",description="For Doctor Only"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_acc_no",type="string",description="For Doctor Only"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_ifsc_code",type="string",description="For Doctor Only"
     *                 ),
     *                 @OA\Property(
     *                     property="upi",type="string",description="For Doctor Only"
     *                 ),
     *                 @OA\Property(
     *                     property="image",type="string",description="Profile Image",format="binary"
     *                 ),
     *                 required={"name"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Get(
     * path="/healinghub/api/addresses",
     * tags={"Customer API"},
     * summary="Address Lists",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Post(
     * path="/healinghub/api/addresses",
     * tags={"Customer API"},
     * summary="Create Address",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="address", type="string", example="Street Address"),
     *             @OA\Property(property="type", type="string", example="Home/Work/Others"),
     *             @OA\Property(property="building_number", type="string", example=""),
     *             @OA\Property(property="receipent_name", type="string", example="Jawed Aftab"),
     *             @OA\Property(property="mobile", type="string", example="7890026841"),
     *             @OA\Property(property="address_id", type="string", example=""),
     *             @OA\Property(property="pincode", type="string", example="700004"),
     *             @OA\Property(property="city", type="string", example="Kolkata"),
     *             @OA\Property(property="state", type="string", example="West Bengal"),
     *             @OA\Property(property="country", type="string", example="India"),
     *             @OA\Property(property="latitude", type="string", example="22.4559104"),
     *             @OA\Property(property="longitude", type="string", example="88.2737152"),
     *         )
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="1"),
     *             @OA\Property(property="message", type="string", example="Successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="address_id", type="integer", example="1"),
     *             )
     *          )
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Get(
     * path="/healinghub/api/order",
     * tags={"Customer API"},
     * summary="Cart/Prescription Order List",
     *      @OA\Parameter(
     *          name="type",description="cart/prescription",in="query",style="form",explode=false,example="prescription",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *       security={{"passport": {}}}
     * )
     * @OA\Post(
    *     path="/healinghub/api/prescription-order/razorpay-order",
    *     tags={"Customer API"},
    *     summary="Create Prescription Razorpay Order",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="order_id", type="integer", example=1)
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Razorpay order created",
    *         @OA\JsonContent()
    *     ),
    *     security={{"passport": {}}}
    * )
    * @OA\Post(
    *     path="/healinghub/api/prescription-order/verify-payment",
    *     tags={"Customer API"},
    *     summary="Verify Prescription Payment",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="order_id", type="integer", example=1),
    *             @OA\Property(property="razorpay_payment_id", type="string", example="pay_TTuQNzt1G396b0"),
    *             @OA\Property(property="razorpay_order_id", type="string", example="order_TTuNsG9dtVoAiu"),
    *             @OA\Property(property="razorpay_signature", type="string", example="generated_signature_here")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Payment successful",
    *         @OA\JsonContent()
    *     ),
    *     security={{"passport": {}}}
    * )
    *  @OA\Post(
    *     path="/healinghub/api/cart/razorpay-order",
    *     tags={"Customer API"},
    *     summary="Create Razorpay Order from Cart",
    *     description="Creates a Razorpay order using the customer's cart total and selected address.",
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"address_id"},
    *
    *             @OA\Property(
    *                 property="address_id",
    *                 type="integer",
    *                 example=1,
    *                 description="Customer address ID"
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Razorpay order created successfully",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="success",
    *                 type="boolean",
    *                 example=true
    *             ),
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="Razorpay order created"
    *             ),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="razorpay_order_id",
    *                     type="string",
    *                     example="order_RZP123456789"
    *                 ),
    *                 @OA\Property(
    *                     property="razorpay_key",
    *                     type="string",
    *                     example="rzp_test_xxxxxxxxx"
    *                 ),
    *                 @OA\Property(
    *                     property="amount",
    *                     type="integer",
    *                     example=150000,
    *                     description="Amount in paise"
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation or cart error",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="success",
    *                 type="boolean",
    *                 example=false
    *             ),
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="Cart is empty"
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     ),
    *
    *     security={{"passport": {}}}
    * )
    * @OA\Post(
    *     path="/healinghub/api/upload-prescription",
    *     operationId="uploadPrescription",
    *     tags={"Customer API"},
    *     summary="Upload Prescription",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 type="object",
    *                 @OA\Property(property="address_id", type="string", example="1", description="Address ID"),
    *                 @OA\Property(property="notes", type="string", example="Notes write here", description="Notes regarding Prescription"),
    *                 @OA\Property(
    *                     property="images[]",
    *                     type="array",
    *                     @OA\Items(
    *                         type="string",
    *                         format="binary",
    *                         description="Prescription Images"
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful",
    *          @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="1"),
    *             @OA\Property(property="message", type="string", example="Successful"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object"
    *             )
    *          )
    *     ),
    *     security={{"passport": {}}}
    * )
    * @OA\Get(
    * path="/healinghub/api/cart",
    * tags={"Customer API"},
    * summary="Cart List",
    *      @OA\Response(
    *          response=200,
    *          description="Successful",
    *          @OA\JsonContent()
    *       ),
    *       security={{"passport": {}}}
    * )
    * @OA\Post(
    * path="/healinghub/api/cart",
    * tags={"Customer API"},
    * summary="Add Item to Cart",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="product_id", type="string", example="1"),
    *             @OA\Property(property="quantity", type="string", example="1"),
    *             @OA\Property(property="medicine_power", type="string", example="1M"),
    *             @OA\Property(property="product_price_id", type="string", example="1"),
    *         )
    *    ),
    *      @OA\Response(
    *          response=200,
    *          description="Signup Successful",
    *          @OA\JsonContent()
    *       ),
    *       security={{"passport": {}}}
    * )
    * @OA\Put(
    * path="/healinghub/api/cart",
    * tags={"Customer API"},
    * summary="Update CartItem Quantity",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="cart_item_id", type="string", example="1"),
    *             @OA\Property(property="quantity", type="string", example="1"),
    *             @OA\Property(property="type", type="string", example="add/remove"),
    *         )
    *    ),
    *      @OA\Response(
    *          response=200,
    *          description="Signup Successful",
    *          @OA\JsonContent()
    *       ),
    *       security={{"passport": {}}}
    * )
    * @OA\Delete(
    * path="/healinghub/api/cart/item/{id}",
    * tags={"Customer API"},
    * summary="Cart Item Delete",
    *      @OA\Parameter(
    *          name="id",
    *          description="Cart Item Id",
    *          in="path",
    *          style="form",
    *          explode=false,
    *          example="1",
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Signup Successful",
    *          @OA\JsonContent()
    *       ),
    *       security={{"passport": {}}}
    * )
    * @OA\Post(
    * path="/healinghub/api/order",
    * tags={"Customer API"},
    * summary="Order from Cart",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
 *             @OA\Property(property="address_id", type="string", example="1"),
 *             @OA\Property(property="payment_method", type="string", example="upi"),
 *             @OA\Property(property="razorpay_payment_id", type="string", example="pay_Nz7abc123456"),
 *             @OA\Property(property="razorpay_order_id", type="string", example="order_Nz7xyz123456"),
 *             @OA\Property(property="razorpay_signature", type="string", example="abc123xyz456signature")
 *         )
    *    ),
    *      @OA\Response(
    *          response=200,
    *          description="Signup Successful",
    *          @OA\JsonContent()
    *       ),
    *       security={{"passport": {}}}
    * )
    * @OA\Get(
    *     path="/healinghub/api/get-doctors/{category_id}",
    *     summary="Get Doctors By Category",
    *     description="Returns list of doctors filtered by category_id",
    *     operationId="getDoctors",
    *     tags={"Basic API"},
    *
    *     @OA\Parameter(
    *         name="category_id",
    *         in="path",
    *         required=true,
    *         description="Category ID from categories table",
    *         @OA\Schema(
    *             type="integer",
    *             example=1
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Successful response",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Successful"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="doctors",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="doctor_id", type="integer", example=5),
    *                         @OA\Property(property="doctor_name", type="string", example="Dr. Amit Sharma"),
    *                         @OA\Property(property="specialist", type="string", example="Cardiologist"),
    *                         @OA\Property(property="doctor_image", type="string", example="http://yourdomain.com/storage/image.jpg"),
    *                         @OA\Property(
    *                             property="available_weekdays",
    *                             type="array",
    *                             @OA\Items(type="string", example="Monday")
    *                         )
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=422,
    *         description="Validation Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Invalid category id.")
    *         )
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/show-doctor/{doctor_id}",
    *     summary="Get Doctor Details",
    *     description="Fetch doctor profile details by doctor_id",
    *     operationId="showDoctor",
    *     tags={"Basic API"},
    *
    *     @OA\Parameter(
    *         name="doctor_id",
    *         in="path",
    *         required=true,
    *         description="ID of the doctor",
    *         @OA\Schema(
    *             type="integer",
    *             example=5
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Doctor details fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Successful"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(property="id", type="integer", example=5),
    *                 @OA\Property(property="name", type="string", example="Dr. John Doe"),
    *                 @OA\Property(property="email", type="string", example="doctor@example.com"),
    *                 @OA\Property(property="mobile", type="string", example="9876543210"),
    *                 @OA\Property(property="audio_call_fee", type="number", format="float", example=500),
    *                 @OA\Property(property="video_call_fee", type="number", format="float", example=800),
    *                 @OA\Property(property="image", type="string", example="http://example.com/storage/doctor.jpg"),
    *                 @OA\Property(property="speciality", type="string", example="Cardiologist"),
    *                 @OA\Property(property="about", type="string", example="10 years of experience in heart treatments.")
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=422,
    *         description="Validation Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Invalid doctor id.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=404,
    *         description="Doctor Not Found",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Doctor not found")
    *         )
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/appointment/razorpay-order",
    *     tags={"Customer API"},
    *     summary="Create Appointment Razorpay Order",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="doctor_id", type="integer", example=10),
    *             @OA\Property(property="booking_date", type="string", format="date", example="2026-08-25"),
    *             @OA\Property(property="booking_time", type="string", example="10:00"),
    *             @OA\Property(property="appointment_type", type="string", example="video")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Razorpay order created",
    *         @OA\JsonContent()
    *     ),
    *     security={{"passport": {}}}
    * )
    * @OA\Post(
    *     path="/healinghub/api/book-appointment",
    *     summary="Book Appointment",
    *     description="Customer can book an appointment with a doctor",
    *     operationId="bookAppointment",
    *     tags={"Customer API"},
    *     security={{"passport": {}}},
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"doctor_id","booking_date","booking_time","appointment_type"},
    *             @OA\Property(property="doctor_id", type="integer", example=5),
    *             @OA\Property(property="booking_date", type="string", format="date", example="2026-03-15"),
    *             @OA\Property(property="booking_time", type="string", format="time", example="14:30"),
    *             @OA\Property(property="appointment_type", type="string", enum={"audio","video"}, example="video"),
    *             @OA\Property(property="notes", type="string", example="Need consultation for headache"),
    *             @OA\Property(property="payment_method", type="string", example="upi"),
    *             @OA\Property(property="razorpay_payment_id", type="string", example="pay_TTuQNzt1G396b0"),
    *             @OA\Property(property="razorpay_order_id", type="string", example="order_TTuNsG9dtVoAiu"),
    *             @OA\Property(property="razorpay_signature", type="string", example="generated_signature_here")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Appointment booked successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Appointment booked successfully"),
    *             @OA\Property(property="data", type="object")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Selected time is not available")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated.")
    *         )
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/get-avalable-slots",
    *     summary="Get Doctor Available Slots",
    *     description="Fetch available booking slots for a doctor by date or weekday name.",
    *     tags={"Basic API"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"doctor_id","day"},
    *             @OA\Property(
    *                 property="doctor_id",
    *                 type="integer",
    *                 example=5,
    *                 description="ID of the doctor"
    *             ),
    *             @OA\Property(
    *                 property="day",
    *                 type="string",
    *                 example="2026-02-15",
    *                 description="Provide either full date (YYYY-MM-DD) or weekday name (e.g. Monday)"
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Available slots fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Available slots fetched successfully"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="date",
    *                     type="string",
    *                     example="2026-02-15",
    *                     nullable=true
    *                 ),
    *                 @OA\Property(
    *                     property="slots",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(
    *                             property="time",
    *                             type="string",
    *                             example="14:00"
    *                         ),
    *                         @OA\Property(
    *                             property="is_available",
    *                             type="boolean",
    *                             example=true
    *                         )
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Validation error"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Doctor or slots not found"
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/cancel-appointment",
    *     summary="Cancel Appointment",
    *     description="Allows authenticated customer to cancel an appointment if it is not completed and at least 2 hours before start time.",
    *     operationId="cancelAppointment",
    *     tags={"Customer API"},
    *     security={{"passport": {}}},
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"booking_id"},
    *             @OA\Property(
    *                 property="booking_id",
    *                 type="integer",
    *                 example=12,
    *                 description="ID of the booking to cancel"
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Appointment cancelled successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Appointment cancelled successfully."),
    *             @OA\Property(property="data", type="object", example={})
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation or business logic error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="This appointment is already cancelled.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/request-for-price-quote",
    *     summary="Request for Price Quote",
    *     description="Customer can request a price quote by submitting address, notes and up to 4 images.",
    *     operationId="requestForPriceQuote",
    *     tags={"Customer API"},
    *     security={{"passport": {}}},
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 required={"address_id","images"},
    *
    *                 @OA\Property(
    *                     property="address_id",
    *                     type="integer",
    *                     example=5,
    *                     description="Existing address ID"
    *                 ),
    *
    *                 @OA\Property(
    *                     property="notes",
    *                     type="string",
    *                     nullable=true,
    *                     example="Need urgent service quotation"
    *                 ),
    *
    *                 @OA\Property(
    *                     property="images[]",
    *                     type="array",
    *                     @OA\Items(
    *                         type="string",
    *                         format="binary"
    *                     ),
    *                     description="Upload up to 4 images (jpeg, png, jpg, max 2MB each)"
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Price quote request submitted successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Price quote request submitted successfully.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="The address id field is required.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/create-booking-slot",
    *     summary="Create or update doctor booking slot",
    *     description="Doctor can create or update weekly booking slots. If weekday already exists, times will be merged.",
    *     operationId="createBookingSlot",
    *     tags={"Doctor API"},
    *     security={{"passport": {}}},
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"weekday","slot_duration","times"},
    *
    *             @OA\Property(
    *                 property="weekday",
    *                 type="string",
    *                 example="Monday",
    *                 enum={"Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"}
    *             ),
    *
    *             @OA\Property(
    *                 property="slot_duration",
    *                 type="integer",
    *                 example=30,
    *                 description="Duration of each slot in minutes"
    *             ),
    *
    *             @OA\Property(
    *                 property="times",
    *                 type="array",
    *                 description="Array of available time slots in H:i format",
    *                 @OA\Items(
    *                     type="string",
    *                     format="time",
    *                     example="14:30"
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Slot created or updated successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Successfull"),
    *             @OA\Property(property="data", type="array", @OA\Items())
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation error or unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Only doctors can create booking slots.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/booking-slots",
    *     summary="Get Doctor Booking Slots",
    *     description="Returns all booking slots for the authenticated doctor",
    *     operationId="getBookingSlots",
    *     tags={"Doctor API"},
    *     security={{"passport": {}}},
    *
    *     @OA\Response(
    *         response=200,
    *         description="Slots fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Successful"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="slots",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="id", type="integer", example=1),
    *                         @OA\Property(property="weekday", type="string", example="Monday"),
    *                         @OA\Property(property="slot_duration", type="integer", example=30),
    *                         @OA\Property(
    *                             property="times",
    *                             type="array",
    *                             @OA\Items(type="string", example="10:00")
    *                         )
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     ),
    *
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Only doctors can view booking slots.")
    *         )
    *     )
    * )
    * @OA\Post(
    *     path="/healinghub/api/update-booking-slot/{slot_id}",
    *     summary="Update Booking Slot",
    *     description="Update time slots and slot duration using slot_id",
    *     operationId="updateBookingSlot",
    *     tags={"Doctor API"},
    *     security={{"passport": {}}},
    *
    *     @OA\Parameter(
    *         name="slot_id",
    *         in="path",
    *         required=true,
    *         description="Booking Slot ID",
    *         @OA\Schema(
    *             type="integer",
    *             example=1
    *         )
    *     ),
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"times","slot_duration"},
    *
    *             @OA\Property(
    *                 property="times",
    *                 type="array",
    *                 description="List of time slots in H:i format",
    *                 @OA\Items(type="string", example="10:00")
    *             ),
    *
    *             @OA\Property(
    *                 property="slot_duration",
    *                 type="integer",
    *                 example=30,
    *                 description="Slot duration in minutes"
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Slot updated successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Slot updated successfully"),
    *             @OA\Property(property="data", type="object", example={})
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=422,
    *         description="Validation Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Invalid input.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Only doctors can update booking slots.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=404,
    *         description="Slot Not Found",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Slot not found or unauthorized.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/get-appointments-list",
    *     summary="Get Appointments List",
    *     description="Returns appointment list for authenticated user (customer or doctor) filtered by status",
    *     operationId="getAppointmentsList",
    *     tags={"Basic API"},
    *     security={{"passport": {}}},
    *
    *     @OA\Parameter(
    *         name="status",
    *         in="query",
    *         required=true,
    *         description="Appointment status filter",
    *         @OA\Schema(
    *             type="string",
    *             enum={"upcoming","completed","cancelled"}
    *         ),
    *         example="upcoming"
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Appointment list fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Appointment list fetched successfully"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="appointments",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="appointment_id", type="integer", example=1),
    *
    *                         @OA\Property(property="doctor_id", type="integer", example=5, nullable=true),
    *                         @OA\Property(property="doctor_name", type="string", example="Dr. Amit Sharma", nullable=true),
    *                         @OA\Property(property="specialist", type="string", example="Cardiologist", nullable=true),
    *
    *                         @OA\Property(property="customer_id", type="integer", example=10, nullable=true),
    *                         @OA\Property(property="customer_name", type="string", example="Rahul Sharma", nullable=true),
    *
    *                         @OA\Property(property="booking_date", type="string", format="date", example="2026-02-20"),
    *                         @OA\Property(property="weekday", type="string", example="Monday"),
    *                         @OA\Property(property="slot_time", type="string", example="14:00"),
    *                         @OA\Property(property="appointment_type", type="string", example="video"),
    *                         @OA\Property(property="notes", type="string", nullable=true, example="Need consultation"),
    *                         @OA\Property(property="amount", type="number", format="float", example=500),
    *                         @OA\Property(property="status", type="string", example="upcoming")
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation error"
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/get-appointment-details",
    *     operationId="getAppointmentDetails",
    *     tags={"Basic API"},
    *     summary="Get Appointment Details",
    *     description="Fetch appointment details for authenticated user (customer or doctor). Includes doctor/customer info and prescription if completed.",
    *     security={{"passport": {}}},
    *
    *     @OA\Parameter(
    *         name="appointment_id",
    *         in="query",
    *         required=true,
    *         description="Appointment ID",
    *         @OA\Schema(type="integer"),
    *         example=15
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Appointment details fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Appointment details fetched successfully"),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(property="appointment_id", type="integer", example=15),
    *                 @OA\Property(property="booking_date", type="string", format="date", example="2026-02-15"),
    *                 @OA\Property(property="weekday", type="string", example="Monday"),
    *                 @OA\Property(property="slot_time", type="string", example="14:00"),
    *                 @OA\Property(property="appointment_type", type="string", example="video"),
    *                 @OA\Property(property="notes", type="string", nullable=true, example="Patient has fever"),
    *                 @OA\Property(property="amount", type="number", format="float", example=500),
    *                 @OA\Property(property="status", type="string", example="completed"),
    *
    *                 @OA\Property(
    *                     property="doctor",
    *                     type="object",
    *                     nullable=true,
    *                     @OA\Property(property="id", type="integer", example=3),
    *                     @OA\Property(property="name", type="string", example="Dr. Amit Sharma"),
    *                     @OA\Property(property="specialist", type="string", example="Cardiologist")
    *                 ),
    *
    *                 @OA\Property(
    *                     property="customer",
    *                     type="object",
    *                     nullable=true,
    *                     @OA\Property(property="id", type="integer", example=5),
    *                     @OA\Property(property="name", type="string", example="Rahul Sharma")
    *                 ),
    *
    *                 @OA\Property(
    *                     property="prescription_notes",
    *                     type="string",
    *                     nullable=true,
    *                     example="Take medicines twice daily"
    *                 ),
    *
    *                 @OA\Property(
    *                     property="prescription_images",
    *                     type="array",
    *                     nullable=true,
    *                     @OA\Items(
    *                         @OA\Property(
    *                             property="image_url",
    *                             type="string",
    *                             example="https://example.com/storage/prescriptions/image1.jpg"
    *                         )
    *                     )
    *                 )
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation error"
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Post(
    * path="/healinghub/api/upload-prescription-by-doctor",
    * summary="Upload prescription by doctor and mark appointment as completed",
    * description="Doctor uploads prescription images for an appointment and marks it as completed.",
    * operationId="uploadPrescriptionByDoctor",
    * tags={"Doctor API"},
    * security={{"passport": {}}},
    *
    * @OA\RequestBody(
    * required=true,
    * @OA\MediaType(
    * mediaType="multipart/form-data",
    * @OA\Schema(
    * required={"appointment_id","images[]"},
    * * @OA\Property(
    * property="appointment_id",
    * type="integer",
    * example=15,
    * description="ID of the appointment"
    * ),
    *
    * @OA\Property(
    * property="notes",
    * type="string",
    * nullable=true,
    * example="Take medicine twice daily after meals"
    * ),
    *
    * @OA\Property(
    * property="images[]",
    * description="Prescription images (jpg, jpeg, png)",
    * type="array",
    * @OA\Items(
    * type="string",
    * format="binary"
    * )
    * )
    * )
    * )
    * ),
    *
    * @OA\Response(
    * response=200,
    * description="Prescription uploaded successfully",
    * @OA\JsonContent(
    * @OA\Property(property="success", type="boolean", example=true),
    * @OA\Property(property="message", type="string", example="Prescription uploaded and appointment completed successfully."),
    * @OA\Property(property="data", type="object")
    * )
    * ),
    *
    * @OA\Response(
    * response=400,
    * description="Validation error",
    * @OA\JsonContent(
    * @OA\Property(property="success", type="boolean", example=false),
    * @OA\Property(property="message", type="string", example="The appointment id field is required.")
    * )
    * ),
    *
    * @OA\Response(
    * response=401,
    * description="Unauthorized"
    * ),
    *
    * @OA\Response(
    * response=403,
    * description="Only doctors can upload prescriptions"
    * )
    * )
    * @OA\Post(
    *     path="/healinghub/api/calculate-earnings-for-doctor",
    *     operationId="calculateDoctorEarnings",
    *     tags={"Doctor API"},
    *     summary="Calculate Doctor Earnings",
    *     description="Returns completed appointments list and total earnings for authenticated doctor. Supports monthly (by month name) and yearly filters.",
    *     security={{"passport": {}}},
    *
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"filter_type","year"},
    *             @OA\Property(
    *                 property="filter_type",
    *                 type="string",
    *                 enum={"monthly","yearly"},
    *                 example="monthly"
    *             ),
    *             @OA\Property(
    *                 property="month",
    *                 type="string",
    *                 description="Required if filter_type is monthly. Month name (January, February...)",
    *                 example="January"
    *             ),
    *             @OA\Property(
    *                 property="year",
    *                 type="integer",
    *                 example=2026
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Doctor earnings fetched successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Doctor earnings fetched successfully."),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(
    *                     property="completed_appointments",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="customer_name", type="string", example="Rahul Sharma"),
    *                         @OA\Property(property="appointment_date", type="string", example="2026-01-12"),
    *                         @OA\Property(property="appointment_time", type="string", example="14:00"),
    *                         @OA\Property(property="amount", type="number", format="float", example=500)
    *                     )
    *                 ),
    *                 @OA\Property(property="total_completed_income", type="number", format="float", example=1200),
    *                 @OA\Property(property="total_completed_count", type="integer", example=2)
    *             )
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=400,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="The filter_type field is required.")
    *         )
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/order/{id}",
    *     summary="Get Order Details",
    *     description="Fetch single order details with user, address, and order items",
    *     operationId="getOrderDetails",
    *     tags={"Customer API"},
    *     security={{"passport": {}}},
    *
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="Order ID",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Successful response"
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     ),
    *
    *     @OA\Response(
    *         response=404,
    *         description="Order not found"
    *     )
    * )
    * @OA\Get(
    *     path="/healinghub/api/my-prescriptions",
    *     summary="Get Customer Prescription List",
    *     description="Fetch logged-in customer's prescription list",
    *     operationId="getMyPrescriptions",
    *     tags={"Customer API"},
    *     security={{"passport": {}}},
    *
    *     @OA\Response(
    *         response=200,
    *         description="Prescription list fetched successfully"
    *     ),
    *
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated"
    *     ),
    *
    *     @OA\Response(
    *         response=404,
    *         description="No prescription found"
    *     )
    * )
    */
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => 1,
            'message' => $message,
            'data'    => (!empty($result)) ? $result : (object) $result
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => 0,
            'message' => $error,
        ];
        // if(!empty($errorMessages)){
            $response['data'] = (!empty($errorMessages)) ? $errorMessages : (object) $errorMessages;
        // }
        return response()->json($response, $code);
    }
}
