<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        $disk = Storage::disk('audio');

        $userAudio = $user->audio();

        $userAudio->each(function ($audio) use ($disk) {
            $disk->delete($audio->filename);
            $audio->delete();
        });

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
