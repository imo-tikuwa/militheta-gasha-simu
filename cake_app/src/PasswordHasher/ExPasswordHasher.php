<?php
namespace App\PasswordHasher;

use App\Utils\Encrypter;
use Authentication\PasswordHasher\PasswordHasherInterface;
use Cake\Auth\AbstractPasswordHasher;

/**
 * 暗号化/複合化を行えるようにするために独自定義したクラス
 * @author tikuwa
 *
 */
class ExPasswordHasher extends AbstractPasswordHasher implements PasswordHasherInterface
{
    /**
     * Default config for this object.
     *
     * ### Options
     *
     * - `hashType` - Hashing algo to use. Valid values are those supported by `$algo`
     *   argument of `password_hash()`. Defaults to null
     *
     * @var array
     */
    protected $_defaultConfig = [
        'hashType' => null,
    ];

    /**
     * パスワードの暗号化
     * {@inheritDoc}
     * @see \Authentication\PasswordHasher\PasswordHasherInterface::hash()
     */
    public function hash($password): string
    {
        return Encrypter::encrypt($password);
    }

    /**
     * @param $password フォームで入力したパスワード
     * @param $hashedPassword DBに登録してあるpassword
     * {@inheritDoc}
     * @see \Authentication\PasswordHasher\PasswordHasherInterface::check()
     */
    public function check($password, $hashedPassword): bool
    {
        if (is_null($hashedPassword) || $hashedPassword === '') {
            return false;
        }
        return $password === Encrypter::decrypt($hashedPassword);
    }
}
