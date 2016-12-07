<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SoapboxController extends Controller
{

	public function __construct() {}

    public function index()
    {
        return view('soapbox')->with('cordova', false);
    }

    public function authenticate(Request $request)
    {
		$credentials = $request->input();

		try {
			if (! $token = JWTAuth::attempt($credentials)) {
				return $this->authFailed();
			}
		} catch (JWTException $e) {
			return $this->authFailed('Could not create token');
		}

		return response()->json(compact('token'));
    }

    public function authFailed($message = 'Invalid email or invitation code')
    {
        return response()->json([
            'authenticated' => false,
            'message' => $message
        ], 401);
    }
}
