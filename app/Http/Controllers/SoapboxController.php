<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Content;

class SoapboxController extends Controller
{

	public function __construct() {}

    public function index()
    {
        $panelContent = Content::whereIn('name', ['auth', 'audio'])->get();

        return view('soapbox')->with([
            'cordova' => false,
            'panelContent' => $panelContent
        ]);
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
