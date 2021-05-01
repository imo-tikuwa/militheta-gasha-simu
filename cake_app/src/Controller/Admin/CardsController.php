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
 * Cards Controller
 *
 * @property \App\Model\Table\CardsTable $Cards
 * @property \App\Model\Table\CharactersTable $Characters
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

        $this->loadModel('Characters');

        if (in_array($this->getRequest()->getParam('action'), [ACTION_INDEX, ACTION_ADD, ACTION_EDIT], true)) {
            $character_id_list = $this->Characters->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

            $this->set(compact('character_id_list'));
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
        $query = $this->Cards->getSearchQuery($request);
        $cards = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('cards', 'search_form'));
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
        $card = $this->Cards->get($id, [
            'contain' => [
                'Characters',
                'CardReprints',
                'GashaPickups',
            ]
        ]);

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
            $card = $this->Cards->get($id, [
                'contain' => [
                    'Characters',
                    'CardReprints',
                    'GashaPickups',
                ]
            ]);
            $this->Cards->touch($card);
        } else {
            $card = $this->Cards->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $card = $this->Cards->patchEntity($card, $this->getRequest()->getData(), [
                'associated' => [
                    'Characters',
                    'CardReprints',
                    'GashaPickups',
                ]
            ]);
            if ($card->hasErrors()) {
                $this->Flash->set(implode('<br />', $card->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
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
        $cards = $this->Cards->getSearchQuery($request)->toArray();
        $extract = [
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

                return null;
            },
            // タイプ
            function ($row) {
                if (!empty($row['type'])) {
                    return _code('Codes.Cards.type.' . $row['type']);
                }

                return null;
            },
            // 実装日
            function ($row) {
                return $row['add_date']?->i18nFormat('yyyy-MM-dd');
            },
            // ガシャ対象？
            'gasha_include',
            // 限定？
            function ($row) {
                if (!empty($row['limited'])) {
                    return _code('Codes.Cards.limited.' . $row['limited']);
                }

                return null;
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
        $this->response = $this->response->withDownload("cards-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'cards',
            'header' => $this->Cards->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('cards'));
    }

    /**
     * csv import method
     * @return \Cake\Http\Response|NULL
     */
    public function csvImport()
    {
        $csv_import_file = $this->getRequest()->getUploadedFile('csv_import_file');
        if (!is_null($csv_import_file)) {
            $conn = $this->Cards->getConnection();
            $conn->begin();
            try {
                $csv_data = CsvUtils::parseUtf8Csv($csv_import_file->getStream()->getMetadata('uri'));
                $insert_count = 0;
                $update_count = 0;
                foreach ($csv_data as $index => $csv_row) {
                    if ($index == 0) {
                        if ($this->Cards->getCsvHeaders() != $csv_row) {
                            throw new CakeException('HeaderCheckError');
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
                        throw new CakeException('SaveError');
                    }
                }
                if (!$conn->commit()) {
                    throw new CakeException('CommitError');
                }
                $this->Flash->success("カードCSVの登録が完了しました。<br />新規：{$insert_count}件<br />更新：{$update_count}件", ['escape' => false]);
            } catch (CakeException $e) {
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
     * @return \Cake\Http\Response
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        /** @var \App\Model\Entity\Card[] $cards */
        $cards = $this->Cards->getSearchQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'cards_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $list_sheet = $spreadsheet->getSheetByName('LIST');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($cards as $card) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $card->id);
            // キャラクター
            $cell_value = '';
            if (isset($card->character->id) && isset($card->character->name)) {
                $cell_value = "{$card->character->id}:{$card->character->name}";
            }
            $data_sheet->setCellValue("B{$row_num}", $cell_value);
            // カード名
            $data_sheet->setCellValue("C{$row_num}", $card->name);
            // レアリティ
            $cell_value = '';
            if (isset($card->rarity) && array_key_exists($card->rarity, _code('Codes.Cards.rarity'))) {
                $cell_value = $card->rarity . ':' . _code('Codes.Cards.rarity.' . $card->rarity);
            }
            $data_sheet->setCellValue("D{$row_num}", $cell_value);
            // タイプ
            $cell_value = '';
            if (isset($card->type) && array_key_exists($card->type, _code('Codes.Cards.type'))) {
                $cell_value = $card->type . ':' . _code('Codes.Cards.type.' . $card->type);
            }
            $data_sheet->setCellValue("E{$row_num}", $cell_value);
            // 実装日
            $data_sheet->setCellValue("F{$row_num}", $card->add_date?->i18nFormat('yyyy-MM-dd'));
            // ガシャ対象？
            $cell_value = _code('Codes.Cards.gasha_include.' . $card->gasha_include, 'false');
            $data_sheet->setCellValue("G{$row_num}", $cell_value);
            // 限定？
            $cell_value = '';
            if (isset($card->limited) && array_key_exists($card->limited, _code('Codes.Cards.limited'))) {
                $cell_value = $card->limited . ':' . _code('Codes.Cards.limited.' . $card->limited);
            }
            $data_sheet->setCellValue("H{$row_num}", $cell_value);
            // 作成日時
            $data_sheet->setCellValue("I{$row_num}", $card->created?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            // 更新日時
            $data_sheet->setCellValue("J{$row_num}", $card->modified?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            $row_num++;
        }

        // キャラクターの一覧を取得、選択肢情報を設定
        $characters = $this->Characters->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();
        if (!is_null($characters) && count($characters) > 0) {
            $row_num = 2;
            foreach ($characters as $selection_key => $selection_value) {
                $list_sheet->setCellValue("A{$row_num}", "{$selection_key}:{$selection_value}");
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
        $cards_row_num = count($cards) + 100;
        $data_sheet->getStyle("A2:J{$cards_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // データ入力行に入力規則を設定（1048576はExcelの最大行数）
        // キャラクター
        $data_sheet->setDataValidation('B2:B1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$A\$2,0,0,COUNTA('LIST'!\$A:\$A)-1,1)"));
        // レアリティ
        $data_sheet->setDataValidation('D2:D1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$B\$2,0,0,COUNTA('LIST'!\$B:\$B)-1,1)"));
        // タイプ
        $data_sheet->setDataValidation('E2:E1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$C\$2,0,0,COUNTA('LIST'!\$C:\$C)-1,1)"));
        // ガシャ対象？
        $data_sheet->setDataValidation('G2:G1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$D\$2,0,0,COUNTA('LIST'!\$D:\$D)-1,1)"));
        // 限定？
        $data_sheet->setDataValidation('H2:H1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$E\$2,0,0,COUNTA('LIST'!\$E:\$E)-1,1)"));

        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:J{$cards_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $data_sheet->setSelectedCell('A2');
        $data_sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndexByName('DATA');

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $writer = new XlsxWriter($spreadsheet);
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });

        return $this->response->withHeader('Content-Type', EXCEL_CONTENT_TYPE)
        ->withHeader('Content-Disposition', "attachment; filename=\"cards-{$datetime}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
