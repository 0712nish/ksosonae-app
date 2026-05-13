<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OsonaeController extends Controller
{
    public function index(Request $request)
    {
        $sname = $request->query('sname');
        $shozokuid = $request->query('shozokuid');
        $year  = $request->query('year');

        $data = DB::table('v_osonaedata')
            ->where('sname', $sname)
            /*->where('shozokuid', $shozokuid)*/
            ->where('year', $year)
            ->where('no', '<>', 0)
            ->orderBy('no')
            ->get();

        return response()->json($data);
    }

    public function save(Request $request)
    {
        $row = $request->all();

        // 区分名 → 区分NO変換
        $kubun = DB::table('osonaekubunmst')
            ->where('name', $row['kubetsu'])
            ->first();

        DB::table('osonaedata')->updateOrInsert(
            [
                'shozokuid' => $row['shozokuid'],
                'year'      => $row['year'],
                'no'        => $row['no'],
            ],
            [
                'osonaekb'  => $kubun?->no,

                'hinmoku'   => $row['hinmoku'],

                'tanikosu1' => $row['quantities']['20日']['ml_value'],
                'tstani1'   => $row['quantities']['20日']['ml_unit'],
                'gokeisu1'  => $row['quantities']['20日']['hon_value'],
                'gstani1'   => $row['quantities']['20日']['hon_unit'],
                'hakosu1'   => $row['quantities']['20日']['hako_value'],
                'hstani1'   => $row['quantities']['20日']['hako_unit'],

                'tanikosu2' => $row['quantities']['25日']['ml_value'],
                'tstani2'   => $row['quantities']['25日']['ml_unit'],
                'gokeisu2'  => $row['quantities']['25日']['hon_value'],
                'gstani2'   => $row['quantities']['25日']['hon_unit'],
                'hakosu2'   => $row['quantities']['25日']['hako_value'],
                'hstani2'   => $row['quantities']['25日']['hako_unit'],

                'tanikosu3' => $row['quantities']['28日']['ml_value'],
                'tstani3'   => $row['quantities']['28日']['ml_unit'],
                'gokeisu3'  => $row['quantities']['28日']['hon_value'],
                'gstani3'   => $row['quantities']['28日']['hon_unit'],
                'hakosu3'   => $row['quantities']['28日']['hako_value'],
                'hstani3'   => $row['quantities']['28日']['hako_unit'],
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        DB::table('osonaedata')
            ->where('shozokuid', $request->shozokuid)
            ->where('year', $request->year)
            ->where('no', $request->no)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /*public function replaceAll(Request $request)
    {
        $rows = $request->rows;

        $shozokuid = $request->shozokuid;
        $year = $request->year;

        DB::table('osonaedata')
            ->where('shozokuid', $shozokuid)
            ->where('year', $year)
            ->delete();

        foreach ($rows as $row) {

            $kubun = DB::table('osonaekubunmst')
                ->where('name', $row['kubetsu'])
                ->first();

            DB::table('osonaedata')->insert([
                'shozokuid' => $shozokuid,
                'year'      => $year,
                'no'        => $row['no'],

                'osonaekb'  => $kubun?->no,

                'hinmoku'   => $row['hinmoku'],

                'tanikosu1' => $row['quantities']['20日']['ml_value'],
                'tstani1'   => $row['quantities']['20日']['ml_unit'],
                'gokeisu1'  => $row['quantities']['20日']['hon_value'],
                'gstani1'   => $row['quantities']['20日']['hon_unit'],
                'hakosu1'   => $row['quantities']['20日']['hako_value'],
                'hstani1'   => $row['quantities']['20日']['hako_unit'],

                'tanikosu2' => $row['quantities']['25日']['ml_value'],
                'tstani2'   => $row['quantities']['25日']['ml_unit'],
                'gokeisu2'  => $row['quantities']['25日']['hon_value'],
                'gstani2'   => $row['quantities']['25日']['hon_unit'],
                'hakosu2'   => $row['quantities']['25日']['hako_value'],
                'hstani2'   => $row['quantities']['25日']['hako_unit'],

                'tanikosu3' => $row['quantities']['28日']['ml_value'],
                'tstani3'   => $row['quantities']['28日']['ml_unit'],
                'gokeisu3'  => $row['quantities']['28日']['hon_value'],
                'gstani3'   => $row['quantities']['28日']['hon_unit'],
                'hakosu3'   => $row['quantities']['28日']['hako_value'],
                'hstani3'   => $row['quantities']['28日']['hako_unit'],
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }*/

}