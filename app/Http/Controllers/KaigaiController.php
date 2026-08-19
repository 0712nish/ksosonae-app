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
        $row = $request->all();

        $saveData = [

            'shozokuid' => $row['shozokuid'],
            'year'      => $row['year'],
            'no'        => $row['no'],

            'excelno'   => $row['excelno'] ?? null,

            'kubetsu'   => $row['kubetsu'],
            'hinmoku'   => $row['hinmoku'],

            'chugokugo' =>
                $row['chugokugo'] ?? '',

            'chiikimei' =>
                $row['chiikimei'] ?? '',

            'jissisyear' =>
                $row['jissisyear'] ?? '',

            'seisansha' =>
                $row['seisansha'] ?? '',

            'shinjkab' =>
                $row['shinjkab'] ?? '',

            'suryo' =>
                $row['suryo'] ?? '',
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


        return response()->json([
            'success' => true,
            'autono' => $autono
        ]);
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