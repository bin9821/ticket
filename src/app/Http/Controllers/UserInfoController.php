<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function csvDownload() {
        $user = User::all();
        // 檔名可含日期時間
        $filename = 'user-1-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($user) {
            $out = fopen('php://output', 'w');
            $userMail = array_map(function($value) {
                return UserInfoController::sanitizeForCsv($value['email']);
            }, $user->toArray());

            // 為了 Excel 正確顯示 UTF-8，加上 BOM
            fwrite($out, "\xEF\xBB\xBF");

            // (header)
            fputcsv($out, ['email', 'password']);
            // (row)
            for ($i=0; $i<count($userMail); $i++){
                fputcsv($out, [$userMail[$i], 'password']);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    /**
     * 簡單防止 CSV 公式注入(formula injection)：
     * 以 = + - @ 開頭的值，前面加單引號。
     */
    private static function sanitizeForCsv($arrValue)
    {
        $value = (string) $arrValue;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'])) {
            return "'".$value;
        }
        return $value;
    }
}
