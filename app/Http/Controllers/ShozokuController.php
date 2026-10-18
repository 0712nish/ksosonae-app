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

        if (!$data) {
            return response()->json([
                'message' => '所属が見つかりません',
            ], 404);
        }

        // パスワードはブラウザに返さない
        unset($data->pass);

        return response()->json($data);
    }
    
    public function login(Request $request)
    {
        $validated = $request->validate([
            'sname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:1024'],
            'tantoshaname' => ['required', 'string', 'max:255'],
        ]);

        $data = DB::table('shozokumst')
            ->where('name', $validated['sname'])
            ->first();

        if (!$data) {
            return response()->json([
                'message' => '所属またはパスワードが違います',
            ], 401);
        }

        $inputPassword = $validated['password'];
        $commonPassword = config('admin.common_password');
        $departmentPassword = $data->pass ?? null;

        // 共通パスワードまたは所属別パスワードを照合
        $commonMatch = is_string($commonPassword)
            && $commonPassword !== ''
            && hash_equals($commonPassword, $inputPassword);

        $departmentMatch = is_string($departmentPassword)
            && $departmentPassword !== ''
            && hash_equals($departmentPassword, $inputPassword);

        if (!$commonMatch && !$departmentMatch) {
            return response()->json([
                'message' => '所属またはパスワードが違います',
            ], 401);
        }

        // パスワードを含めず必要な情報だけ返す
        return response()->json([
            'no' => $data->no,
            'reigaiflg' => $data->reigaiflg,
            'kaigaiflg' => $data->kaigaiflg,
            'tantoshaname' => $validated['tantoshaname'],
        ]);
    }
}