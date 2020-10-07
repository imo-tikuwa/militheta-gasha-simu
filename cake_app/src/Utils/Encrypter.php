<?php

namespace App\Utils;

use Cake\Utility\Security;

/**
 * 暗号化/複合化を行うクラス
 * @author tikuwa
 *
 */
class Encrypter {

    /** 暗号化用のメソッド */
    private const METHOD = 'AES-256-CBC';

    /**
     * 暗号化
     * @see https://everyday-growth.com/?p=2409
     *
     * @param string $plain_password
     */
    public static function encrypt($plain_password = '')
    {
        // 暗号化・復元用のIVキーを作成
        // 暗号初期化ベクトル (iv) の長さを取得
        $iv_size = openssl_cipher_iv_length(self::METHOD);

        // 暗号モードに対するIVの長さに合わせたキーを生成します
        $iv = openssl_random_pseudo_bytes($iv_size);

        // 暗号化
        $encrypted_password = openssl_encrypt($plain_password, self::METHOD, Security::getSalt(), OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv) . ':' . base64_encode($encrypted_password);
    }

    /**
     * 複合化
     *
     * @param string $encrypted_password
     */
    public static function decrypt($encrypted_password = '')
    {
        // 復元したいデータとそのIVキーを取得
        $password_data = explode(':', $encrypted_password);
        $iv = base64_decode($password_data[0]);
        $encrypted = base64_decode($password_data[1]);

        // 複合化
        return openssl_decrypt($encrypted, self::METHOD, Security::getSalt(), OPENSSL_RAW_DATA, $iv);
    }
}