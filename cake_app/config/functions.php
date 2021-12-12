<?php
use Cake\Core\Configure;

/**
 * 設定データを取得する
 *
 * @param $code_key
 * @param $default
 * @return bool|mixed
 */
function _code($code_key, $default = null)
{
    return Configure::read($code_key, $default);
}

/**
 * 引数のテキストについて改行コードをセパレータとし、配列に変換する
 * 改行コードはCRLF、CR、LFの全てに対応
 *
 * @param string|null $text
 * @return array
 */
function text2array($text = null)
{
    return explode("\n", str_replace(["\r\n", "\r", "\n"], "\n", $text));
}

/**
 * 桁数を指定した切り捨て
 * 参考：https://gotohayato.com/content/491/
 * @param int|float $value
 * @param int $precision
 * @return float
 */
function floor_plus($value, $precision = 1)
{
	return round($value - 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_UP);
}
