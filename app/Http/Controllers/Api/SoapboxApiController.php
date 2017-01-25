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
use App\Audio;
use App\Mail\Invitation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class SoapboxApiController extends Controller
{

    public function handleAudioUpload(Request $request)
    {
        $validator = Validator::make(
            $request->file(),
            [
                'audio' => 'bail|required|mimetypes:audio/mp4,audio/mpeg,audio/ogg,audio/x-aac,audio/x-mpegurl,audio/x-wav'
            ],
            [
                'audio.mimetypes' => "Please submit your audio as a <br> <span>wav</span> or <span>mp3</span> file."
            ]
        );


        if ($validator->fails() && $request->input('extension') !== 'ogg') {
            return response()->json(
                ['message' => $validator->errors()->first()], 400
            );
        } else {
            $file = $request->file('audio');
            $ext = $request->input('extension');
            $user = Auth::user();
            $disk = Storage::disk('audio');
            $ffprobe = env('FFPROBE_PATH', '/usr/bin/ffprobe');
            $filename = str_random(12) . '.' . $ext;
            $fullPath = $disk->getDriver()->getAdapter()->getPathPrefix() . $filename;
            
            if ($request->input('removePrevious')) {
                $this->deleteUserAudio();
            }
            
            $disk->putFileAs('/', $file, $filename);
            
            Artisan::call('getAudioDuration', ['path' => $fullPath]);
            $duration = Artisan::output();

            $audio = new Audio();
            $audio->filename = $filename;
            $audio->duration = $duration;
            $user->audio()->save($audio);

            // combine this file with any others that already exist
            Artisan::call('concatAudioFiles', ['id' => $user->id]);

            $result = $user->audio()->firstOrFail();

            return response()->json([
                'url' => '/audio/' . $result->filename,
                'duration' => $result->duration,
                'message' => 'Upload Complete'
            ]);
        }
    }

    public function deleteUserAudio()
    {
        $user = Auth::user();
        $disk = Storage::disk('audio');

        $userAudio = $user->audio();

        $userAudio->each(function ($audio) use ($disk) {
            $disk->delete($audio->filename);
            $audio->delete();
        });

        return response()->json([
            'message' => 'Audio Deleted'
        ]);
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

    public function handleSubmission(Request $request)
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

    public function saveSubmission($data)
    {
        $user = Auth::user();
        $user->name = $data['info']['name'];
        $user->title = $data['info']['title'];
        $user->institution = $data['info']['institution'];
        $user->save();

        $submission = new Submission();
        $submission->title = $data['nominees']['submissionTitle'];
        $user->submission()->save($submission);

        unset($data['nominees']['submissionTitle']);

        foreach ($data['nominees'] as $email) {
            $this->createUser($email, $user->id);
        }
    }

    protected function getSubmissionValidator(Request $request)
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

    protected function createUser($email, $parentId)
    {
        $user = new User();
        $user->email = $email;
        $user->parent_id = $parentId;
        $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));

        $user->code = str_random(8);
        $user->password = bcrypt($user->code);

        $user->save();
        $user->attachRole(env('ROLE_ID_NORMALUSER'));

        $this->sendInvitationEmail($user, $user->code);
    }

    protected function sendInvitationEmail($nominee)
    {
        $invitation = new Invitation(Auth::user(), $nominee, $nominee->code);
        Mail::to($nominee)->send($invitation);
    }
}
