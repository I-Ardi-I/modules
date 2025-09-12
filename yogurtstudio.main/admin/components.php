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

<div class="block_general_setting">
	<h2 class="title">Основные настройки</h2>
	<div class="block_contacts container">
		<h2>Контактные данные
			<h2>
				<div class="block_phone block_item">
					<p>Список телефонов</p>
					<div class="block_input">
						<div class="input_item">
							<div class="label">
								<p>Первый номер</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
							<input type="text"
							       placeholder="Описание"
							>
							<div class="phone_description">
								<input type="text"
								       placeholder="Описание"
								>
							</div>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Второй номер</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
							<input type="text"
							       placeholder="Описание"
							>
							<div class="phone_description">
								<input type="text"
								       placeholder="Описание"
								>
							</div>
						</div>
					</div>
				</div>
				<div class="block_phone block_item">
					<p>Соц. сети</p>
					<div class="block_input">
						<div class="input_item">
							<div class="label">
								<p>ВКонтакте</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
						<div class="input_item">
							<div class="label">
								<p>Телеграмм</p>
							</div>
							<input type="text"
							       placeholder="+7 000 000 00 00"
							>
						</div>
					</div>
				</div>
	</div>
	<div class="block_personalization container">
		<h2>Настройки сайта
			<h2>
				<div class="block_phone block_item">
					<p>Логотип</p>
					<div class="block_input">
						<div class="input_item">
							<div class="label">
								<p>Первый Логотип</p>
							</div>
							<input type="file">
						</div>
						<div class="input_item">
							<div class="label">
								<p>Второй Логотип</p>
							</div>
							<input type="file">
						</div>
						<div class="input_item">
							<div class="label">
								<p>Фавикон</p>
							</div>
							<input type="file">
						</div>
					</div>
				</div>
				<div class="block_phone block_item">
					<p>Данные</p>
					<div class="block_input">
						<div class="input_item">
							<div class="label">
								<p>Адрес</p>
							</div>
							<input type="text">
							<div class="label">
								<p>Карта</p>
							</div>
							<input type="text">
						</div>
						<div class="input_item">
							<div class="label">
								<p>Юридический адрес</p>
							</div>
							<input type="text">
							<div class="label">
								<p>Политика конфиденциальности</p>
							</div>
							<input type="file">
						</div>
						<div class="input_item">
							<div class="label">
								<p>Описание</p>
							</div>
							<textarea name=""
							          id=""
							          cols="30"
							          rows="10"
							></textarea>
						</div>
					</div>
				</div>
	</div>
</div>
