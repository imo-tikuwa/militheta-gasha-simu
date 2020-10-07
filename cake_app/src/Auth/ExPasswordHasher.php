<?php
namespace App\Auth;

use App\Utils\Encrypter;
use Cake\Auth\AbstractPasswordHasher;

/**
 * プログラムのどこからでも暗号化、複合化を行えるようにするために独自定義したクラス
 *
 * @see https://insight.hiliberate.biz/?p=2289
 * @author tikuwa
 *
 */
class ExPasswordHasher extends AbstractPasswordHasher
{
    protected $_config = ['hashType' => null];

    /**
     * パスワードの暗号化
     * {@inheritDoc}
     * @see \Cake\Auth\AbstractPasswordHasher::hash()
     */
    public function hash($password)
    {
        return Encrypter::encrypt($password);
    }

    /**
     * @param $password フォームで入力したパスワード
     * @param $hashedPassword DBに登録してあるpassword
     * {@inheritDoc}
     * @see \Cake\Auth\AbstractPasswordHasher::check()
     */
    public function check($password, $hashedPassword)
    {
        return $password === Encrypter::decrypt($hashedPassword);
    }
}
