<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use App\User;

class SoapboxApiController extends Controller
{

    public function audioUpload(Request $request)
    {
        $date = date('Y-m-d H:i:s', strtotime('now'));

        $validator = Validator::make($request->file(), [
            'audioUpload' => 'mimetypes:audio/mp4,audio/mpeg,audio/ogg,audio/x-aac,audio/x-mpegurl,audio/x-wav'
        ]);

        if ($validator->fails()) {
            return response()->json(array_merge($validator->errors()->all(), ['extension' => $request->file('audioUpload')->extension()]));
        } else {
            $filename = $date . '.' . $request->file('audioUpload')->extension();
            $request->file('audioUpload')->move(base_path() . '/storage/app/public/', $filename);
            return response()->json([
                'filename' => $filename,
                'message' => 'Upload Complete'
            ]);
        }
    }

    public function validateNominee(Request $request)
    {
        $email = $request->input('email');

        try {
            $user = User::where('email', $email)->firstOrFail();
            return response()->json([
                'available' => false,
                'message' => 'Already Nominated!'
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'email' => $email,
                'available' => true
            ]);
        }
    }
}
