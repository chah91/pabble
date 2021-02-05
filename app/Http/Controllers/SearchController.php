<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\SubPabble;
use App\Models\Thread;
use App\Models\Vote;

class SearchController extends Controller
{
    public function search($subpabble = null, Request $request, SubPabble $subPabble, Thread $thread, Vote $vote)
    {
        $page = $request->input('page');
        if (!is_numeric($page)) {
            $page = 1;
        }
        $rpage = $page;
        $sort = $request->input('sort');
        $query = $request->input('q');
        $type = $request->input('searchType');
        if ($subpabble) {
            $take = 25;
            $skip = $page * $take - $take;
            $subpabble = $subPabble->where('name', $subpabble)->first();
            if ($subpabble) {
                $subpabble_id = $subpabble->id;
            } else {
                $subpabble_id = null;
            }
            if (isset($sort) && $sort === 'new'){
                $threads = $thread->where('sub_pabble_id', $subpabble_id)->where('title', 'LIKE', '%' . $query . '%')->orderBy('created_at', 'DESC')->skip($skip)->take($take)->get();
            }
            else{
                $threads = $thread->where('sub_pabble_id', $subpabble_id)->where('title', 'LIKE', '%' . $query . '%')->orderBy('title', 'asc')->skip($skip)->take($take)->get();
            }
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

        if ($type == 'pabble') {
            if (isset($sort) && $sort === 'new'){
                $subpabbles = $subPabble->select('id', 'name', 'title', 'created_at')->where('name', 'LIKE', '%' . $query . '%')->orderBy('created_at', 'DESC')->skip($skip)->take($take)->get();
            }
            else $subpabbles = $subPabble->select('id', 'name', 'title', 'created_at')->where('name', 'LIKE', '%' . $query . '%')->orderBy('name', 'asc')->skip($skip)->take($take)->get();
        } else {
            $subpabbles = collect(new SubPabble());
        }
        if ($type == 'post') {
            $threads = $thread->where('title', 'LIKE', '%' . $query . '%')->skip($skip)->take($take);
            if (isset($sort)){
                switch ($sort) {
                    case 'popular':
                        $threads = $threads->orderBy('score', 'DESC')->get();
                        break;
                    case 'new':
                        $threads = $threads->orderBy('created_at', 'DESC')->get();
                        break;
                    case 'top':
                        $threads = $threads->orderBy('score', 'DESC')->get();
                        break;
                }
            }
            else {
                $threads = $threads->orderBy('title', 'asc')->get();
            }
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
            'q' => $query,
            'currentSearchType' => $type
        ]);
    }
}
