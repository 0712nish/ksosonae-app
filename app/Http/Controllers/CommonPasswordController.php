<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonPasswordController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'max:1024'],
        ]);

        $commonPassword = config('admin.common_password');

        if (
            ! is_string($commonPassword) ||
            $commonPassword === '' ||
            ! hash_equals(
                $commonPassword,
                $request->input('password')
            )
        ) {
            return response()->json([
                'message' => 'パスワードが違います',
            ], 401);
        }

        return response()->json([
            'ok' => true,
        ]);
    }
}