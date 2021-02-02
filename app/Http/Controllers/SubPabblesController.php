<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Moderator;
use App\Models\SubPabble;
use App\Models\Thread;
use App\Models\Vote;
use App\Models\Subscription;

class SubPabblesController extends Controller
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


    public function subPabble($name, Request $request, SubPabble $subPabble, Thread $thread, Vote $vote, Subscription $subscription, Moderator $moderator)
    {
        $subPabble = $subPabble->where('name', $name)->first();

        $page = $request->input('page');
        if (!is_numeric($page)) {
            $page = 1;
        }

        if (!$subPabble) {
            return view('subPabbles.subPabble', array('subPabble' => $subPabble));
        }

        $readers = $subscription->where('sub_pabble_id', $subPabble->id)->count();

        $sort = $request->segment(3);
        if (!$sort) {
            $threads = $thread->where('sub_pabble_id', $subPabble->id)->where('created_at', '>=', \Carbon\Carbon::now()->subDay(7))->take(25)->orderBy('score', 'DESC');
        } else if ($sort == 'new') {
            $threads = $thread->where('sub_pabble_id', $subPabble->id)->orderBy('created_at', 'DESC')->take(25);
        } else if ($sort == 'top') {
            $threads = $thread->where('sub_pabble_id', $subPabble->id)->orderBy('score', 'DESC')->take(25);
        } else if ($sort == 'shekeld') {
            //coming soon
            $threads = null;
        } else {
            $threads = null;
        }

        $userVotes = null;
        $subscribed = null;
        if ($threads) {
            if ($page) {
                $threads = $threads->skip(25 * $page - 25);
            }
            $threads = $threads->get();

            $threadsArray = $threads->pluck('id')->toArray();
            if (Auth::check()) {
                $user = Auth::user();
                $subscribed = $subscription->subscribed($user->id, $subPabble->id);
                $userVotes = $vote->where('user_id', $user->id)->whereIn('thread_id', $threadsArray)->get();
            }
        }

        return view('subPabbles.subPabble', array(
            'subPabble' => $subPabble,
            'threads' => $threads,
            'userVotes' => $userVotes,
            'sort' => $sort,
            'subscribed' => $subscribed,
            'readers' => $readers,
            'moderators' => $moderator->getBySubPabbleId($subPabble->id))
        );
    }

    public function index(Request $request){
        $search = $request->input('search');
        if ($search){
            $subpabbles = SubPabble::where('name', 'LIKE', '%' . $search . '%')->orderBy('name', 'asc')->paginate(10);
        }
        else {
            $subpabbles = SubPabble::orderBy('name', 'asc')->paginate(10);
        }
        return view('subPabbles.all', array(
            'subpabbles' => $subpabbles
        ));
    }

}
