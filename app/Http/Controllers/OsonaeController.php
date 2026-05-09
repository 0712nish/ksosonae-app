<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OsonaeController extends Controller
{
    public function index(Request $request)
    {
        $sname = $request->query('sname');
        $year  = $request->query('year');

        $data = DB::table('v_osonaedata')
            ->where('sname', $sname)
            ->where('year', $year)
            ->where('no', '<>', 0)
            ->orderBy('no')
            ->get();

        return response()->json($data);
    }
}