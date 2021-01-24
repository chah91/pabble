<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Models\Moderator;
use App\Models\Thread;
use App\Models\Post;
use App\Models\SubPabble;

class ModerationController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteThread($code, Request $request, Thread $thread, SubPabble $subPabble, Moderator $moderator)
    {
        $user = Auth::guard('api')->user();

        $thread = $thread->where('code', $code)->first();
        if (!$thread) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thread not found'
            ], 200);
        }

        $sub_pabble = $subPabble->select('id', 'owner_id')->where('id', $thread->sub_pabble_id)->first();
        if (!$subPabble) {
            return response()->json([
                'status' => 'error',
                'message' => 'subPabble not found'
            ], 200);
        }

        $mod = $moderator->isMod($user->id, $sub_pabble);
        if (!$mod) {
            return response()->json([
               'status' => 'error',
               'message' => "You are not allowed to moderate this subPabble"
            ]);
        }

        $thread->type = 'text';
        $thread->link = null;
        $thread->media_type = null;
        $thread->thumbnail = null;
        $thread->post = 'Deleted';
        $thread->save();

        return response()->json([
           'status' => 'success'
        ]);
    }

    public function deleteComment($id,  Request $request, Post $post, SubPabble $subPabble, Moderator $moderator, Thread $thread)
    {
        $user = Auth::guard('api')->user();

        $post = $post->where('id', $id)->first();
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found'
            ], 200);
        }
        $thread = $thread->where('id', $post->thread_id)->first();

        $sub_pabble = $subPabble->select('id', 'owner_id')->where('id', $thread->sub_pabble_id)->first();
        if (!$subPabble) {
            return response()->json([
                'status' => 'error',
                'message' => 'subPabble not found'
            ], 200);
        }

        $mod = $moderator->isMod($user->id, $sub_pabble);
        if (!$mod) {
            return response()->json([
                'status' => 'error',
                'message' => "You are not allowed to moderate this subPabble"
            ]);
        }

        $post->comment = 'Deleted';
        $post->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

}
