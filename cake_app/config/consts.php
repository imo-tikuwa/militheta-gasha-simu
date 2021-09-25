<?php
/** サイト名 */
const SITE_NAME = 'MilliGashaSimu';

/** サイト名(短) */
const SITE_NAME_SHORT = 'Mli';

/** 管理者のアカウントID(権限チェック不要で全ての機能にアクセス可能) */
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

/** ExcelExport権限 */
const ROLE_EXCEL_EXPORT = 'EXCEL_EXPORT';

/** ExcelImport権限 */
const ROLE_EXCEL_IMPORT = 'EXCEL_IMPORT';

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

/** fileUploadアクション */
const ACTION_FILE_UPLOAD = 'fileUpload';

/** fileDeleteアクション */
const ACTION_FILE_DELETE = 'fileDelete';

/** csvExportアクション */
const ACTION_CSV_EXPORT = 'csvExport';

/** csvImportアクション */
const ACTION_CSV_IMPORT = 'csvImport';

/** excelExportアクション */
const ACTION_EXCEL_EXPORT = 'excelExport';

/** excelImportアクション */
const ACTION_EXCEL_IMPORT = 'excelImport';

/** 権限エラーメッセージ */
const MESSAGE_AUTH_ERROR = '権限エラーが発生しました';

/** エクセルファイル(.xlsx)のContent-Type */
const EXCEL_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

/** Google Authenticatorのシークレットキーの長さ */
const GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN = 32;

/** 認証コードのinput名 */
const GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME = 'secret';

/** SSRピックアップレートの初期値 */
const BASE_SSR_PICKUP_RATE = 0.99;

/** SHS限定の1枚当たりのSSRピックアップレート(2021-09-17現在) */
const SHS_LIMITED_SSR_PER_PICKUP_RATE = 0.899;

/** SRピックアップレートの初期値 */
const BASE_SR_PICKUP_RATE = 2.4;

/** Rピックアップレートの初期値 */
const BASE_R_PICKUP_RATE = 8.5;

/** ピック指定機能のピック上限 */
const TARGET_PICK_ALLOW_MAX_NUM = 10000;