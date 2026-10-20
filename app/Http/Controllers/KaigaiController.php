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

/*
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
            'tanikosu' => ($row['tanikosu'] ?? '') === '' ? null : $row['tanikosu'],
            'tstani' => ($row['tstani'] ?? '') === '' ? null : $row['tstani'],
            'gokeisu' => ($row['gokeisu'] ?? '') === '' ? null : $row['gokeisu'],
            'gstani' => ($row['gstani'] ?? '') === '' ? null : $row['gstani'],

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
        // autonoあり → UPDATE
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
        // autonoなし → INSERT
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
*/

    public function save(Request $request)
    {
        try {
            $row = $request->all();

            $shozokuid = $row['shozokuid'];
            $year      = $row['year'];
            $no        = $row['no'];

            // 空文字はNULLに変換
            $nullable = function ($value) {
                return ($value === '' || $value === null)
                    ? null
                    : $value;
            };

            /*
            * ① 海外データ os onaekaigaidata
            */
            $saveData = [
                'shozokuid' => $shozokuid,
                'year'      => $year,
                'no'        => $no,

                'excelno'   => $row['excelno'] ?? null,
                'kubetsu'   => $row['kubetsu'],
                'hinmoku'   => $row['hinmoku'],
                'chugokugo' => $row['chugokugo'] ?? '',
                'chiikimei' => $row['chiikimei'] ?? '',
                'jissiyear' => $row['jissiyear'] ?? '',
                'seisansha' => $row['seisansha'] ?? '',
                'shinjakb'  => $row['shinjakb'] ?? '',
                'suryo'     => $row['suryo'] ?? '',

                'tanikosu' => $nullable($row['tanikosu'] ?? null),
                'tstani'   => $nullable($row['tstani'] ?? null),
                'gokeisu'  => $nullable($row['gokeisu'] ?? null),
                'gstani'   => $nullable($row['gstani'] ?? null),

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
            * 海外テーブル INSERT / UPDATE
            */
            if (!empty($row['autono'])) {
                DB::table('osonaekaigaidata')
                    ->where('autono', $row['autono'])
                    ->update($saveData);

                $autono = $row['autono'];
            } else {
                $autono = DB::table('osonaekaigaidata')
                    ->insertGetId($saveData);
            }

            /*
            * ② 通常データ osonaedata
            * 項目名を変換して INSERT / UPDATE
            */
            $osonaeData = [
                'shozokuid' => $shozokuid,
                'year'      => $year,
                'no'        => $no,

                'osonaekb' => match ($row['kubetsu'] ?? '') {
                    '野菜' => 2,
                    '果物' => 3,
                    '特産' => 4,
                    default => null,
                },
                'hinmoku'   => $row['hinmoku'],

                'tanikosu3' => $nullable($row['tanikosu'] ?? null),
                'tstani3'   => $nullable($row['tstani'] ?? null),
                'gokeisu3'  => $nullable($row['gokeisu'] ?? null),
                'gstani3'   => $nullable($row['gstani'] ?? null),

                'tantoshaname' => $row['tantoshaname'] ?? '',
            ];

            /*
            * 同じ shozokuid・year・no があれば UPDATE
            * なければ INSERT
            */
            $exists = DB::table('osonaedata')
                ->where('shozokuid', $shozokuid)
                ->where('year', $year)
                ->where('no', $no)
                ->exists();

            if ($exists) {
                DB::table('osonaedata')
                    ->where('shozokuid', $shozokuid)
                    ->where('year', $year)
                    ->where('no', $no)
                    ->update($osonaeData);
            } else {
                DB::table('osonaedata')->insert($osonaeData);
            }

            /*
            * 保存結果を返す
            */
            $savedData = DB::table('osonaekaigaidata')
                ->where('autono', $autono)
                ->first([
                    'updatedt',
                    'tantoshaname'
                ]);

            return response()->json([
                'success'      => true,
                'autono'       => $autono,
                'updatedt'     => $savedData?->updatedt,
                'tantoshaname' => $savedData?->tantoshaname,
            ]);

        } catch (\Throwable $e) {
            \Log::error('Kaigai save error', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);

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

    /*
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
    */

    public function delete(Request $request)
    {
        $request->validate([
            'autono' => 'required',
        ]);

        return DB::transaction(function () use ($request) {

            // ① 削除対象を取得
            $row = DB::table('osonaekaigaidata')
                ->where('autono', $request->autono)
                ->first();

            if (!$row) {
                return response()->json([
                    'success' => false,
                    'message' => '削除対象が見つかりません。',
                ], 404);
            }

            $shozokuid = $row->shozokuid;
            $year = $row->year;

            // ② 海外データの削除
            DB::table('osonaekaigaidata')
                ->where('autono', $request->autono)
                ->delete();

            // ③ 残った海外データを取得
            $rows = DB::table('osonaekaigaidata')
                ->where('shozokuid', $shozokuid)
                ->where('year', $year)
                ->where('no', '<>', 0)
                ->orderBy('no')
                ->orderBy('autono')
                ->get();

            // ④ 海外データの番号を 1 から詰め直す
            foreach ($rows as $index => $item) {
                $newNo = $index + 1;

                if ((int) $item->no !== $newNo) {
                    DB::table('osonaekaigaidata')
                        ->where('autono', $item->autono)
                        ->update([
                            'no' => $newNo,
                        ]);
                }
            }

            // ⑤ この所属・年度の osonaeデータを整理
            // shozokuid で国内データとは分かれている前提
            DB::table('osonaedata')
                ->where('shozokuid', $shozokuid)
                ->where('year', $year)
                ->delete();

            // ⑥ 整理後の海外データを osonaeデータへ再登録
            foreach ($rows as $index => $item) {

                $kubetsu = match ($item->kubetsu ?? '') {
                    '野菜' => 2,
                    '果物' => 3,
                    '特産' => 4,
                    default => null,
                };

                DB::table('osonaedata')->insert([
                    'shozokuid' => $shozokuid,
                    'year' => $year,
                    'no' => $index + 1,
                    'osonaekb' => $kubetsu,
                    'hinmoku' => $item->hinmoku,
                    'tanikosu3' => $item->tanikosu,
                    'tstani3' => $item->tstani,
                    'gokeisu3' => $item->gokeisu,
                    'gstani3' => $item->gstani,
                    'tantoshaname' => $item->tantoshaname ?? '',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => '削除しました。',
            ]);
        });
    }
    
}