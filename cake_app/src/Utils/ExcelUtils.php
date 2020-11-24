<?php
namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Excel周りで使用する関数をまとめたUtilクラス
 */
class ExcelUtils
{
    /**
     * ワークシートに設定する選択形式の入力規則を作成＆返す
     * @param string $formula1 入力規則
     * @return DataValidation
     */
    public static function getDataValidation($formula1 = null): DataValidation
    {
        return (new DataValidation())
        ->setFormula1($formula1)
        ->setType(DataValidation::TYPE_LIST)
        ->setAllowBlank(true)
        ->setShowDropDown(true)
        ->setShowErrorMessage(true)
        ->setErrorStyle(DataValidation::STYLE_STOP);
    }

    /**
     * Excelインポート機能でアップロードされたExcelファイルが処理可能かチェックする
     * @param Worksheet $spreadsheet phpspreadsheetのSpreadsheet
     * @param string $controller Excelインポートを行おうとしているコントローラ名
     * @return bool
     */
    public static function checkExcelVersion(Spreadsheet $spreadsheet = null, $controller = null): bool
    {
        if (is_null($spreadsheet) || is_null($controller)) {
            return false;
        }

        // VERSIONシート取得
        $version_sheet = $spreadsheet->getSheetByName('VERSION');

        // プロパティに保管されているExcelバージョンとExcelファイルのVERSIONシートに記載されているテキストを比較
        $baked_version = _code("ExcelOptions.{$controller}.version");
        $excel_version = $version_sheet->getCell('A1')->getValue();

        if (empty($baked_version) || empty($excel_version)) {
            return false;
        }

        return $baked_version === $excel_version;
    }
}
