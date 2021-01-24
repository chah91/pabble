<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\SubPabble;

class SearchSubPabblesController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($query, SubPabble $subPabble)
    {
        $results = $subPabble->searchByName($query)->toArray();

        return response()->json(
           $results
        );
    }
}
