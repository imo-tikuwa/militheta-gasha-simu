<?php
namespace App\Utils;

/**
 * CSV周りで使用する関数をまとめたUtilクラス
 */
class CsvUtils
{

    /**
     * @param string $file Shift_JIS の csv ファイルパス
     * @return array
     */
    public static function parseCsv($file)
    {
        $str = file_get_contents($file);
        $is_win = strpos(PHP_OS, "WIN") === 0;

        // Windowsの場合は Shift_JIS、Unix系は UTF-8で処理
        if ( $is_win ) {
            setlocale(LC_ALL, "Japanese_Japan.932");
        } else {
            setlocale(LC_ALL, "ja_JP.UTF-8");
            $str = mb_convert_encoding($str, "UTF-8", "SJIS-win");
        }

        $result = array();
        $fp = fopen("php://temp", "r+");
        fwrite($fp, str_replace(array("\r\n", "\r" ), "\n", $str));
        rewind($fp);
        while($row = fgetcsv($fp)) {
            // windows の場合はSJIS-win → UTF-8 変換
            $result[] = $is_win
                ? array_map(function($val){return mb_convert_encoding($val, "UTF-8", "SJIS-win");}, $row)
                : $row;
        }
        fclose($fp);

        return $result;
    }
}
