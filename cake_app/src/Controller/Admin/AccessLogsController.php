<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use OperationLogs\Util\OperationLogsUtils;

/**
 * operation_logs_(hourly,daily,monthly)のデータを元にグラフを表示するコントローラ
 */
class AccessLogsController extends AppController
{
    /**
     * バーチャートのカラーコード表
     * https://color.adobe.com/ja/create
     * で作成
     *
     * @var array<string>
     */
    private $rgb_colors = [
        '110, 59, 246',
        '77, 247, 240',
        '164, 250, 65',
        '255, 195, 56',
        '222, 48, 42',
        '237, 79, 245',
        '96, 154, 247',
        '255, 246, 77',
        '222, 125, 60',
        '180, 180, 180',
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $params = $this->getRequest()->getQueryParams();
        $this->set(compact('params'));

        // 検索対象日
        $target_date = null;
        if (isset($params['target_date'])) {
            $target_date = new \DateTime($params['target_date']);
        }
        if (empty($target_date)) {
            $target_date = new \DateTime();
        }

        // 集計データを取得
        $graph_data = null;
        if (isset($params['date_type'])) {
            $graph_data_all = $this->findAllData($params['date_type'], $target_date);
            $graph_data_ip = $this->findDataGroupedByIp($params['date_type'], $target_date);
            $graph_data_ua = $this->findDataGroupedByUa($params['date_type'], $target_date);
            $graph_data_url = $this->findDataGroupedByUrl($params['date_type'], $target_date);
            $this->set(compact('graph_data_all', 'graph_data_ip', 'graph_data_ua', 'graph_data_url'));
        }
    }

    /**
     * 集計タイプ = 全のときのChartJs用のチャートデータを返す
     *
     * @param string $date_type 絞込の日付タイプ
     * @param \DateTime $target_date 対象日
     * @return null|string
     */
    private function findAllData($date_type, \DateTime $target_date)
    {
        $graph_data = null;
        $result = null;
        switch ($date_type) {
            case OL_DATE_TYPE_HOURLY:
                $result = OperationLogsUtils::findHourlySummaryLogs(OL_SUMMARY_TYPE_ALL, $target_date);
                break;
            case OL_DATE_TYPE_DAILY:
                $result = OperationLogsUtils::findDailySummaryLogs(OL_SUMMARY_TYPE_ALL, $target_date);
                break;
            case OL_DATE_TYPE_MONTHLY:
                $result = OperationLogsUtils::findMonthlySummaryLogs(OL_SUMMARY_TYPE_ALL, $target_date);
                break;
        }

        // allのときはKeyが0固定
        if (isset($result) && !empty($result)) {
            $graph_data = [];
            $graph_data['labels'] = array_keys($result[0]);
            $graph_data['datasets'] = [];
            $graph_data['datasets'][] = [
                'label' => _code("OperationLogs.date_type_jp.{$date_type}"),
                'backgroundColor' => "rgba({$this->rgb_colors[9]},0.9)",
                'borderColor' => "rgba({$this->rgb_colors[9]},0.8)",
                'data' => array_values($result[0]),
            ];
            $graph_data = json_encode($graph_data);
            assert($graph_data !== false);
        }

        return $graph_data;
    }

    /**
     * 集計タイプ = IPのときのチャートデータを返す
     *
     * @param string $date_type 絞込の日付タイプ
     * @param \DateTime $target_date 対象日
     * @return null|string
     */
    private function findDataGroupedByIp($date_type, \DateTime $target_date)
    {
        $graph_data = null;
        $result = null;
        switch ($date_type) {
            case OL_DATE_TYPE_HOURLY:
                $result = OperationLogsUtils::findHourlySummaryLogs(OL_SUMMARY_TYPE_IP, $target_date);
                break;
            case OL_DATE_TYPE_DAILY:
                $result = OperationLogsUtils::findDailySummaryLogs(OL_SUMMARY_TYPE_IP, $target_date);
                break;
            case OL_DATE_TYPE_MONTHLY:
                $result = OperationLogsUtils::findMonthlySummaryLogs(OL_SUMMARY_TYPE_IP, $target_date);
                break;
        }

        if (isset($result) && !empty($result)) {
            // いったんカウントの多い順にソートする
            foreach ($result as $ip => $data) {
                $sort_array[$ip] = array_sum(array_values($data));
            }
            array_multisort($sort_array, SORT_DESC, $result);

            // 10件目以降はその他のグループにまとめ、最大10件まで表示する
            if (count($result) > 10) {
                $tmp_result = [];
                $loop_count = 0;
                foreach ($result as $ip => $data) {
                    $loop_count++;
                    if ($loop_count == 10) {
                        $tmp_result['その他'] = $data;
                    } elseif ($loop_count > 10) {
                        foreach ($data as $key => $count) {
                            $tmp_result['その他'][$key] += $count;
                        }
                    } else {
                        $tmp_result[$ip] = $data;
                    }
                }
                $result = $tmp_result;
            }

            $graph_data = [];
            foreach ($result as $ip => $data) {
                $graph_data['labels'] = array_keys($data);
                break;
            }
            $graph_data['datasets'] = [];
            $chart_index = 0;
            foreach ($result as $ip => $data) {
                $graph_data['datasets'][] = [
                    'label' => $ip,
                    'backgroundColor' => "rgba({$this->rgb_colors[$chart_index]},0.9)",
                    'borderColor' => "rgba({$this->rgb_colors[$chart_index]},0.8)",
                    'data' => array_values($data),
                ];
                $chart_index++;
            }

            $graph_data = json_encode($graph_data);
            assert($graph_data !== false);
        }

        return $graph_data;
    }

