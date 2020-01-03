<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('APP_DIR', 'src');

/**
 * Path to the application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/**
 * Bake detail config directory.
 */
define('BAKE_DETAIL_CONFIG', CONFIG . 'bake_config' . DS);

/**
 * Baked config file.
 */
define('BAKED_CONFIG_FILE', CONFIG . 'bake_codes.php');

/**
 * Baked left_side_menu file.
 */
define('BAKED_LEFT_SIDE_MENU_FILE', CONFIG . 'left_side_menu.php');

/**
 * Baked admin_config file.
 */
define('BAKED_ADMIN_CONFIG_FILE', CONFIG . 'admin_config.php');

/**
 * Baked env dir.
 */
define('BAKED_ENV_DIR', dirname(ROOT) . DS . 'env' . DS);

/**
 * Baked create table sql dir.
 */
define('BAKED_CREATE_TABLE_SQL_DIR', dirname(ROOT) . DS . 'env' . DS . 'sql' . DS);

/**
 * admins.sql file.
 */
define('CREATE_ADMINS_TABLE_SQL_FILE', dirname(ROOT) . DS . 'env' . DS . 'admins.sql');

/**
 * Baked app.php file.
 */
define('BAKED_APP_CONFIG_FILE', CONFIG . 'app.php');

/**
 * app.php.org file.
 */
define('BAKED_APP_CONFIG_FILE_ORG', CONFIG . 'app.php.org');

/**
 * File path to the webroot directory.
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/**
 * Upload Files Base directory name.
 */
define('UPLOAD_FILE_BASE_DIR_NAME', 'upload_files');

/**
 * Upload Files Base directory.
 */
define('UPLOAD_FILE_BASE_DIR', WWW_ROOT . UPLOAD_FILE_BASE_DIR_NAME);

/**
 * Path to the tests directory.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/**
 * Path to the temporary files directory.
 */
define('TMP', ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
define('CACHE', TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
