<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CharactersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharactersTable Test Case
 */
class CharactersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CharactersTable
     */
    protected $Characters;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Characters',
        'app.Cards',
    ];

    /**
     * character valid data.
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
        $config = $this->getTableLocator()->exists('Characters') ? [] : ['className' => CharactersTable::class];
        $this->Characters = $this->getTableLocator()->get('Characters', $config);

        $this->valid_data = [
            // 名前
            'name' => 'valid data.',
        ];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Characters);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $character = $this->Characters->newEmptyEntity();
        $character = $this->Characters->patchEntity($character, $this->valid_data);
        $this->assertEmpty($character->getErrors());
    }

    /**
     * Test patchEntity method
     *
     * @return void
     */
    public function testPatchEntity(): void
    {
        $character = $this->Characters->get(1);
        $this->assertInstanceOf('\App\Model\Entity\Character', $character);
        $character = $this->Characters->patchEntity($character, $this->valid_data);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $character);

        $this->assertFalse($character->hasErrors());
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
            '名前',
            '作成日時',
            '更新日時',
        ];
        $this->assertEquals($this->Characters->getCsvHeaders(), $data);
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
            'name',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Characters->getCsvColumns(), $data);
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
            'name',
            'created',
            'modified',
        ];
        $this->assertEquals($this->Characters->getExcelColumns(), $data);
    }
}
