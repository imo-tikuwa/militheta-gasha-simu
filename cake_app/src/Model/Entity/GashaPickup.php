<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * GashaPickup Entity
 *
 * @property int $id
 * @property int $gasha_id
 * @property int $card_id
 * @property string|null $search_snippet
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Gasha $gasha
 * @property \App\Model\Entity\Card $card
 */
class GashaPickup extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'gasha_id' => true,
        'card_id' => true,
        'search_snippet' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'gasha' => true,
        'card' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'search_snippet',
        'created',
        'modified',
        'deleted',
    ];
}
