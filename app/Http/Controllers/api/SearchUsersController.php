<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Subscription;

class SearchUsersController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($query, Request $request, User $user)
    {
        $results = $user->searchByName($query)->toArray();

        return Response()->json(
            $results
        , 200);
    }

}
