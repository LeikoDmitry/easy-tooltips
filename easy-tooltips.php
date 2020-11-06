<?php
/*
Plugin Name: Easy Tooltips
Description: Easily add tooltips to your wordpress site. You can define tooltip color settings in <strong>Settings => Easy Tooltips</strong>
Version: 1.0.0
Author: Crock Dev
License: MIT
*/

use Easy\Tooltip\Factory\PluginFactory;

define('EASY_TOOLTIPS_VERSION', '1.0.0');
define('EASY_TOOLTIPS_PATH', __FILE__);
define('EASY_TOOLTIPS_DIR', __DIR__);

require_once sprintf('%s/%s', EASY_TOOLTIPS_DIR, 'vendor/autoload.php');

function easyTooltips(): void
{
    $factory = new PluginFactory(EASY_TOOLTIPS_VERSION, EASY_TOOLTIPS_PATH);
    $factory->initPlugin();
}
easyTooltips();