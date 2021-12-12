<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use App\Model\Entity\Gasha;
use App\Utils\ExcelUtils;
use Cake\Event\EventInterface;
use Cake\Http\CallbackStream;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use DateTime;
use DateTimeZone;

/**
 * CardReprints Controller
 *
 * @property \App\Model\Table\CardReprintsTable $CardReprints
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\CardReprint[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardReprintsController extends AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\Admin\AppController::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        $result = parent::beforeFilter($event);
        if (!is_null($result) && $result instanceof \Cake\Http\Response) {
            return $result;
        }

        $this->Gashas = $this->fetchTable('Gashas');
        $this->Cards = $this->fetchTable('Cards');

        if (in_array($this->getRequest()->getParam('action'), [ACTION_INDEX, ACTION_ADD, ACTION_EDIT], true)) {
            $gasha_id_list = $this->Gashas->find('list', [
                'keyField' => 'id',
                'valueField' => function (Gasha $gasha) {
                    return $gasha->start_date->i18nFormat('yyyy/MM/dd') . '　' . $gasha->title;
                }
            ])
            ->where(['title LIKE' => '【限定復刻】%'])
            ->order(['id' => 'DESC'])->toArray();
            $card_id_list = $this->Cards->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

            $this->set(compact('gasha_id_list', 'card_id_list'));
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
        $query = $this->CardReprints->getSearchQuery($request);
        $card_reprints = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('card_reprints', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Card Reprint id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $card_reprint = $this->CardReprints->get($id, [
            'contain' => [
                'Gashas',
                'Cards',
            ]
        ]);

        $this->set('card_reprint', $card_reprint);
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
     * @param string|null $id 復刻情報ID
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
     * @param string|null $id 復刻情報ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $card_reprint = $this->CardReprints->get($id, [
                'contain' => [
                    'Gashas',
                    'Cards',
                ]
            ]);
            $this->CardReprints->touch($card_reprint);
        } else {
            $card_reprint = $this->CardReprints->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $card_reprint = $this->CardReprints->patchEntity($card_reprint, $this->getRequest()->getData(), [
                'associated' => [
                    'Gashas',
                    'Cards',
                ]
            ]);
            if ($card_reprint->hasErrors()) {
                $this->Flash->set(implode('<br />', $card_reprint->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->CardReprints->getConnection();
                $conn->begin();
                if ($this->CardReprints->save($card_reprint, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('復刻情報の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.CardReprints')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('card_reprint'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 復刻情報ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Cake\Http\Exception\MethodNotAllowedException When request method invalid.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod('delete');
        $entity = $this->CardReprints->get($id);
        if ($this->CardReprints->delete($entity)) {
            $this->Flash->success('復刻情報の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.CardReprints')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $card_reprints = $this->CardReprints->getSearchQuery($request)->toArray();
        $extract = [
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
                return $row['created']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            // 更新日時
            function ($row) {
                return $row['modified']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $this->response = $this->response->withDownload("card_reprints-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'card_reprints',
            'header' => $this->CardReprints->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('card_reprints'));
    }

    /**
     * excel export method
     * @return \Cake\Http\Response
     */
    public function excelExport()
    {
        $request = $this->getRequest()->getQueryParams();
        /** @var \App\Model\Entity\CardReprint[] $card_reprints */
        $card_reprints = $this->CardReprints->getSearchQuery($request)->toArray();

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(EXCEL_TEMPLATE_DIR . 'card_reprints_template.xlsx');
        $data_sheet = $spreadsheet->getSheetByName('DATA');
        $list_sheet = $spreadsheet->getSheetByName('LIST');
        $row_num = 2;

        // 取得したデータをExcelに書き込む
        foreach ($card_reprints as $card_reprint) {
            // ID
            $data_sheet->setCellValue("A{$row_num}", $card_reprint->id);
            // ガシャID
            $cell_value = '';
            if (isset($card_reprint->gasha->id) && isset($card_reprint->gasha->title)) {
                $cell_value = "{$card_reprint->gasha->id}:{$card_reprint->gasha->title}";
            }
            $data_sheet->setCellValue("B{$row_num}", $cell_value);
            // カードID
            $cell_value = '';
            if (isset($card_reprint->card->id) && isset($card_reprint->card->name)) {
                $cell_value = "{$card_reprint->card->id}:{$card_reprint->card->name}";
            }
            $data_sheet->setCellValue("C{$row_num}", $cell_value);
            // 作成日時
            $data_sheet->setCellValue("D{$row_num}", $card_reprint->created?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            // 更新日時
            $data_sheet->setCellValue("E{$row_num}", $card_reprint->modified?->i18nFormat('yyyy-MM-dd HH:mm:ss'));
            $row_num++;
        }

        // ガシャIDの一覧を取得、選択肢情報を設定
        $gashas = $this->Gashas->find('list', ['keyField' => 'id', 'valueField' => 'title'])->toArray();
        if (!is_null($gashas) && count($gashas) > 0) {
            $row_num = 2;
            foreach ($gashas as $selection_key => $selection_value) {
                $list_sheet->setCellValue("A{$row_num}", "{$selection_key}:{$selection_value}");
                $row_num++;
            }
        }

        // カードIDの一覧を取得、選択肢情報を設定
        $cards = $this->Cards->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();
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
        $card_reprints_row_num = count($card_reprints) + 100;
        $data_sheet->getStyle("A2:E{$card_reprints_row_num}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // データ入力行に入力規則を設定（1048576はExcelの最大行数）
        // ガシャID
        $data_sheet->setDataValidation('B2:B1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$A\$2,0,0,COUNTA('LIST'!\$A:\$A)-1,1)"));
        // カードID
        $data_sheet->setDataValidation('C2:C1048576', ExcelUtils::getDataValidation("=OFFSET('LIST'!\$B\$2,0,0,COUNTA('LIST'!\$B:\$B)-1,1)"));

        // 罫線設定、A2セルを選択、1行目固定、DATAシートをアクティブ化
        $data_sheet->getStyle("A1:E{$card_reprints_row_num}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $data_sheet->setSelectedCell('A2');
        $data_sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndexByName('DATA');

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $writer = new XlsxWriter($spreadsheet);
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });

        return $this->response->withHeader('Content-Type', EXCEL_CONTENT_TYPE)
        ->withHeader('Content-Disposition', "attachment; filename=\"card_reprints-{$datetime}.xlsx\"")
        ->withHeader('Cache-Control', 'max-age=0')
        ->withBody($stream);
    }
}
