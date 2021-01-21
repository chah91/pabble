<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subPabble;
use App\Models\Thread;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search($subpabble = null, Request $request, subPabble $subPabble, Thread $thread, Vote $vote)
    {
        $page = $request->input('page');
        if (!is_numeric($page)) {
            $page = 1;
        }
        $rpage = $page;

        $query = $request->input('q');
        if ($subpabble) {
            $take = 25;
            $skip = $page * $take - $take;
            $subpabble = $subPabble->where('name', $subpabble)->first();
            if ($subpabble) {
                $subpabble_id = $subpabble->id;
            } else {
                $subpabble_id = null;
            }
            $threads = $thread->where('sub_pabble_id', $subpabble_id)->where('title', 'LIKE', '%' . $query . '%')->orderBy('title', 'asc')->skip($skip)->take($take)->get();
            $userVotes = null;
            if (Auth::check()) {
                $threads_ids_array = $threads->pluck('id')->toArray();
                $userVotes = $vote->where('user_id', Auth::user()->id)->whereIn('thread_id', $threads_ids_array)->get();
            }

            return view('search.results_subpabble')->with([
                'threads' => $threads,
                'subPabble' => $subpabble,
                'userVotes' => $userVotes,
                'page' => $page
            ]);
        }

        if ($page == 1) {
            $skip = 0;
            $take = 5;
        } else if ($page == 2) {
            $skip = 5;
            $take = 20;
        } else {
            $page = $page - 1;
            $skip = 25 * $page - 25;
            $take = 25;
        }

        $type = $request->input('type');
        if ($type !== 'posts' && $type !== 'subpabbles') {
            $type = 'all';
        }

        if ($type == 'all' || $type == 'subpabbles') {
            $subpabbles = $subPabble->select('id', 'name', 'title', 'created_at')->where('name', 'LIKE', '%' . $query . '%')->orderBy('name', 'asc')->skip($skip)->take($take)->get();
        } else {
            $subpabbles = collect(new subPabble());
        }
        if ($type == 'all' || $type == 'posts') {
            $threads = $thread->where('title', 'LIKE', '%' . $query . '%')->orderBy('title', 'asc')->skip($skip)->take($take)->get();
        } else {
            $threads = collect(new Thread());
        }
        $userVotes = null;
        if (Auth::check()) {
            $threads_ids_array = $threads->pluck('id')->toArray();
            $userVotes = $vote->where('user_id', Auth::user()->id)->whereIn('thread_id', $threads_ids_array)->get();
        }
        return view('search.results_global')->with([
            'threads' => $threads,
            'subpabbles' => $subpabbles,
            'userVotes' => $userVotes,
            'page' => $rpage,
            'q' => $query
        ]);
    }
}
