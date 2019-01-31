<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
date_default_timezone_set('UTC');

$homeDir = $_SERVER['HOME'] . '/.magento';
if (!file_exists($homeDir)) {
    mkdir($homeDir);
}

if (!defined('HOME_DIR')) {
    define('HOME_DIR', $homeDir);
}
