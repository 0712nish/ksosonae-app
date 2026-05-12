<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShozokuController extends Controller
{
    public function index(Request $request)
    {
        $sname = $request->query('sname');

        $data = DB::table('shozokumst')
            ->where('name', $sname)
            ->first();

        return response()->json($data);
    }
}