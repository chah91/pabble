<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\Thread;
use App\Models\Vote;
use App\Models\User;
use Validator;
use Hash;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name, Request $request, Thread $thread, Post $post, User $user, Vote $vote, Subscription $subscription)
    {
        $user = $user->where('username', $name)->first();

        $sort = $request->segment(3);
        if (!$sort) {
            $sort = 'new';
        }
        $page = $request->input('page');
        if (!$page || !is_numeric($page)) {
            $page = 1;
        }
        $skip = 25 * $page - 25;

        $comments = null;
        $posts = null;
        $subscriptions = null;
        if ($user) {
            $comments = $post->postsbyUser($user->id, $sort, $skip, 25);
            $posts = $thread->threadsByUser($user->id, $sort, $skip, 25);
            $subscriptions = $subscription->subscriptions($user->id);
        }
        $userVotes = null;
        if (Auth::check() && $user) {
            $auth_user = Auth::user();
            $threadsArray = $posts->pluck('id')->toArray();
            $postsArray = $comments->pluck('id')->toArray();

            $thread_votes = $vote->where('user_id', $auth_user->id)->whereIn('thread_id', $threadsArray)->get();
            $post_votes = $vote->where('user_id', $auth_user->id)->whereIn('post_id', $postsArray)->get();
            $userVotes = $thread_votes->merge($post_votes);
    }

        return view('profile', array('sort' => $sort, 'user' => $user, 'posts' => $posts, 'comments' => $comments, 'userVotes' => $userVotes, 'page' => $page, 'subscriptions' => $subscriptions));
    }

    public function sortPackage($sort, $collection)
    {
        if ($sort == 'new') {
            return $collection->sortBy('created_at');
        } else if ($sort == 'popular' || $sort == 'top') {
            return $collection->sortBy('score');
        } else {
            return false;
        }
    }

    public function resetPasswordShow(){
        return view('auth.changePassword');
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password' => "required",
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->new_password !== $request->confirm_password){
            return back()->withErrors(['new_password' => 'Confirm Password does not match'])->withInput();;
        }

        $auth_user = Auth::user();

        if (Hash::check($validator->validated()['current_password'], $auth_user->password)){
            $auth_user->password = bcrypt($request->new_password);
            $auth_user->save();
            flash('Password changed successfully', 'success');
            return \redirect('/u/'.$auth_user->username);
        }
        else{
            return back()->withErrors(['current_password' => 'Current Password is incorrect'])->withInput();;
        }

    }

    public function resetEmailShow(){
        return view('auth.changeEmail');
    }

    public function resetEmail(Request $request){
        $validator = Validator::make($request->all(), [
            'current_email' => "required",
            'new_email' => 'required',
            'confirm_email' => 'required'
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->new_email !== $request->confirm_email){
            return back()->withErrors(['new_email' => 'Confirm Email Address does not match'])->withInput();
        }

        $auth_user = Auth::user();

        if ($auth_user->email === $request['current_email']){
            $auth_user->email = $request['new_email'];
            if (env('EMAIL_ACTIVATION')) {
                $auth_user->active = false;
                // send email activation link
            }
            $auth_user->save();
            flash('Email Address changed successfully', 'success');
            return \redirect('/u/'.$auth_user->username);
        }
        else{
            return back()->withErrors(['current_email' => 'Current Email Address is incorrect'])->withInput();
        }

    }
}
