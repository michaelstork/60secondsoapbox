<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Submission;
use App\Audio;
use App\User;

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
    public function index()
    {
        $users = User::with('audio', 'submission')->get();

        $data = ['users' => $users];
        return view('dashboard')->with($data);
    }

    public function userInfo(Request $request)
    {
        $data = [
            'children' => []
        ];

        $user = User::where('id', $request->input('id'))->firstOrFail();

        // days since invited
        $datetime1 = new \DateTime($user->created_at);
        $datetime2 = new \DateTime('now');
        $interval = $datetime1->diff($datetime2);
        $data['days_since_invited'] = $interval->format('%a day(s)');

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

        // user submission
        $submission = $user->submission()->first();
        $audio = $user->audio()->first();
        if ($submission && $audio) {
            $submissionData = [
                'title' => $submission->title,
                'filename' => $audio->filename
            ];

            $data['submission'] = $submissionData;
        }

        return response()->json($data);
    }
}
