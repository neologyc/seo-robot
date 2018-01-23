<?php

ini_set('max_execution_time', 20*60); // 20 minutes max runtime
// load settings
require_once('./settings/settings.php');
require_once('./app/functions.php');
$testid = htmlspecialchars($_GET['id'], ENT_QUOTES);
$log = '';
$hasError = FALSE;

if(!function_exists('curl_version') || !extension_loaded('curl')) {
  die('Rozšíření CURL není nainstalováno.');
}

require_once('./app/app.php');
