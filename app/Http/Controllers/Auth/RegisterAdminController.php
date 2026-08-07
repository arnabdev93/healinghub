<?php



namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Str;

use Illuminate\Foundation\Auth\RegistersUsers;



// use Spatie\Permission\Models\Role;

class RegisterAdminController extends Controller

{

    use RegistersUsers;

    public function __construct()

    {

        // $this->middleware('guest');

        $this->middleware('guest');

    }

     /**

     * Display the registration view.

     *

     * @return \Illuminate\View\View

     */

    public function create()

    {
        return redirect()->route('login');
        return view('auth.register');

    }

    /**

     * Handle an incoming registration request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\RedirectResponse

     *

     * @throws \Illuminate\Validation\ValidationException

     */

    

    public function showAdminRegisterForm()

    {

        return view('auth.register');

    }



    protected function createAdmin(Request $request)

    {

        // $this->validator($request->all())->validate();

        // echo "<pre>";

        // print_r($request->all());exit;

        $request->validate([

            'name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],

            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);

        $nameParts = explode(' ', trim($request->name));

        $first_name = array_shift($nameParts); // First element

        $last_name = implode(' ', $nameParts); // Everything else



        $user = new User;

        $user->name = $first_name;

        $user->email = $request->email;

        $user->mobile = '1111111111';

        $user->password = Hash::make($request->password);

        $user->role = "admin";

        $user->save();



        Auth::login($user);

        return redirect()->route('home');

    }

}

