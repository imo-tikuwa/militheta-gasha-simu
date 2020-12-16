<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Utils\ExcelUtils;
use Cake\Http\CallbackStream;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

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
        $this->set('params', $request);
        $query = $this->_getQuery($request);
        $characters = $this->paginate($query);

        $this->set(compact('characters'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Characters->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Characters->aliasField('id') => $request['id']]);
        }
        // 名前
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->Characters->aliasField('name') => $request['name']]);
        }

        return $query;
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
        $characters = $this->_getQuery($request)->toArray();
        $_extract = [
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

        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('Asia/Tokyo'));

        $this->response = $this->response->withDownload("characters-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'characters',
            'header' => $this->Characters->getCsvHeaders(),
            'extract' => $_extract,
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
        $characters = $this->_getQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'characters_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($characters as $character) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $character['id']);
            // 名前
            $data_sheet->setCellValue("B{$row_num}", $character['name']);
            // 作成日時
            $cell_value = @$character['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("C{$row_num}", $cell_value);
            // 更新日時
            $cell_value = @$character['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
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

        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('Asia/Tokyo'));
        $writer = new XlsxWriter($spreadsheet);
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });

        return $this->response->withHeader('Content-Type', EXCEL_CONTENT_TYPE)
        ->withHeader('Content-Disposition', "attachment; filename=\"characters-{$datetime->format('YmdHis')}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
