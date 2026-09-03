<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginDataController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'shozokuid' => 'required',
            'tantoshaname' => 'required|string|max:255',
        ]);

        DB::table('logindata')->insert([
            'shozokuid' => $request->shozokuid,
            'tantoshaname' => $request->tantoshaname,
            'logindt' => now(),
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
