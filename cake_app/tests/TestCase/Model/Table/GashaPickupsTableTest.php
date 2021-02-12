<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GashaPickupsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GashaPickupsTable Test Case
 */
class GashaPickupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GashaPickupsTable
     */
    protected $GashaPickups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GashaPickups',
        'app.Gashas',
        'app.Cards',
    ];

    /**
     * gasha_pickup valid data.
     */
    protected $valid_data;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('GashaPickups') ? [] : ['className' => GashaPickupsTable::class];
        $this->GashaPickups = $this->getTableLocator()->get('GashaPickups', $config);

        /** @var \App\Model\Entity\Gasha $gasha */
        $gasha = $this->getTableLocator()->get('Gashas')->get(1);
        /** @var \App\Model\Entity\Card $card */
        $card = $this->getTableLocator()->get('Cards')->get(1);

        $this->valid_data = [
            // ガシャID
            'gasha_id' => $gasha->id,
            // カードID
            'card_id' => $card->id,
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GashaPickups);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $gasha_pickup = $this->GashaPickups->newEmptyEntity();
        $gasha_pickup = $this->GashaPickups->patchEntity($gasha_pickup, $this->valid_data);
        $this->assertEmpty($gasha_pickup->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $gasha_pickup = $this->GashaPickups->get(1);
        $this->assertTrue($this->GashaPickups->checkRules($gasha_pickup));

        $gasha_pickup = $this->GashaPickups->get(1);
        $gasha_pickup->set('gasha_id', -1);
        $this->assertFalse($this->GashaPickups->checkRules($gasha_pickup));

        $expected = [
            'gasha_id' => [
                '_existsIn' => 'This value does not exist'
            ],
        ];
        $this->assertEquals($gasha_pickup->getErrors(), $expected);
        $gasha_pickup = $this->GashaPickups->get(1);
        $gasha_pickup->set('card_id', -1);
        $this->assertFalse($this->GashaPickups->checkRules($gasha_pickup));

        $expected = [
            'card_id' => [
                '_existsIn' => 'This value does not exist'
            ],
        ];
        $this->assertEquals($gasha_pickup->getErrors(), $expected);
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $gasha_pickup = $this->GashaPickups->get(1);
        $this->assertInstanceOf('\App\Model\Entity\GashaPickup', $gasha_pickup);
        $gasha_pickup = $this->GashaPickups->patchEntity($gasha_pickup, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $gasha_pickup);

        $this->assertFalse($gasha_pickup->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->GashaPickups->getSearchQuery([]);
        $gasha_pickup = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $gasha_pickup));
        $this->assertEquals(1, $gasha_pickup['id']);

        $query = $this->GashaPickups->getSearchQuery(['id' => 99999]);
        $gasha_pickup = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($gasha_pickup);
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
            'ガシャID',
            'カードID',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->GashaPickups->getCsvHeaders(), $data);
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
            'gasha_id',
            'card_id',
            'created',
            'modified',
        ];
        $this->assertEquals($this->GashaPickups->getCsvColumns(), $data);
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
            'gasha_id',
            'card_id',
            'created',
            'modified',
        ];
        $this->assertEquals($this->GashaPickups->getExcelColumns(), $data);
    }

    /**
     * Test query method
     *
     * @return void
     */
    public function testQuery(): void
    {
        $query = $this->GashaPickups->query();
        $this->assertInstanceOf('\SoftDelete\ORM\Query', $query);
    }

    /**
     * Test deleteAll method
     *
     * @return void
     */
    public function testDeleteAll(): void
    {
        $this->GashaPickups->deleteAll([]);
        $this->assertEquals(0, $this->GashaPickups->find()->count());
        $this->assertNotEquals(0, $this->GashaPickups->find('all', ['withDeleted'])->count());
    }

    /**
     * Test getSoftDeleteField method
     *
     * @return void
     */
    public function testGetSoftDeleteField(): void
    {
        $this->assertEquals($this->GashaPickups->getSoftDeleteField(), 'deleted');
    }

    /**
     * Test hardDelete method
     *
     * @return void
     */
    public function testHardDelete(): void
    {
        $gasha_pickup = $this->GashaPickups->get(1);
        $this->GashaPickups->hardDelete($gasha_pickup);
        $gasha_pickup = $this->GashaPickups->findById(1)->first();
        $this->assertEquals(null, $gasha_pickup);

        $gasha_pickup = $this->GashaPickups->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $gasha_pickup);
    }

    /**
     * Test hardDeleteAll method
     *
     * @return void
     */
    public function testHardDeleteAll(): void
    {
        $affected_rows = $this->GashaPickups->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affected_rows);

        $gasha_pickups_rows_count = $this->GashaPickups->find('all', ['withDeleted'])->count();

        $this->GashaPickups->delete($this->GashaPickups->get(1));
        $affected_rows = $this->GashaPickups->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affected_rows);

        $newgasha_pickups_rows_count = $this->GashaPickups->find('all', ['withDeleted'])->count();
        $this->assertEquals($gasha_pickups_rows_count - 1, $newgasha_pickups_rows_count);
    }

    /**
     * Test restore method
     *
     * @return void
     */
    public function testRestore(): void
    {
        $gasha_pickup = $this->GashaPickups->findById(1)->first();
        $this->assertNotNull($gasha_pickup);
        $this->GashaPickups->delete($gasha_pickup);
        $gasha_pickup = $this->GashaPickups->findById(1)->first();
        $this->assertNull($gasha_pickup);

        $gasha_pickup = $this->GashaPickups->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->GashaPickups->restore($gasha_pickup);
        $gasha_pickup = $this->GashaPickups->findById(1)->first();
        $this->assertNotNull($gasha_pickup);
    }
}
