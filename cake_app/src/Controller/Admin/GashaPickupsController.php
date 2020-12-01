<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Utils\ExcelUtils;
use Cake\Http\CallbackStream;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

/**
 * GashaPickups Controller
 *
 * @property \App\Model\Table\GashaPickupsTable $GashaPickups
 *
 * @method \App\Model\Entity\GashaPickup[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashaPickupsController extends AppController
{

    /**
     * Initialize Method.
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        if (!in_array($this->getRequest()->getParam('action'), [ACTION_DELETE, ACTION_CSV_EXPORT, ACTION_EXCEL_EXPORT], true)) {
            // ガシャIDの選択肢
            $this->Gashas = TableRegistry::getTableLocator()->get('Gashas');
            $gashas = $this->Gashas->find()->select(['id', 'title', 'start_date'])->enableHydration(false)->order(['id' => 'DESC'])->toArray();
            if (!empty($gashas)) {
                $gashas = Hash::combine($gashas, '{n}.id', ['%s　%s', '{n}.start_date', '{n}.title']);
                $gashas = ["" => "　"] + $gashas;
            }
            $this->set('gashas', $gashas);
            // カードIDの選択肢
            $this->set('cards', $this->GashaPickups->findForeignSelectionData('Cards', 'name', true));
        }
    }

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
        $gasha_pickups = $this->paginate($query);

        $this->set(compact('gasha_pickups'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->GashaPickups->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->GashaPickups->aliasField('id') => $request['id']]);
        }
        // ガシャID
        if (isset($request['gasha_id']) && !is_null($request['gasha_id']) && $request['gasha_id'] !== '') {
            $query->where(['Gashas.id' => $request['gasha_id']]);
        }
        // カードID
        if (isset($request['card_id']) && !is_null($request['card_id']) && $request['card_id'] !== '') {
            $query->where(['Cards.id' => $request['card_id']]);
        }
        // フリーワード
        if (isset($request['search_snippet']) && !is_null($request['search_snippet']) && $request['search_snippet'] !== '') {
            $search_snippet_conditions = [];
            foreach (explode(' ', str_replace('　', ' ', $request['search_snippet'])) as $search_snippet) {
                $search_snippet_conditions[] = [$this->GashaPickups->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function ($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }
        $query->group('GashaPickups.id');

        return $query->contain(['Gashas', 'Cards']);
    }

    /**
     * View method
     *
     * @param string|null $id Gasha Pickup id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gashaPickup = $this->GashaPickups->get($id, ['contain' => ['Gashas', 'Cards']]);

        $this->set('gashaPickup', $gashaPickup);
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
     * @param string|null $id ピックアップ情報ID
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
     * @param string|null $id ピックアップ情報ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $gasha_pickup = $this->GashaPickups->get($id);
            $this->GashaPickups->touch($gasha_pickup);
        } else {
            $gasha_pickup = $this->GashaPickups->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $gasha_pickup = $this->GashaPickups->patchEntity($gasha_pickup, $this->getRequest()->getData(), ['associated' => ['Gashas', 'Cards']]);
            if (!$gasha_pickup->hasErrors()) {
                $conn = $this->GashaPickups->getConnection();
                $conn->begin();
                if ($this->GashaPickups->save($gasha_pickup, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('ピックアップ情報の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.GashaPickups')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('gasha_pickup'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id ピックアップ情報ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $entity = $this->GashaPickups->get($id);
        if ($this->GashaPickups->delete($entity)) {
            $this->Flash->success('ピックアップ情報の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.GashaPickups')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $gasha_pickups = $this->_getQuery($request)->toArray();
        $_extract = [
            // ID
            'id',
            // ガシャID
            function ($row) {
                return @$row['gasha']['title'];
            },
            // カードID
            function ($row) {
                return @$row['card']['name'];
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

        $this->response = $this->response->withDownload("gasha_pickups-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'gasha_pickups',
            'header' => $this->GashaPickups->getCsvHeaders(),
            'extract' => $_extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('gasha_pickups'));
    }

    /**
     * excel export method
     * @return void
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $gasha_pickups = $this->_getQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'gasha_pickups_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $list_sheet = $spreadsheet->getSheetByName('LIST');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($gasha_pickups as $gasha_pickup) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $gasha_pickup['id']);
            // ガシャID
            $cell_value = "";
            if (isset($gasha_pickup['gasha']['id']) && isset($gasha_pickup['gasha']['title'])) {
                $cell_value = "{$gasha_pickup['gasha']['id']}:{$gasha_pickup['gasha']['title']}";
            }
            $data_sheet->setCellValue("B{$row_num}", $cell_value);
            // カードID
            $cell_value = "";
            if (isset($gasha_pickup['card']['id']) && isset($gasha_pickup['card']['name'])) {
                $cell_value = "{$gasha_pickup['card']['id']}:{$gasha_pickup['card']['name']}";
            }
            $data_sheet->setCellValue("C{$row_num}", $cell_value);
            // 作成日時
            $cell_value = @$gasha_pickup['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("D{$row_num}", $cell_value);
            // 更新日時
            $cell_value = @$gasha_pickup['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("E{$row_num}", $cell_value);
            $row_num++;
        }

        // ガシャIDの一覧を取得、選択肢情報を設定
        $gashas = $this->GashaPickups->findForeignSelectionData('Gashas', 'title');
        if (!is_null($gashas) && count($gashas) > 0) {
            $row_num = 2;
            foreach ($gashas as $selection_key => $selection_value) {
                $list_sheet->setCellValue("A{$row_num}", "{$selection_key}:{$selection_value}");
                $row_num++;
            }
        }

        // カードIDの一覧を取得、選択肢情報を設定
        $cards = $this->GashaPickups->findForeignSelectionData('Cards', 'name');
        if (!is_null($cards) && count($cards) > 0) {
            $row_num = 2;
            foreach ($cards as $selection_key => $selection_value) {
                $list_sheet->setCellValue("B{$row_num}", "{$selection_key}:{$selection_value}");
                $row_num++;
            }
        }

        // LISTシートの選択肢項目を罫線で囲む
        $list_sheet->getStyle("A1:{$list_sheet->getHighestColumn()}{$list_sheet->getHighestRow()}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);
        $list_sheet->setSelectedCell('A1');

        // データ入力行のフォーマットを文字列に設定
        $gasha_pickups_row_num = count($gasha_pickups) + 100;
        $data_sheet->getStyle("A2:E{$gasha_pickups_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // データ入力行に入力規則を設定（1048576はExcelの最大行数）
        // ガシャID
        $data_sheet->setDataValidation("B2:B1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$A\$2,0,0,COUNTA('LIST'!\$A:\$A)-1,1)"));
        // カードID
        $data_sheet->setDataValidation("C2:C1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$B\$2,0,0,COUNTA('LIST'!\$B:\$B)-1,1)"));

        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:E{$gasha_pickups_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
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
        ->withHeader('Content-Disposition', "attachment; filename=\"gasha_pickups-{$datetime->format('YmdHis')}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
