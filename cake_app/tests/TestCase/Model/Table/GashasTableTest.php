<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GashasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GashasTable Test Case
 */
class GashasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GashasTable
     */
    protected $Gashas;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Gashas',
        'app.CardReprints',
        'app.GashaPickups',
    ];

    /**
     * gasha valid data.
     */
    protected $valid_data;

    /**
     * gasha valid csv data.
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
        $config = $this->getTableLocator()->exists('Gashas') ? [] : ['className' => GashasTable::class];
        $this->Gashas = $this->getTableLocator()->get('Gashas', $config);

        $this->valid_data = [
            // ガシャ開始日
            'start_date' => date('Y-m-d'),
            // ガシャ終了日
            'end_date' => date('Y-m-d'),
            // ガシャタイトル
            'title' => 'valid data.',
            // SSRレート
            'ssr_rate' => 0,
            // SRレート
            'sr_rate' => 0,
        ];

        $this->valid_csv_data = [];
        // ID
        $this->valid_csv_data[] = '';
        // ガシャ開始日
        $this->valid_csv_data[] = date('Y-m-d');
        // ガシャ終了日
        $this->valid_csv_data[] = date('Y-m-d');
        // ガシャタイトル
        $this->valid_csv_data[] = 'valid data.';
        // SSRレート
        $this->valid_csv_data[] = '0';
        // SRレート
        $this->valid_csv_data[] = '0';
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
        unset($this->Gashas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $gasha = $this->Gashas->newEmptyEntity();
        $gasha = $this->Gashas->patchEntity($gasha, $this->valid_data);
        $this->assertEmpty($gasha->getErrors());
    }

    /**
     * Test validationCsv method
     *
     * @return void
     */
    public function testValidationCsv(): void
    {
        $gasha = $this->Gashas->newEmptyEntity();
        $gasha = $this->Gashas->patchEntity($gasha, $this->valid_data, [
            'validate' => 'csv',
        ]);
        $this->assertEmpty($gasha->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $gasha = $this->Gashas->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Gasha', $gasha);
        $gasha = $this->Gashas->patchEntity($gasha, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $gasha);

        $this->assertFalse($gasha->hasErrors());
    }

    /**
     * Test getSearchQuery method
     *
     * @return void
     */
    public function testGetSearchQuery(): void
    {
        $query = $this->Gashas->getSearchQuery([]);
        $gasha = $query->select(['id'])->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertTrue(array_key_exists('id', $gasha));
        $this->assertEquals(1, $gasha['id']);

        $query = $this->Gashas->getSearchQuery(['id' => 99999]);
        $gasha = $query->enableHydration(false)->first();

        $this->assertInstanceOf('\Cake\ORM\Query', $query);
        $this->assertNull($gasha);
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
            'ガシャ開始日',
            'ガシャ終了日',
            'ガシャタイトル',
            'SSRレート',
            'SRレート',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->Gashas->getCsvHeaders(), $data);
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
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Gashas->getCsvColumns(), $data);
    }

    /**
     * Test createEntityByCsvRow method
     *
     * @return void
     */
    public function testCreateEntityByCsvRow(): void
    {
        $gasha = $this->Gashas->createEntityByCsvRow($this->valid_csv_data);
        $this->assertInstanceOf('\App\Model\Entity\Gasha', $gasha);
        $this->assertFalse($gasha->hasErrors());

        $result = $this->Gashas->save($gasha);
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
            'start_date',
            'end_date',
            'title',
            'ssr_rate',
            'sr_rate',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Gashas->getExcelColumns(), $data);
    }

    /**
     * Test findGashaData method
     *
     * @return void
     */
    public function testFindGashaData(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }

    /**
     * Test getGashaJsonData method
     *
     * @return void
     */
    public function testGetGashaJsonData(): void
    {
        $this->markTestIncomplete('このテストはまだ実装されていません。');
    }

    /**
     * Test query method
     *
     * @return void
     */
    public function testQuery(): void
    {
        $query = $this->Gashas->query();
        $this->assertInstanceOf('\SoftDelete\ORM\Query', $query);
    }

    /**
     * Test deleteAll method
     *
     * @return void
     */
    public function testDeleteAll(): void
    {
        $this->Gashas->deleteAll([]);
        $this->assertEquals(0, $this->Gashas->find()->count());
        $this->assertNotEquals(0, $this->Gashas->find('all', ['withDeleted'])->count());
    }

    /**
     * Test getSoftDeleteField method
     *
     * @return void
     */
    public function testGetSoftDeleteField(): void
    {
        $this->assertEquals($this->Gashas->getSoftDeleteField(), 'deleted');
    }

    /**
     * Test hardDelete method
     *
     * @return void
     */
    public function testHardDelete(): void
    {
        $gasha = $this->Gashas->get(1);
        $this->Gashas->hardDelete($gasha);
        $gasha = $this->Gashas->findById(1)->first();
        $this->assertEquals(null, $gasha);

        $gasha = $this->Gashas->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $gasha);
    }

    /**
     * Test hardDeleteAll method
     *
     * @return void
     */
    public function testHardDeleteAll(): void
    {
        $affected_rows = $this->Gashas->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affected_rows);

        $gashas_rows_count = $this->Gashas->find('all', ['withDeleted'])->count();

        $this->Gashas->delete($this->Gashas->get(1));
        $affected_rows = $this->Gashas->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affected_rows);

        $newgashas_rows_count = $this->Gashas->find('all', ['withDeleted'])->count();
        $this->assertEquals($gashas_rows_count - 1, $newgashas_rows_count);
    }

    /**
     * Test restore method
     *
     * @return void
     */
    public function testRestore(): void
    {
        $gasha = $this->Gashas->findById(1)->first();
        $this->assertNotNull($gasha);
        $this->Gashas->delete($gasha);
        $gasha = $this->Gashas->findById(1)->first();
        $this->assertNull($gasha);

        $gasha = $this->Gashas->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->Gashas->restore($gasha);
        $gasha = $this->Gashas->findById(1)->first();
        $this->assertNotNull($gasha);
    }
}
