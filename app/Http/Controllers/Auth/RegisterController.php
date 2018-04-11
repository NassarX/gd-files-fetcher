<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Google_Service_Drive;

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
    protected $redirectTo = '/home';

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
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
    	$createdUser = User::updateOrCreate(
    		['email' => $data['email']],
		    [
	        	'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
	        ]
        );

    	if ($createdUser) {
		    $createdUser->tokens->isEmpty()? $createdUser->tokens()->create($data)
			    : $createdUser->tokens->first()->update($data);
	    }

	    return $createdUser;
    }

	/**
	 * Redirect the user to the Google authentication page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function redirectToProvider()
	{
		return Socialite::driver('google')
		                ->scopes(['openid', 'profile', 'email', Google_Service_Drive::DRIVE_READONLY])
						->with(['access_type' => 'offline', 'prompt' => 'consent'])
		                ->redirect();
	}

	/**
	 * Obtain the user information from Google+.
	 *
	 * @param $request
	 * @return \Illuminate\Http\Response
	 */
	public function handleProviderCallback(Request $request)
	{
		$user = Socialite::driver('google')->user();

		// Set token for the Google API PHP Client
		$google_client_token = [
			'access_token' => $user->token,
			'refresh_token' => $user->refreshToken,
			'expires_in' => $user->expiresIn
		];

		// Add user info on the fly inside a request instance
		$request->request->add(
			array_merge([
				'name' => $user->name,
				'email' => $user->email,
				'password' => str_random(8)
			], $google_client_token)
		);

		return $this->register($request);
	}
}
