<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Utils\CsvUtils;
use App\Form\SearchForm;
use App\Utils\ExcelUtils;
use Cake\Http\CallbackStream;
use Cake\Core\Exception\CakeException;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use DateTime;
use DateTimeZone;

/**
 * Gashas Controller
 *
 * @property \App\Model\Table\GashasTable $Gashas
 *
 * @method \App\Model\Entity\Gasha[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashasController extends AppController
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
        $query = $this->Gashas->getSearchQuery($request);
        $gashas = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('gashas', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Gasha id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gasha = $this->Gashas->get($id);

        $this->set('gasha', $gasha);
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
     * @param string|null $id ガシャID
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
     * @param string|null $id ガシャID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $gasha = $this->Gashas->get($id, [
                'contain' => [
                    'CardReprints',
                    'GashaPickups',
                ]
            ]);
            $this->Gashas->touch($gasha);
        } else {
            $gasha = $this->Gashas->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $gasha = $this->Gashas->patchEntity($gasha, $this->getRequest()->getData(), [
                'associated' => [
                    'CardReprints',
                    'GashaPickups',
                ]
            ]);
            if ($gasha->hasErrors()) {
                $this->Flash->set(implode('<br />', $gasha->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->Gashas->getConnection();
                $conn->begin();
                if ($this->Gashas->save($gasha, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('ガシャの登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Gashas')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('gasha'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id ガシャID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $entity = $this->Gashas->get($id);
        if ($this->Gashas->delete($entity)) {
            $this->Flash->success('ガシャの削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Gashas')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $gashas = $this->Gashas->getSearchQuery($request)->toArray();
        $extract = [
            // ID
            'id',
            // ガシャ開始日
            function ($row) {
                return $row['start_date']?->i18nFormat('yyyy-MM-dd');
            },
            // ガシャ終了日
            function ($row) {
                return $row['end_date']?->i18nFormat('yyyy-MM-dd');
            },
            // ガシャタイトル
            'title',
            // SSRレート
            function ($row) {
                return !is_null($row['ssr_rate']) ? "{$row['ssr_rate']}%" : null;
            },
            // SRレート
            function ($row) {
                return !is_null($row['sr_rate']) ? "{$row['sr_rate']}%" : null;
            },
            // 作成日時
            function ($row) {
                return $row['created']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            // 更新日時
            function ($row) {
                return $row['modified']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $this->response = $this->response->withDownload("gashas-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'gashas',
            'header' => $this->Gashas->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('gashas'));
    }

    /**
     * csv import method
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport()
    {
        $csv_import_file = $this->getRequest()->getUploadedFile('csv_import_file');
        if (!is_null($csv_import_file)) {
            $conn = $this->Gashas->getConnection();
            $conn->begin();
            try {
                $csv_data = CsvUtils::parseUtf8Csv($csv_import_file->getStream()->getMetadata('uri'));
                $insert_count = 0;
                $update_count = 0;
                foreach ($csv_data as $index => $csv_row) {
                    if ($index == 0) {
                        if ($this->Gashas->getCsvHeaders() != $csv_row) {
                            throw new CakeException('HeaderCheckError');
                        }
                        continue;
                    }

                    $gasha = $this->Gashas->createEntityByCsvRow($csv_row);
                    if ($gasha->isNew()) {
                        $insert_count++;
                    } else {
                        $update_count++;
                    }
                    if (!$this->Gashas->save($gasha, ['atomic' => false])) {
                        throw new CakeException('SaveError');
                    }
                }
                if (!$conn->commit()) {
                    throw new CakeException('CommitError');
                }
                $this->Flash->success("ガシャCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['escape' => false]);
            } catch (CakeException $e) {
                $error_message = 'ガシャCSVの登録でエラーが発生しました。';
                if (!empty($e->getMessage())) {
                    $error_message .= "(" . $e->getMessage() . ")";
                }
                $this->Flash->error($error_message);
                $conn->rollback();
            }
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Gashas')]);
    }

    /**
     * excel export method
     * @return \Cake\Http\Response
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        /** @var \App\Model\Entity\Gasha[] $gashas */
        $gashas = $this->Gashas->getSearchQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'gashas_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($gashas as $gasha) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $gasha->id);
            // ガシャ開始日
            $data_sheet->setCellValue("B{$row_num}", $gasha->start_date?->i18nFormat('yyyy-MM-dd'));
            // ガシャ終了日
            $data_sheet->setCellValue("C{$row_num}", $gasha->end_date?->i18nFormat('yyyy-MM-dd'));
            // ガシャタイトル
            $data_sheet->setCellValue("D{$row_num}", $gasha->title);
            // SSRレート
            $data_sheet->setCellValue("E{$row_num}", $gasha->ssr_rate);
            // SRレート
            $data_sheet->setCellValue("F{$row_num}", $gasha->sr_rate);
            // 作成日時
            $data_sheet->setCellValue("G{$row_num}", $gasha->created?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            // 更新日時
            $data_sheet->setCellValue("H{$row_num}", $gasha->modified?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            $row_num++;
        }

        // データ入力行のフォーマットを文字列に設定
        $gashas_row_num = count($gashas) + 100;
        $data_sheet->getStyle("A2:H{$gashas_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);


        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:H{$gashas_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $data_sheet->setSelectedCell('A2');
        $data_sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndexByName('DATA');

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $writer = new XlsxWriter($spreadsheet);
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });

        return $this->response->withHeader('Content-Type', EXCEL_CONTENT_TYPE)
        ->withHeader('Content-Disposition', "attachment; filename=\"gashas-{$datetime}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
