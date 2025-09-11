<?php
use Aspro\Functions\CAsproMax as SolutionFunctions;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use CMax as Solution;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

global $APPLICATION;
IncludeModuleLangFile(__FILE__);