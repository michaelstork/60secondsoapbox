<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;
use App\Submission;
use App\Mail\Invitation;

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
           'nominees.nominee1' => 'bail|required|email',
           'nominees.nominee2' => 'bail|required|email',
           'nominees.nominee3' => 'bail|required|email'
        ]);
    }

    public function saveSubmission($data)
    {
        $user = Auth::user();
        $user->name = $data['info']['name'];
        $user->title = $data['info']['title'];
        $user->institution = $data['info']['institution'];
        $user->save();

        $submission = new Submission();
        $submission->title = $data['nominees']['submissionTitle'];
        $submission->filename = $data['audio']['filename'];
        $user->submission()->save($submission);

        unset($data['nominees']['submissionTitle']);

        foreach ($data['nominees'] as $email) {
            $this->createUser($email);
        }
    }

    public function createUser($email)
    {
        $user = new User();
        $user->email = $email;

        $code = $this->generateCode(8);
        $user->password = bcrypt($code);

        $user->save();
        $user->attachRole(env('ROLE_ID_NORMALUSER'));

        $this->sendInvitationEmail($user, $code);
    }

    protected function sendInvitationEmail($nominee, $code)
    {
        $invitation = new Invitation(Auth::user(), $nominee, $code);
        Mail::to($nominee)->send($invitation);
    }

    protected function generateCode($length)
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
