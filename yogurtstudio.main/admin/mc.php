<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

global $APPLICATION;
IncludeModuleLangFile(__FILE__);


echo 'hello world';



$json = [
	'settings' => [
		'phones' => [
			[
				'value' => '',
				'description' => ''
			],
			[
				'value' => '',
				'description' => ''
			],
			[
				'value' => '',
				'description' => ''
			]
		],
		'socials' => [
			[
				'name' => '',
				'value' => ''
			]
		],
		'settings' => [
			'address' =>'',
			'mapLink'=>'',
			'workTime' => '',
			'logoLight' => '',
			'logoDark' => '',
			'favicon' => [
				'16x16' => '',
				'32x32' => '',
				'64x64' => '',
				'apple' => '',
			],
		],

	]
];