    /**
     * 集計タイプ = UserAgentのときのチャートデータを返す
     *
     * @param string $date_type 絞込の日付タイプ
     * @param \DateTime $target_date 対象日
     * @return null|string
     */
    private function findDataGroupedByUa($date_type, \DateTime $target_date)
    {
        $graph_data = null;
        $result = null;
        switch ($date_type) {
            case OL_DATE_TYPE_HOURLY:
                $result = OperationLogsUtils::findHourlySummaryLogs(OL_SUMMARY_TYPE_USER_AGENT, $target_date);
                break;
            case OL_DATE_TYPE_DAILY:
                $result = OperationLogsUtils::findDailySummaryLogs(OL_SUMMARY_TYPE_USER_AGENT, $target_date);
                break;
            case OL_DATE_TYPE_MONTHLY:
                $result = OperationLogsUtils::findMonthlySummaryLogs(OL_SUMMARY_TYPE_USER_AGENT, $target_date);
                break;
        }

        if (isset($result) && !empty($result)) {
            // いったんカウントの多い順にソートする
            foreach ($result as $ua => $data) {
                $sort_array[$ua] = array_sum(array_values($data));
            }
            array_multisort($sort_array, SORT_DESC, $result);

            // 10件目以降はその他のグループにまとめ、最大10件まで表示する
            if (count($result) > 10) {
                $tmp_result = [];
                $loop_count = 0;
                foreach ($result as $ua => $data) {
                    $loop_count++;
                    if ($loop_count == 10) {
                        $tmp_result['その他'] = $data;
                    } elseif ($loop_count > 10) {
                        foreach ($data as $key => $count) {
                            $tmp_result['その他'][$key] += $count;
                        }
                    } else {
                        $tmp_result[$ua] = $data;
                    }
                }
                $result = $tmp_result;
            }

            $graph_data = [];
            foreach ($result as $ua => $data) {
                $graph_data['labels'] = array_keys($data);
                break;
            }
            $graph_data['datasets'] = [];
            $chart_index = 0;
            foreach ($result as $ua => $data) {
                $graph_data['datasets'][] = [
                    'label' => $ua,
                    'backgroundColor' => "rgba({$this->rgb_colors[$chart_index]},0.9)",
                    'borderColor' => "rgba({$this->rgb_colors[$chart_index]},0.8)",
                    'data' => array_values($data),
                ];
                $chart_index++;
            }

            $graph_data = json_encode($graph_data);
            assert($graph_data !== false);
        }

        return $graph_data;
    }

    /**
     * 集計タイプ = URLのときのチャートデータを返す
     *
     * @param string $date_type 絞込の日付タイプ
     * @param \DateTime $target_date 対象日
     * @return null|string
     */
    private function findDataGroupedByUrl($date_type, \DateTime $target_date)
    {
        $graph_data = null;
        $result = null;
        switch ($date_type) {
            case OL_DATE_TYPE_HOURLY:
                $result = OperationLogsUtils::findHourlySummaryLogs(OL_SUMMARY_TYPE_URL, $target_date);
                break;
            case OL_DATE_TYPE_DAILY:
                $result = OperationLogsUtils::findDailySummaryLogs(OL_SUMMARY_TYPE_URL, $target_date);
                break;
            case OL_DATE_TYPE_MONTHLY:
                $result = OperationLogsUtils::findMonthlySummaryLogs(OL_SUMMARY_TYPE_URL, $target_date);
                break;
        }

        if (isset($result) && !empty($result)) {
            // いったんカウントの多い順にソートする
            foreach ($result as $url => $data) {
                $sort_array[$url] = array_sum(array_values($data));
            }
            array_multisort($sort_array, SORT_DESC, $result);

            // 10件目以降はその他のグループにまとめ、最大10件まで表示する
            if (count($result) > 10) {
                $tmp_result = [];
                $loop_count = 0;
                foreach ($result as $url => $data) {
                    $loop_count++;
                    if ($loop_count == 10) {
                        $tmp_result['その他'] = $data;
                    } elseif ($loop_count > 10) {
                        foreach ($data as $key => $count) {
                            $tmp_result['その他'][$key] += $count;
                        }
                    } else {
                        $tmp_result[$url] = $data;
                    }
                }
                $result = $tmp_result;
            }

            $graph_data = [];
            foreach ($result as $url => $data) {
                $graph_data['labels'] = array_keys($data);
                break;
            }
            $graph_data['datasets'] = [];
            $chart_index = 0;
            foreach ($result as $url => $data) {
                $graph_data['datasets'][] = [
                    'label' => $url,
                    'backgroundColor' => "rgba({$this->rgb_colors[$chart_index]},0.9)",
                    'borderColor' => "rgba({$this->rgb_colors[$chart_index]},0.8)",
                    'data' => array_values($data),
                ];
                $chart_index++;
            }

            $graph_data = json_encode($graph_data);
            assert($graph_data !== false);
        }

        return $graph_data;
    }
}
