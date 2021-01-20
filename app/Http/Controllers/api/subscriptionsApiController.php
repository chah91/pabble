<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\subPabble;
use App\Subscription;
use Illuminate\Support\Facades\Auth;

class subscriptionsApiController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe($name, Request $request, subPabble $subPabble, Subscription $subscription)
    {
        $subPabble = $subPabble->select('name', 'id')->where('name', $name)->first();
        if (!$subPabble) {
            return Response()->json([
               'error' => "subPabble doesn't exist"
            ], 404);
        }

        $user = Auth::guard('api')->user();

        $sub = $subscription->where('user_id', $user->id)->where('sub_pabble_id', $subPabble->id)->first();
        if (!$sub) {
            $sub = new Subscription();
            $sub->user_id = $user->id;
            $sub->sub_pabble_id = $subPabble->id;
            $sub->save();
        }

        return Response()->json([
            'status' => 'success',
            'sub_pabble' => $subPabble->name
        ], 200);
    }

    public function unsubscribe($name, Request $request, subPabble $subPabble, Subscription $subscription)
    {
        $subPabble = $subPabble->select('name', 'id')->where('name', $name)->first();
        if (!$subPabble) {
            return Response()->json([
                'error' => "subPabble doesn't exist"
            ], 404);
        }

        $user = Auth::guard('api')->user();

        $sub = $subscription->where('user_id', $user->id)->where('sub_pabble_id', $subPabble->id)->first();
        if ($sub) {
            $sub->delete();
        }

        return Response()->json([
            'status' => 'success',
            'sub_pabble' => $subPabble->name
        ], 200);
    }
}
