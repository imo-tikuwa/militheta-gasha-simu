<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CardsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CardsTable Test Case
 */
class CardsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CardsTable
     */
    protected $Cards;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Cards',
        'app.Characters',
        'app.CardReprints',
        'app.GashaPickups',
    ];

    /**
     * card valid data.
     */
    protected $valid_data;

    /**
     * card valid csv data.
     */
    protected $valid_csv_data;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Cards') ? [] : ['className' => CardsTable::class];
        $this->Cards = $this->getTableLocator()->get('Cards', $config);

        /** @var \App\Model\Entity\Character $character */
        $character = $this->getTableLocator()->get('Characters')->get(1);

        $this->valid_data = [
            // キャラクター
            'character_id' => $character->id,
            // カード名
            'name' => 'valid data.',
            // レアリティ
            'rarity' => '01',
            // タイプ
            'type' => '01',
            // 実装日
            'add_date' => date('Y-m-d'),
            // ガシャ対象？
            'gasha_include' => '1',
            // 限定？
            'limited' => '01',
        ];

        $this->valid_csv_data = [];
        // ID
        $this->valid_csv_data[] = '';
        // キャラクター
        $this->valid_csv_data[] = $character->name;
        // カード名
        $this->valid_csv_data[] = 'valid data.';
        // レアリティ
        $this->valid_csv_data[] = 'N';
        // タイプ
        $this->valid_csv_data[] = 'Princess';
        // 実装日
        $this->valid_csv_data[] = date('Y-m-d');
        // ガシャ対象？
        $this->valid_csv_data[] = '1';
        // 限定？
        $this->valid_csv_data[] = '恒常';
        // 作成日時
        $this->valid_csv_data[] = date('Y-m-d H:i:00');
        // 更新日時
        $this->valid_csv_data[] = date('Y-m-d H:i:00');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Cards);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $card = $this->Cards->newEmptyEntity();
        $card = $this->Cards->patchEntity($card, $this->valid_data);
        $this->assertEmpty($card->getErrors());
    }

    /**
     * Test validationCsv method
     *
     * @return void
     */
    public function testValidationCsv(): void
    {
        $card = $this->Cards->newEmptyEntity();
        $card = $this->Cards->patchEntity($card, $this->valid_data, [
            'validate' => 'csv',
        ]);
        $this->assertEmpty($card->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $card = $this->Cards->get(1);
        $this->assertTrue($this->Cards->checkRules($card));

        $card = $this->Cards->get(1);
        $card->set('character_id', -1);
        $this->assertFalse($this->Cards->checkRules($card));

        $expected = [
            'character_id' => [
                '_existsIn' => 'This value does not exist'
            ],
        ];
        $this->assertEquals($card->getErrors(), $expected);
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $card = $this->Cards->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Card', $card);
        $card = $this->Cards->patchEntity($card, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $card);

        $this->assertFalse($card->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->Cards->getSearchQuery([]);
        $card = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $card));
        $this->assertEquals(1, $card['id']);

        $query = $this->Cards->getSearchQuery(['id' => 99999]);
        $card = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($card);
    }

    /**
     * Test getCsvHeaders method
     *
     * @return void
     */
    public function testGetCsvHeaders(): void
    {
        $data = [
            'ID',
            'キャラクター',
            'カード名',
            'レアリティ',
            'タイプ',
            '実装日',
            'ガシャ対象？',
            '限定？',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->Cards->getCsvHeaders(), $data);
    }

    /**
     * Test getCsvColumns method
     *
     * @return void
     */
    public function testGetCsvColumns(): void
    {
        $data = [
            'id',
            'character_id',
            'name',
            'rarity',
            'type',
            'add_date',
            'gasha_include',
            'limited',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Cards->getCsvColumns(), $data);
    }

    /**
     * Test createEntityByCsvRow method
     *
     * @return void
     */
    public function testCreateEntityByCsvRow(): void
    {
        $card = $this->Cards->createEntityByCsvRow($this->valid_csv_data);
        $this->assertInstanceOf('\App\Model\Entity\Card', $card);
        $this->assertFalse($card->hasErrors());

        $result = $this->Cards->save($card);
        $this->assertTrue(!empty($result));
    }

    /**
     * Test getExcelColumns method
     *
     * @return void
     */
    public function testGetExcelColumns(): void
    {
        $data = [
            'id',
            'character_id',
            'name',
            'rarity',
            'type',
            'add_date',
            'gasha_include',
            'limited',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Cards->getExcelColumns(), $data);
    }

    /**
     * Test query method
     *
     * @return void
     */
    public function testQuery(): void
    {
        $query = $this->Cards->query();
        $this->assertInstanceOf('\SoftDelete\ORM\Query', $query);
    }

    /**
     * Test deleteAll method
     *
     * @return void
     */
    public function testDeleteAll(): void
    {
        $this->Cards->deleteAll([]);
        $this->assertEquals(0, $this->Cards->find()->count());
        $this->assertNotEquals(0, $this->Cards->find('all', ['withDeleted'])->count());
    }

    /**
     * Test getSoftDeleteField method
     *
     * @return void
     */
    public function testGetSoftDeleteField(): void
    {
        $this->assertEquals($this->Cards->getSoftDeleteField(), 'deleted');
    }

    /**
     * Test hardDelete method
     *
     * @return void
     */
    public function testHardDelete(): void
    {
        $card = $this->Cards->get(1);
        $this->Cards->hardDelete($card);
        $card = $this->Cards->findById(1)->first();
        $this->assertEquals(null, $card);

        $card = $this->Cards->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $card);
    }

    /**
     * Test hardDeleteAll method
     *
     * @return void
     */
    public function testHardDeleteAll(): void
    {
        $affected_rows = $this->Cards->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affected_rows);

        $cards_rows_count = $this->Cards->find('all', ['withDeleted'])->count();

        $this->Cards->delete($this->Cards->get(1));
        $affected_rows = $this->Cards->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affected_rows);

        $newcards_rows_count = $this->Cards->find('all', ['withDeleted'])->count();
        $this->assertEquals($cards_rows_count - 1, $newcards_rows_count);
    }

    /**
     * Test restore method
     *
     * @return void
     */
    public function testRestore(): void
    {
        $card = $this->Cards->findById(1)->first();
        $this->assertNotNull($card);
        $this->Cards->delete($card);
        $card = $this->Cards->findById(1)->first();
        $this->assertNull($card);

        $card = $this->Cards->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->Cards->restore($card);
        $card = $this->Cards->findById(1)->first();
        $this->assertNotNull($card);
    }
}
