<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Gasha Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $start_date
 * @property \Cake\I18n\FrozenDate|null $end_date
 * @property string|null $title
 * @property int|null $ssr_rate
 * @property int|null $sr_rate
 * @property float|null $ssr_pickup_rate
 * @property float|null $sr_pickup_rate
 * @property float|null $r_pickup_rate
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $delete_flag
 */
class Gasha extends Entity
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
        'start_date' => true,
        'end_date' => true,
        'title' => true,
        'ssr_rate' => true,
        'sr_rate' => true,
        'ssr_pickup_rate' => true,
        'sr_pickup_rate' => true,
        'r_pickup_rate' => true,
        'created' => true,
        'modified' => true,
        'delete_flag' => true
    ];
}
