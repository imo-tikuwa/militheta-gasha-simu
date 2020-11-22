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
 * Cards Controller
 *
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardsController extends AppController
{

    /**
     * Initialize Method.
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        if (!in_array($this->getRequest()->getParam('action'), ['delete', 'csvExport', 'csvImport', 'excelExport'], true)) {
            // キャラクターの選択肢
            $this->set('characters', $this->Cards->findForeignSelectionData('Characters', 'name', true));
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
        $cards = $this->paginate($query);

        $this->set(compact('cards'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Cards->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Cards->aliasField('id') => $request['id']]);
        }
        // キャラクター
        if (isset($request['character_id']) && !is_null($request['character_id']) && $request['character_id'] !== '') {
            $query->where(['Characters.id' => $request['character_id']]);
        }
        // カード名
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->Cards->aliasField('name LIKE') => "%{$request['name']}%"]);
        }
        // レアリティ
        if (isset($request['rarity']) && !is_null($request['rarity']) && $request['rarity'] !== '') {
            $query->where([$this->Cards->aliasField('rarity') => $request['rarity']]);
        }
        // タイプ
        if (isset($request['type']) && !is_null($request['type']) && $request['type'] !== '') {
            $query->where([$this->Cards->aliasField('type') => $request['type']]);
        }
        // 実装日
        if (isset($request['add_date']) && !is_null($request['add_date']) && $request['add_date'] !== '') {
            $query->where([$this->Cards->aliasField('add_date') => $request['add_date']]);
        }
        // ガシャ対象？
        if (isset($request['gasha_include']) && !is_null($request['gasha_include']) && $request['gasha_include'] !== '') {
            $query->where([$this->Cards->aliasField('gasha_include') => $request['gasha_include']]);
        }
        // 限定？
        if (isset($request['limited']) && !is_null($request['limited']) && $request['limited'] !== '') {
            $query->where([$this->Cards->aliasField('limited') => $request['limited']]);
        }
        // フリーワード
        if (isset($request['search_snippet']) && !is_null($request['search_snippet']) && $request['search_snippet'] !== '') {
            $search_snippet_conditions = [];
            foreach (explode(' ', str_replace('　', ' ', $request['search_snippet'])) as $search_snippet) {
                $search_snippet_conditions[] = [$this->Cards->aliasField('search_snippet LIKE') => "%{$search_snippet}%"];
            }
            if (isset($request['search_snippet_format']) && $request['search_snippet_format'] == 'AND') {
                $query->where($search_snippet_conditions);
            } else {
                $query->where(function ($exp) use ($search_snippet_conditions) {
                    return $exp->or($search_snippet_conditions);
                });
            }
        }
        $query->group('Cards.id');

        return $query->contain(['Characters', 'CardReprints', 'GashaPickups']);
    }

    /**
     * View method
     *
     * @param string|null $id Card id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $card = $this->Cards->get($id, ['contain' => ['Characters', 'CardReprints', 'GashaPickups']]);

        $this->set('card', $card);
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
     * @param string|null $id カードID
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
     * @param string|null $id カードID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $card = $this->Cards->get($id);
            $this->Cards->touch($card);
        } else {
            $card = $this->Cards->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $card = $this->Cards->patchEntity($card, $this->getRequest()->getData(), ['associated' => ['Characters', 'CardReprints', 'GashaPickups']]);
            if (!$card->hasErrors()) {
                $conn = $this->Cards->getConnection();
                $conn->begin();
                if ($this->Cards->save($card, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('カードの登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Cards')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('card'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id カードID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $entity = $this->Cards->get($id);
        if ($this->Cards->delete($entity)) {
            $this->Flash->success('カードの削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Cards')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $cards = $this->_getQuery($request)->toArray();
        $_serialize = 'cards';
        $_header = $this->Cards->getCsvHeaders();
        $_extract = [
            // ID
            'id',
            // キャラクター
            function ($row) {
                return @$row['character']['name'];
            },
            // カード名
            'name',
            // レアリティ
            function ($row) {
                if (!empty($row['rarity'])) {
                    return _code('Codes.Cards.rarity.' . $row['rarity']);
                }

                return "";
            },
            // タイプ
            function ($row) {
                if (!empty($row['type'])) {
                    return _code('Codes.Cards.type.' . $row['type']);
                }

                return "";
            },
            // 実装日
            function ($row) {
                if ($row['add_date'] instanceof FrozenDate) {
                    return @$row['add_date']->i18nFormat('yyyy-MM-dd');
                }

                return "";
            },
            // ガシャ対象？
            'gasha_include',
            // 限定？
            function ($row) {
                if (!empty($row['limited'])) {
                    return _code('Codes.Cards.limited.' . $row['limited']);
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
        $this->response = $this->response->withDownload("cards-{$datetime->format('YmdHis')}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('cards', '_serialize', '_header', '_extract', '_csvEncoding'));
    }

    /**
     * csv import method
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport()
    {
        $csv_import_file = @$_FILES["csv_import_file"]["tmp_name"];
        if (is_uploaded_file($csv_import_file)) {
            $conn = $this->Cards->getConnection();
            $conn->begin();
            try {
                $csv_data = CsvUtils::parseUtf8Csv($csv_import_file);
                $insert_count = 0;
                $update_count = 0;
                foreach ($csv_data as $index => $csv_row) {
                    if ($index == 0) {
                        if ($this->Cards->getCsvHeaders() != $csv_row) {
                            throw new \Exception('HeaderCheckError');
                        }
                        continue;
                    }

                    $card = $this->Cards->createEntityByCsvRow($csv_row);
                    if ($card->isNew()) {
                        $insert_count++;
                    } else {
                        $update_count++;
                    }
                    if (!$this->Cards->save($card, ['atomic' => false])) {
                        throw new \Exception('SaveError');
                    }
                }
                if (!$conn->commit()) {
                    throw new \Exception('CommitError');
                }
                $this->Flash->success("カードCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['params' => ['escape' => false]]);
            } catch (\Exception $e) {
                $error_message = 'カードCSVの登録でエラーが発生しました。';
                if (!empty($e->getMessage())) {
                    $error_message .= "(" . $e->getMessage() . ")";
                }
                $this->Flash->error($error_message);
                $conn->rollback();
            }
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Cards')]);
    }

    /**
     * excel export method
     * @return void
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $cards = $this->_getQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'cards_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $list_sheet = $spreadsheet->getSheetByName('LIST');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($cards as $card) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $card['id']);
            // キャラクター
            $cell_value = "";
            if (isset($card['character']['id']) && isset($card['character']['name'])) {
                $cell_value = "{$card['character']['id']}:{$card['character']['name']}";
            }
            $data_sheet->setCellValue("B{$row_num}", $cell_value);
            // カード名
            $data_sheet->setCellValue("C{$row_num}", $card['name']);
            // レアリティ
            $cell_value = "";
            if (isset($card['rarity']) && array_key_exists($card['rarity'], _code('Codes.Cards.rarity'))) {
                $cell_value = $card['rarity'] . ':' . _code('Codes.Cards.rarity.' . $card['rarity']);
            }
            $data_sheet->setCellValue("D{$row_num}", $cell_value);
            // タイプ
            $cell_value = "";
            if (isset($card['type']) && array_key_exists($card['type'], _code('Codes.Cards.type'))) {
                $cell_value = $card['type'] . ':' . _code('Codes.Cards.type.' . $card['type']);
            }
            $data_sheet->setCellValue("E{$row_num}", $cell_value);
            // 実装日
            $cell_value = @$card['add_date']->i18nFormat('yyyy-MM-dd');
            $data_sheet->setCellValue("F{$row_num}", $cell_value);
            // ガシャ対象？
            $cell_value = _code('Codes.Cards.gasha_include.' . $card['gasha_include'], 'false');
            $data_sheet->setCellValue("G{$row_num}", $cell_value);
            // 限定？
            $cell_value = "";
            if (isset($card['limited']) && array_key_exists($card['limited'], _code('Codes.Cards.limited'))) {
                $cell_value = $card['limited'] . ':' . _code('Codes.Cards.limited.' . $card['limited']);
            }
            $data_sheet->setCellValue("H{$row_num}", $cell_value);
            // 作成日時
            $cell_value = @$card['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("I{$row_num}", $cell_value);
            // 更新日時
            $cell_value = @$card['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $data_sheet->setCellValue("J{$row_num}", $cell_value);
            $row_num++;
        }

        // キャラクターの一覧を取得、選択肢情報を設定
        $characters = $this->Cards->findForeignSelectionData('Characters', 'name');
        if (!is_null($characters) && count($characters) > 0) {
            $row_num = 2;
            foreach ($characters as $selection_key => $selection_value) {
                $list_sheet->setCellValue("A{$row_num}", "{$selection_key}:{$selection_value}");
                $row_num++;
            }
        }

        // データ入力行のフォーマットを文字列に設定
        $cards_row_num = count($cards) + 100;
        $data_sheet->getStyle("A2:J{$cards_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // データ入力行に入力規則を設定（1048576はExcelの最大行数）
        // キャラクター
        $data_sheet->setDataValidation("B2:B1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$A\$2,0,0,COUNTA('LIST'!\$A:\$A)-1,1)"));
        // レアリティ
        $data_sheet->setDataValidation("D2:D1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$B\$2,0,0,COUNTA('LIST'!\$B:\$B)-1,1)"));
        // タイプ
        $data_sheet->setDataValidation("E2:E1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$C\$2,0,0,COUNTA('LIST'!\$C:\$C)-1,1)"));
        // ガシャ対象？
        $data_sheet->setDataValidation("G2:G1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$D\$2,0,0,COUNTA('LIST'!\$D:\$D)-1,1)"));
        // 限定？
        $data_sheet->setDataValidation("H2:H1048576", ExcelUtils::getDataValidation("=OFFSET('LIST'!\$E\$2,0,0,COUNTA('LIST'!\$E:\$E)-1,1)"));

        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:J{$cards_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $data_sheet->setSelectedCell('A2');
        $data_sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndexByName('DATA');

        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('Asia/Tokyo'));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename=\"cards-{$datetime->format('YmdHis')}.xlsx\"");
        header('Cache-Control: max-age=0');
        $writer = new XlsxWriter($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
