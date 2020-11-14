<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Card Entity
 *
 * @property int $id
 * @property int|null $character_id
 * @property string|null $name
 * @property string|null $rarity
 * @property string|null $type
 * @property \Cake\I18n\FrozenDate|null $add_date
 * @property int|null $gasha_include
 * @property string|null $limited
 * @property string|null $search_snippet
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Character $character
 * @property \App\Model\Entity\CardReprint[] $card_reprints
 * @property \App\Model\Entity\GashaPickup[] $gasha_pickups
 */
class Card extends AppEntity
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
        'gasha_include' => true,
        'limited' => true,
        'search_snippet' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'character' => true,
        'card_reprints' => true,
        'gasha_pickups' => true,
    ];
}
