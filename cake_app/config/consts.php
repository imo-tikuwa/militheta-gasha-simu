<?php
/** サイト名 */
const SITE_NAME = 'MilliGashaSimu';

/** サイト名(短) */
const SITE_NAME_SHORT = 'Mli';

/** GoogleMapAPIキー */
const GOOGLEMAP_API_KEY = '';

/** スーパーユーザーのアカウントID(権限チェック不要で全ての機能にアクセス可能) */
const SUPER_USER_ID = 1;

/** Read権限 */
const ROLE_READ = 'READ';

/** Write権限 */
const ROLE_WRITE = 'WRITE';

/** Delete権限 */
const ROLE_DELETE = 'DELETE';

/** CsvExport権限 */
const ROLE_CSV_EXPORT = 'CSV_EXPORT';

/** CsvImport権限 */
const ROLE_CSV_IMPORT = 'CSV_IMPORT';

/** indexアクション */
const ACTION_INDEX = 'index';

/** viewアクション */
const ACTION_VIEW = 'view';

/** addアクション */
const ACTION_ADD = 'add';

/** editアクション */
const ACTION_EDIT = 'edit';

/** deleteアクション */
const ACTION_DELETE = 'delete';

/** csvExportアクション */
const ACTION_CSV_EXPORT = 'csvExport';

/** csvImportアクション */
const ACTION_CSV_IMPORT = 'csvImport';

/** 権限エラーメッセージ */
const MESSAGE_AUTH_ERROR = '権限エラーが発生しました';

/** SSRピックアップレートの初期値 */
const BASE_SSR_PICKUP_RATE = 0.99;

/** SRピックアップレートの初期値 */
const BASE_SR_PICKUP_RATE = 2.4;

/** Rピックアップレートの初期値 */
const BASE_R_PICKUP_RATE = 8.5;

/** ピック指定機能のピック上限 */
const TARGET_PICK_ALLOW_MAX_NUM = 10000;