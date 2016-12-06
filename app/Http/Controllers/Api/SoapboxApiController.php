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
            'audioUpload' => 'bail|required|mimetypes:audio/mp4,audio/mpeg,audio/ogg,audio/x-aac,audio/x-mpegurl,audio/x-wav'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0]], 400);
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
        $validator = Validator::make($request->input(), [
            'email' => 'bail|required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0]], 400);
        }

        $email = $request->input('email');
        
        try {
            $user = User::where('email', $email)->firstOrFail();

            return response()->json([
                'message' => 'Already nominated!'
            ], 400);

        } catch (ModelNotFoundException $e) {
            return response()->json(['email' => $email]);
        }
    }

    public function submission(Request $request)
    {
        $validator = $this->getSubmissionValidator($request);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0]], 400);
        }

        $this->saveSubmission($request->input());

        return response()->json([
            'message' => 'Thanks for participating!'
        ], 200);
    }

    public function getSubmissionValidator(Request $request)
    {
        return Validator::make($request->input(), [
           'auth.email' => 'bail|required|email',
           'info.name' => 'required',
           'info.title' => 'required',
           'info.institution' => 'required',
           'audio.filename' => 'required',
           'nominees.submissionTitle' => 'required',
           'nominees.nominee1' => 'bail|required|email|unique:users,email',
           'nominees.nominee2' => 'bail|required|email|unique:users,email',
           'nominees.nominee3' => 'bail|required|email|unique:users,email'
        ]);
    }

    public function saveSubmission($input)
    {
            
    }

}
