<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CardReprintsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CardReprintsTable Test Case
 */
class CardReprintsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CardReprintsTable
     */
    protected $CardReprints;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.CardReprints',
        'app.Gashas',
        'app.Cards',
    ];

    /**
     * card_reprint valid data.
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
        $config = $this->getTableLocator()->exists('CardReprints') ? [] : ['className' => CardReprintsTable::class];
        $this->CardReprints = $this->getTableLocator()->get('CardReprints', $config);

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
        unset($this->CardReprints);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $card_reprint = $this->CardReprints->newEmptyEntity();
        $card_reprint = $this->CardReprints->patchEntity($card_reprint, $this->valid_data);
        $this->assertEmpty($card_reprint->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $card_reprint = $this->CardReprints->get(1);
        $this->assertTrue($this->CardReprints->checkRules($card_reprint));

        $card_reprint = $this->CardReprints->get(1);
        $card_reprint->set('gasha_id', -1);
        $this->assertFalse($this->CardReprints->checkRules($card_reprint));

        $expected = [
            'gasha_id' => [
                '_existsIn' => 'This value does not exist'
            ],
        ];
        $this->assertEquals($card_reprint->getErrors(), $expected);
        $card_reprint = $this->CardReprints->get(1);
        $card_reprint->set('card_id', -1);
        $this->assertFalse($this->CardReprints->checkRules($card_reprint));

        $expected = [
            'card_id' => [
                '_existsIn' => 'This value does not exist'
            ],
        ];
        $this->assertEquals($card_reprint->getErrors(), $expected);
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $card_reprint = $this->CardReprints->get(1);
        $this->assertInstanceOf('\App\Model\Entity\CardReprint', $card_reprint);
        $card_reprint = $this->CardReprints->patchEntity($card_reprint, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $card_reprint);

        $this->assertFalse($card_reprint->hasErrors());
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
        $this->assertEquals($this->CardReprints->getCsvHeaders(), $data);
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
        $this->assertEquals($this->CardReprints->getCsvColumns(), $data);
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
        $this->assertEquals($this->CardReprints->getExcelColumns(), $data);
    }

    /**
     * Test query method
     *
     * @return void
     */
    public function testQuery(): void
    {
        $query = $this->CardReprints->query();
        $this->assertInstanceOf('\SoftDelete\ORM\Query', $query);
    }

    /**
     * Test deleteAll method
     *
     * @return void
     */
    public function testDeleteAll(): void
    {
        $this->CardReprints->deleteAll([]);
        $this->assertEquals(0, $this->CardReprints->find()->count());
        $this->assertNotEquals(0, $this->CardReprints->find('all', ['withDeleted'])->count());
    }

    /**
     * Test getSoftDeleteField method
     *
     * @return void
     */
    public function testGetSoftDeleteField(): void
    {
        $this->assertEquals($this->CardReprints->getSoftDeleteField(), 'deleted');
    }

    /**
     * Test hardDelete method
     *
     * @return void
     */
    public function testHardDelete(): void
    {
        $card_reprint = $this->CardReprints->get(1);
        $this->CardReprints->hardDelete($card_reprint);
        $card_reprint = $this->CardReprints->findById(1)->first();
        $this->assertEquals(null, $card_reprint);

        $card_reprint = $this->CardReprints->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $card_reprint);
    }

    /**
     * Test hardDeleteAll method
     *
     * @return void
     */
    public function testHardDeleteAll(): void
    {
        $affected_rows = $this->CardReprints->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affected_rows);

        $card_reprints_rows_count = $this->CardReprints->find('all', ['withDeleted'])->count();

        $this->CardReprints->delete($this->CardReprints->get(1));
        $affected_rows = $this->CardReprints->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affected_rows);

        $newcard_reprints_rows_count = $this->CardReprints->find('all', ['withDeleted'])->count();
        $this->assertEquals($card_reprints_rows_count - 1, $newcard_reprints_rows_count);
    }

    /**
     * Test restore method
     *
     * @return void
     */
    public function testRestore(): void
    {
        $card_reprint = $this->CardReprints->findById(1)->first();
        $this->assertNotNull($card_reprint);
        $this->CardReprints->delete($card_reprint);
        $card_reprint = $this->CardReprints->findById(1)->first();
        $this->assertNull($card_reprint);

        $card_reprint = $this->CardReprints->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->CardReprints->restore($card_reprint);
        $card_reprint = $this->CardReprints->findById(1)->first();
        $this->assertNotNull($card_reprint);
    }
}
