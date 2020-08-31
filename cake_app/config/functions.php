<?php
use Cake\Core\Configure;

/**
 * コード定義を取得する
 * @param $code_key
 * @return bool|mixed
 */
function _code($code_key)
{
    return Configure::read($code_key);
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
 * CakePHP3のAuthで使用する暗号化関数
 * @see https://everyday-growth.com/?p=2409
 *
 * @param string $plain_password
 */
function encrypt_password($plain_password = '')
{
    // 暗号化用のメソッドを指定
    $method = 'AES-256-CBC';

    // 暗号化・復元用のIVキーを作成
    // 暗号初期化ベクトル (iv) の長さを取得
    $iv_size = openssl_cipher_iv_length($method);

    // 暗号モードに対するIVの長さに合わせたキーを生成します
    $iv = openssl_random_pseudo_bytes($iv_size);

    // 暗号化
    $encrypted_password = openssl_encrypt($plain_password, $method, _code('AdminConfig.CakeEncryptionSalt'), OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv) . ':' . base64_encode($encrypted_password);
}

/**
 * CakePHP3のAuthで使用する複合化関数
 *
 * @param string $encrypted_password
 */
function decrypt_password($encrypted_password = '')
{
    // 復元したいデータとそのIVキーを取得
    $password_data = explode(':', $encrypted_password);
    $iv = base64_decode($password_data[0]);
    $encrypted = base64_decode($password_data[1]);

    // 暗号化用のメソッドを指定
    $method = 'AES-256-CBC';

    // 複合化
    return openssl_decrypt($encrypted, $method, _code('AdminConfig.CakeEncryptionSalt'), OPENSSL_RAW_DATA, $iv);
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