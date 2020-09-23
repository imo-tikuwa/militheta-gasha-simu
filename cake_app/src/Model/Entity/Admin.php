<?php
namespace App\Model\Entity;

/**
 * Admin Entity
 *
 * @property int $id
 * @property string $mail
 * @property string $password
 * @property array|null $privilege
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $delete_flag
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
        'delete_flag' => true,
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
     * 生のパスワードを返す
     * @return string
     */
    protected function _getRawPassword()
    {
        if (empty($this->password)) {
            return null;
        }

        return decrypt_password($this->password);
    }
}
