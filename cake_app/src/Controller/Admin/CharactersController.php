<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use App\Utils\ExcelUtils;
use Cake\Http\CallbackStream;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use DateTime;
use DateTimeZone;

/**
 * Characters Controller
 *
 * @property \App\Model\Table\CharactersTable $Characters
 *
 * @method \App\Model\Entity\Character[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CharactersController extends AppController
{
    /**
     * Paging setting.
     */
    public $paginate = [
        'limit' => 20,
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $request = $this->getRequest()->getQueryParams();
        $query = $this->Characters->getSearchQuery($request);
        $characters = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('characters', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Character id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $character = $this->Characters->get($id);

        $this->set('character', $character);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        return $this->_form();
    }

    /**
     * Edit method
     *
     * @param string|null $id キャラクターID
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        return $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id キャラクターID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $character = $this->Characters->get($id);
            $this->Characters->touch($character);
        } else {
            $character = $this->Characters->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $character = $this->Characters->patchEntity($character, $this->getRequest()->getData(), ['associated' => ['Cards']]);
            if ($character->hasErrors()) {
                $this->Flash->set(implode('<br />', $character->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->Characters->getConnection();
                $conn->begin();
                if ($this->Characters->save($character, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('キャラクターの登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Characters')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('character'));
        $this->render('edit');
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $characters = $this->Characters->getSearchQuery($request)->toArray();
        $extract = [
            // ID
            'id',
            // 名前
            'name',
            // 作成日時
            function ($row) {
                if ($row['created'] instanceof FrozenTime) {
                    return @$row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
                }

                return "";
            },
            // 更新日時
            function ($row) {
                if ($row['modified'] instanceof FrozenTime) {
                    return @$row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
                }

                return "";
            },
        ];

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $this->response = $this->response->withDownload("characters-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'characters',
            'header' => $this->Characters->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('characters'));
    }

    /**
     * excel export method
     * @return \Cake\Http\Response
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        /** @var \App\Model\Entity\Character[] $characters */
        $characters = $this->Characters->getSearchQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'characters_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($characters as $character) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $character->id);
            // 名前
            $data_sheet->setCellValue("B{$row_num}", $character->name);
            // 作成日時
            $cell_value = ($character->created instanceof FrozenTime) ? $character->created->i18nFormat('yyyy-MM-dd HH:mm:ss') : null;
            $data_sheet->setCellValue("C{$row_num}", $cell_value);
            // 更新日時
            $cell_value = ($character->modified instanceof FrozenTime) ? $character->modified->i18nFormat('yyyy-MM-dd HH:mm:ss') : null;
            $data_sheet->setCellValue("D{$row_num}", $cell_value);
            $row_num++;
        }

        // データ入力行のフォーマットを文字列に設定
        $characters_row_num = count($characters) + 100;
        $data_sheet->getStyle("A2:D{$characters_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);


        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:D{$characters_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $data_sheet->setSelectedCell('A2');
        $data_sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndexByName('DATA');

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $writer = new XlsxWriter($spreadsheet);
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });

        return $this->response->withHeader('Content-Type', EXCEL_CONTENT_TYPE)
        ->withHeader('Content-Disposition', "attachment; filename=\"characters-{$datetime}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
