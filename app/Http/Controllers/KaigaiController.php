<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaigaiController extends Controller
{
    public function index(Request $request)
    {
        $shozokuid = $request->query('shozokuid');
        $year      = $request->query('year');

        $data = DB::table('osonaekaigaidata')
            ->where('shozokuid', $shozokuid)
            ->where('year', $year)
            ->where('no', '<>', 0)
            ->orderBy('no')
            ->get();

        return response()->json($data);
    }


    public function save(Request $request)
    {
      try {

        $row = $request->all();

        $saveData = [

            'shozokuid' => $row['shozokuid'],
            'year'      => $row['year'],
            'no'        => $row['no'],

            'excelno'   => $row['excelno'] ?? null,
            'kubetsu'   => $row['kubetsu'],
            'hinmoku'   => $row['hinmoku'],
            'chugokugo' => $row['chugokugo'] ?? '',
            'chiikimei' => $row['chiikimei'] ?? '',
            'jissiyear' => $row['jissiyear'] ?? '',
            'seisansha' => $row['seisansha'] ?? '',
            'shinjakb' => $row['shinjakb'] ?? '',
            'suryo' => $row['suryo'] ?? '',

            'tantoshaname' => $row['tantoshaname'] ?? '',

            'mark1' => $row['mark1'] ?? 0,
            'mark2' => $row['mark2'] ?? 0,
            'mark3' => $row['mark3'] ?? 0,
            'mark4' => $row['mark4'] ?? 0,
            'mark5' => $row['mark5'] ?? 0,
            'mark6' => $row['mark6'] ?? 0,
            'mark7' => $row['mark7'] ?? 0,
            'mark8' => $row['mark8'] ?? 0,

        ];


        /*
         * autonoあり
         * → UPDATE
         */

        if (!empty($row['autono'])) {

            DB::table('osonaekaigaidata')
                ->where(
                    'autono',
                    $row['autono']
                )
                ->update($saveData);

            $autono =
                $row['autono'];

        }


        /*
         * autonoなし
         * → INSERT
         */

        else {

            $autono =
                DB::table('osonaekaigaidata')
                    ->insertGetId($saveData);

        }

        // DBに保存された値を取得 
        $savedData = DB::table('osonaekaigaidata')
            ->where('autono', $autono)
            ->first([
                'updatedt',
                'tantoshaname'
            ]);

        return response()->json([
            'success' => true,
            'autono' => $autono,
            'updatedt' => $savedData?->updatedt,
            'tantoshaname' => $savedData?->tantoshaname,
        ]);

      }catch (\Throwable $e) { 
        \Log::error(
             'Kaigai save error',
              [ 'message' => $e->getMessage(),
                'request' => $request->all(),
                ]
        );
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
      }

    }

    public function lastSaved(Request $request)
    {
        $data = DB::table('osonaekaigaidata')
            ->where(
                'shozokuid',
                $request->shozokuid
            )
            ->where(
                'year',
                $request->year
            )
            ->whereNotNull('updatedt')
            ->orderByDesc('updatedt')
            ->first([
                'updatedt',
                'tantoshaname'
            ]);

        return response()->json(
            $data
        );
    }

    public function delete(Request $request)
    {
        DB::table('osonaekaigaidata')
            ->where(
                'autono',
                $request->autono
            )
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }
}