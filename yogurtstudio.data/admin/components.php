<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$GLOBALS['APPLICATION']->SetAdditionalCss("/local/modules/yogurtstudio.main/css/style.css");
?>


