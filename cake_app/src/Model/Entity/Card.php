<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Card Entity
 *
 * @property int $id
 * @property int|null $character_id
 * @property string|null $name
 * @property string|null $rarity
 * @property string|null $type
 * @property \Cake\I18n\FrozenDate|null $add_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $delete_flag
 *
 * @property \App\Model\Entity\Character $character
 */
class Card extends Entity
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
        'character_id' => true,
        'name' => true,
        'rarity' => true,
        'type' => true,
        'add_date' => true,
        'created' => true,
        'modified' => true,
        'delete_flag' => true,
        'character' => true
    ];
}
