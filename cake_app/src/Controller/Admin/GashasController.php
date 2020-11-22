<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Utils\CsvUtils;
use App\Utils\ExcelUtils;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

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
        $this->set('params', $request);
        $query = $this->_getQuery($request);
        $gashas = $this->paginate($query);

        $this->set(compact('gashas'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Gashas->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Gashas->aliasField('id') => $request['id']]);
        }
        // ガシャ開始日
        if (isset($request['start_date']) && !is_null($request['start_date']) && $request['start_date'] !== '') {
            $query->where([$this->Gashas->aliasField('start_date >=') => $request['start_date']]);
        }
        // ガシャ終了日
        if (isset($request['end_date']) && !is_null($request['end_date']) && $request['end_date'] !== '') {
            $query->where([$this->Gashas->aliasField('end_date <=') => $request['end_date']]);
        }
        // ガシャタイトル
        if (isset($request['title']) && !is_null($request['title']) && $request['title'] !== '') {
            $query->where([$this->Gashas->aliasField('title LIKE') => "%{$request['title']}%"]);
        }
        // フリーワード
        if (isset($request['search_snippet']) && !is_null($request['search_snippet']) && $request['search_snippet'] !== '') {
            $search_snippet_conditions = [];
            foreach (explode(' ', str_replace('　', ' ', $request['search_snippet'])) as $search_snippet) {
                $search_snippet_conditions[] = [$this->Gashas->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function ($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }

        return $query;
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
            $gasha = $this->Gashas->get($id);
            $this->Gashas->touch($gasha);
        } else {
            $gasha = $this->Gashas->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $gasha = $this->Gashas->patchEntity($gasha, $this->getRequest()->getData(), ['associated' => ['CardReprints', 'GashaPickups']]);
            if (!$gasha->hasErrors()) {
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
        $gashas = $this->_getQuery($request)->toArray();
        $_serialize = 'gashas';
        $_header = $this->Gashas->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // ガシャ開始日
            function ($row) {
                if ($row['start_date'] instanceof FrozenDate) {
                    return @$row['start_date']->i18nFormat('yyyy-MM-dd');
                }

                return "";
            },
            // ガシャ終了日
            function ($row) {
                if ($row['end_date'] instanceof FrozenDate) {
                    return @$row['end_date']->i18nFormat('yyyy-MM-dd');
                }

                return "";
            },
            // ガシャタイトル
            'title',
            // SSRレート
            function ($row) {
                if (!empty($row['ssr_rate'])) {
                    return $row['ssr_rate'] . "%";
                }

                return "";
            },
            // SRレート
            function ($row) {
                if (!empty($row['sr_rate'])) {
                    return $row['sr_rate'] . "%";
                }

                return "";
            },
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

        $_csvEncoding = 'UTF-8';
        $this->response = $this->response->withDownload("gashas-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('gashas', '_serialize', '_header', '_extract', '_csvEncoding'));
    }

    /**
     * csv import method
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport()
    {
        $csv_import_file = @$_FILES["csv_import_file"]["tmp_name"];
        if (is_uploaded_file($csv_import_file)) {
            $conn = $this->Gashas->getConnection();
            $conn->begin();
            try {
                $csv_data = CsvUtils::parseUtf8Csv($csv_import_file);
                $insert_count = 0;
                $update_count = 0;
                foreach ($csv_data as $index => $csv_row) {
                    if ($index == 0) {
                        if ($this->Gashas->getCsvHeaders() != $csv_row) {
                            throw new \Exception('HeaderCheckError');
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
                        throw new \Exception('SaveError');
                    }
                }
                if (!$conn->commit()) {
                    throw new \Exception('CommitError');
                }
                $this->Flash->success("ガシャCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['params' => ['escape' => false]]);
            } catch (\Exception $e) {
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
     * @return void
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $gashas = $this->_getQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'gashas_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($gashas as $gasha) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $gasha['id']);
            // ガシャ開始日
            $cell_value = @$gasha['start_date']->i18nFormat('yyyy-MM-dd');
            $data_sheet->setCellValue("B{$row_num}", $cell_value);
            // ガシャ終了日
            $cell_value = @$gasha['end_date']->i18nFormat('yyyy-MM-dd');
            $data_sheet->setCellValue("C{$row_num}", $cell_value);
            // ガシャタイトル
            $data_sheet->setCellValue("D{$row_num}", $gasha['title']);
            // SSRレート
            $data_sheet->setCellValue("E{$row_num}", $gasha['ssr_rate']);
            // SRレート
            $data_sheet->setCellValue("F{$row_num}", $gasha['sr_rate']);
            // 作成日時
            $cell_value = @$gasha['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("G{$row_num}", $cell_value);
            // 更新日時
            $cell_value = @$gasha['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("H{$row_num}", $cell_value);
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

        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('Asia/Tokyo'));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename=\"gashas-{$datetime->format('YmdHis')}.xlsx\"");
        header('Cache-Control: max-age=0');
        $writer = new XlsxWriter($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
