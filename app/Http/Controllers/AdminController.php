<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Invitation;
use App\Submission;
use App\Audio;
use App\User;
use App\Content;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'adminRole']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('audio', 'submission')->where('email', '<>', 'admin@60secondsoapbox.io')->get();
        $content = Content::all();

        $createUserMessage = $request->session()->get('createUserMessage');

        $data = [
            'users' => $users,
            'content' => $content,
            'createUserMessage' => $createUserMessage
        ];
        return view('dashboard')->with($data);
    }

    public function saveContent(Request $request)
    {
        foreach ($request->input() as $field => $value) {
            $content = Content::where('name', $field)->first();

            if (!$content) {
                continue;
            }

            if ($content->type === 'html') {
                $content->content = preg_replace('*<p><br></p>*i', '', $value);
            } else {
                $content->content = trim($value);
            }

            $content->save();
        }

        return redirect()->route('dashboard', ['admin' => 'dashboard']);
    }

    public function userInfo(Request $request)
    {
        $data = [
            'children' => []
        ];

        $user = User::where('id', $request->input('id'))->firstOrFail();

        // days since nominated
        $datetime1 = new \DateTime($user->created_at);
        $datetime2 = new \DateTime('now');
        $interval = $datetime1->diff($datetime2);
        $data['days_since_invited'] = $interval->format('%a');

        // days since last invited
        $datetime3 = new \DateTime($user->last_invited);
        $interval2 = $datetime3->diff($datetime2);
        $data['days_since_last_invited'] = $interval2->format('%a');

        // invited by
        $parent = User::where('id', $user->parent_id)->first();
        if ($parent) {
            $parentData = [
                'id' => $parent->id,
                'name' => $parent->name,
                'email' => $parent->email
            ];
            $data['parent'] = $parentData;
        }

        // user nominees
        $children = User::where('parent_id', $request->input('id'))->get();
        foreach ($children as $child) {
            $submission = $child->submission()->first();
            $data['children'][] = [
                'id' => $child['id'],
                'email' => $child['name'],
                'email' => $child['email'],
                'submission' => $submission
            ];
        }

        if (!$user->hasRole('admin')) {
            $data['code'] = $user->code;
        }

        return response()->json($data);
    }

    public function resendInvitation (Request $request)
    {
        $recipient = User::where('id', $request->input('id'))->firstOrFail();

        $nominator = User::where('id', $recipient->parent_id)->first();
        if (!$nominator) {
            $nominator = Auth::user();
            $recipient->parent_id = $nominator->id;
        }

        $this->sendInvitationEmail($nominator, $recipient);
        
        $recipient->last_invited = date('Y-m-d H:i:s', strtotime('now'));
        $recipient->save();

        $data = [];
        $data['last_invited'] = $recipient->last_invited;

        $lastInvitedDatetime = new \DateTime($recipient->last_invited);
        $nowDatetime = new \DateTime('now');
        $interval = $lastInvitedDatetime->diff($nowDatetime);
        $data['days_since_last_invited'] = $interval->format('%a');

        return response()->json($data);
    }

    protected function sendInvitationEmail ($nominator, $recipient)
    {
        $invitation = new Invitation($nominator, $recipient, $recipient->code);
        Mail::to($recipient)->send($invitation);
    }

    public function createUser (Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $user = new User();
            $user->email = $request->input('email');
            $user->parent_id = Auth::user()->id;

            $user->code = str_random(8);
            $user->password = bcrypt($user->code);
            $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
            $user->attachRole(env('ROLE_ID_NORMALUSER'));

            $this->sendInvitationEmail(Auth::user(), $user);

            $request->session()->flash('createUserMessage', 'User Invited');

        } else {
            $request->session()->flash('createUserMessage', 'User already exists');
        }


        return redirect()->route('dashboard', ['admin' => 'dashboard']);
    }

    public function deleteUser (Request $request)
    {
        $user = User::where('id', $request->input('id'))->first();
        $disk = Storage::disk('audio');

        $userAudio = $user->audio();

        $userAudio->each(function ($audio) use ($disk) {
            $disk->delete($audio->filename);
            $audio->delete();
        });

        $user->delete();

        return response()->json([
            'message' => 'User Deleted'
        ]);
    }
}
