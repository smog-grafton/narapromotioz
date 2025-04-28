<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Fighter;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_fighter' => ['sometimes', 'boolean'],
        ];

        // Add fighter validation if user is registering as a fighter
        if (isset($data['is_fighter']) && $data['is_fighter']) {
            if (isset($data['existing_fighter_id']) && $data['existing_fighter_id']) {
                $rules['existing_fighter_id'] = ['required', 'exists:fighters,id'];
            } else {
                $rules['first_name'] = ['required', 'string', 'max:255'];
                $rules['last_name'] = ['required', 'string', 'max:255'];
                $rules['nickname'] = ['nullable', 'string', 'max:255'];
                $rules['date_of_birth'] = ['required', 'date', 'before:today'];
                $rules['country'] = ['required', 'string', 'max:100'];
                $rules['gender'] = ['required', 'in:male,female'];
            }
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $isFighter = isset($data['is_fighter']) && $data['is_fighter'];

        // Start a database transaction
        return DB::transaction(function () use ($data, $isFighter) {
            // Create the user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'is_fighter' => $isFighter,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'country' => $data['country'] ?? null,
            ]);

            // If registering as a fighter
            if ($isFighter) {
                // Link to existing fighter profile
                if (isset($data['existing_fighter_id']) && $data['existing_fighter_id']) {
                    $fighter = Fighter::findOrFail($data['existing_fighter_id']);
                    $fighter->user_id = $user->id;
                    $fighter->verification_status = Fighter::VERIFICATION_STATUS_PENDING;
                    $fighter->save();
                }
                // Create new fighter profile
                else {
                    $fighter = Fighter::create([
                        'user_id' => $user->id,
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'nickname' => $data['nickname'] ?? null,
                        'date_of_birth' => $data['date_of_birth'],
                        'country' => $data['country'],
                        'gender' => $data['gender'],
                        'verification_status' => Fighter::VERIFICATION_STATUS_PENDING,
                        'commission_rate' => 10.00, // Default commission rate
                        'commission_earned' => 0,
                        'commission_withdrawn' => 0,
                    ]);
                }
            }

            return $user;
        });
    }

    /**
     * Show the application registration form with fighter options.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Get verified fighters without user accounts for the dropdown
        $availableFighters = Fighter::whereNull('user_id')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'nickname']);

        return view('auth.register', compact('availableFighters'));
    }

    /**
     * Handle fighter information when a user switches to fighter mode in the form.
     */
    public function fetchFighterDetails(Request $request)
    {
        $request->validate([
            'fighter_id' => 'required|exists:fighters,id',
        ]);

        $fighter = Fighter::findOrFail($request->fighter_id);

        return response()->json([
            'fighter' => [
                'id' => $fighter->id,
                'first_name' => $fighter->first_name,
                'last_name' => $fighter->last_name,
                'nickname' => $fighter->nickname,
                'date_of_birth' => $fighter->date_of_birth,
                'country' => $fighter->country,
                'gender' => $fighter->gender,
                'image_url' => $fighter->profile_image_url,
                'record' => $fighter->record,
            ]
        ]);
    }
}