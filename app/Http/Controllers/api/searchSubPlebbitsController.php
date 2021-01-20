<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\subPabble;

class searchSubPabblesController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($query, subPabble $subPabble)
    {
        $results = $subPabble->searchByName($query)->toArray();

        return response()->json(
           $results
        );
    }
}
