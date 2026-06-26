<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditTableController extends Controller
{
    public function index()
    {
        $data = DB::table('editdate')
            ->orderBy('no')
            ->get();

        return response()->json($data);
    }
}
