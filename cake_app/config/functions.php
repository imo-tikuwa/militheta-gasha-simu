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

/**
 * 文字列$haystackは$needleで始まる？
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function starts_with($haystack, $needle)
{
	return $needle === "" || strpos($haystack, $needle) === 0;
}

/**
 * 文字列$haystackは$needleで終わる？
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function ends_with($haystack, $needle)
{
	return $needle === "" || substr($haystack, - strlen($needle)) === $needle;
}