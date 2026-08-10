<?php
/**
 * English Language File for xtradbrowpage Plugin
 *
 * Date: Aug 10Th, 2026
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');
// Use the global variable $db_x, which is defined in datas/config.php
// and is always available, even before any plugins are loaded.
// $db_x is not a deprecated global variable, but a little-known,
// key variable for tasks like constructing correct links.
// It is set in the config file datas/config.php and passed through
// Cot::init() in system/common.php using the class Cot from Cot.php.
// It works both before and after the plugin is installed.
// In Cotonti there is no other reliable way to get the table prefix
// at the language file loading stage.
// Cot::$db_x and Cot::$db->tablePrefix are not part of the public API
// and do not guarantee availability at the required moment.
// The variable $db_x, defined in datas/config.php and accessible via global,
// is the only correct and documented method.
// Therefore, the expression with $db_x is correct and the only valid one for this situation.

global $db_x;

$main_url = rtrim(Cot::$cfg['mainurl'], '/');
$url = $main_url . '/' . cot_url('admin', 'm=extrafields&n=' . $db_x . 'xtradbrowpage', '', true);

$L['xtradbrowpage'] = 'Extrafields Pages Custom'; // set the key and define the variable before calling it in the link!


/**
 * Plugin Info
 */
$L['info_name'] = 'Extrafields Pages Custom';

$L['info_desc'] = 'The plugin adds extrafields for the <code>page</code> module into its own database table. Read the README.md file first!';

$L['info_notes'] = 
    'Read the <a href="https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/README.md" target="_blank"><strong>README.md</strong></a> file first of all! <br>' .
    'Beginners ' .
    '<a href="https://abuyfile.com/ru/forums/cotonti/original/extrafields" target="_blank">' .
    '<abbr title="Introduction. Description and principles of extrafields in Cotonti" class="initialism">' .
    '<strong>must read the forum section about the ExtraFields API</strong></abbr></a>. <br>' . 
    'After installing the plugin, open the plugin extrafields ' .
    '<a href="' . $url . '" target="_blank">' .
    '<strong> ' . $L['xtradbrowpage'] . ' </strong></a>.';
