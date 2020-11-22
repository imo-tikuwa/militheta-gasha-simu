<?php
use Cake\Core\Configure;

/**
 * コード定義を取得する
 * @param $code_key
 * @param $default
 * @return bool|mixed
 */
function _code($code_key, $default = null)
{
    return Configure::read($code_key, $default);
}

/**
 * ランダムな文字列の生成
 * @param number $length
 */
function create_random_str($length = 8)
{
    $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
}

/**
 * 桁数を指定した切り捨て
 * 参考：https://gotohayato.com/content/491/
 * @param unknown $value
 * @param number $precision
 * @return number
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