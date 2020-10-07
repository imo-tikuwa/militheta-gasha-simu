<?php
namespace App\Model\Entity;

use App\Utils\Encrypter;

/**
 * Admin Entity
 *
 * @property int $id
 * @property string $mail
 * @property string $password
 * @property array|null $privilege
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 */
class Admin extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'mail' => true,
        'password' => true,
        'privilege' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * 平のパスワードを暗号化する
     */
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return Encrypter::encrypt($password);
        }
    }

    /**
     * 生のパスワードを返す
     * @return string
     */
    protected function _getRawPassword()
    {
        if (empty($this->password)) {
            return null;
        }

        return Encrypter::decrypt($this->password);
    }
}
