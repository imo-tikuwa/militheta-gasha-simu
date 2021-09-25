<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Gasha Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $start_date
 * @property \Cake\I18n\FrozenDate|null $end_date
 * @property string|null $title
 * @property int|null $ssr_rate
 * @property int|null $sr_rate
 * @property string|null $search_snippet
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\CardReprint[] $card_reprints
 * @property \App\Model\Entity\GashaPickup[] $gasha_pickups
 */
class Gasha extends AppEntity
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
        'search_snippet' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'card_reprints' => true,
        'gasha_pickups' => true,
    ];

    /**
     * 限定ガチャ？
     * @return bool
     */
    public function isLimited()
    {
        return str_starts_with($this->title, '【期間限定】');
    }

    /**
     * フェス限定ガチャ？
     * @return bool
     */
    public function isFesLimited()
    {
        return str_starts_with($this->title, '【フェス限定】');
    }

    /**
     * SHS限定ガチャ？
     * @return bool
     */
    public function isShsLimited()
    {
        return str_starts_with($this->title, '【ヘアスタイル限定】');
    }

    /**
     * 復刻ガチャ？
     * @return bool
     */
    public function isReprintLimited()
    {
        return str_starts_with($this->title, '【限定復刻】');
    }
}
