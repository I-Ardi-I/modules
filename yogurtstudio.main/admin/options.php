<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$GLOBALS['APPLICATION']->SetAdditionalCss("/local/modules/yogurtstudio.main/css/style.css");
$GLOBALS['APPLICATION']->AddHeadScript("/local/modules/yogurtstudio.main/js/script.js");
?>

<div class="main_container">
	<header class="header">
		<div class="logo header_logo">
		</div>
		<nav class="header_menu">
			<ul class="menu_list">
				<li class="menu_item">
					<a href="#contacts"
					   class="menu_link"
					>
						<span class="button_text">
							Контакты
						</span>
					</a>
				</li>
				<li class="menu_item">
					<a href="#setting"
					   class="menu_link"
					>
						<span class="button_text">
							Настройки
						</span>
					</a>
				</li>
			</ul>
		</nav>
	</header>
	<form action=""
	      class="main_form"
	      id="formOptions"
	>
		<main class="main_block">
			<section class="contacts block" id="contacts">
				<div class="contacts_title title">
					<h3>
						Контакты
					</h3>
				</div>
				<div class="contacts_phones">
					<div class="phones_title title--sub">
						<h3>Список телефонов</h3>
					</div>
					<div class="phones_list">
						<div class="phones_item">
							<input type="text"
							       class="input input--default phone_input--number"
							       placeholder="+7 (900) 000 00 00"
							       name="phoneValue"
							>
							<input type="text"
							       class="input input--default phone_input--description"
							       placeholder="Описание"
							       name="phoneDescription"
							>
						</div>
						<div class="phones_item">
							<input type="text"
							       class="input input--default phone_input--number"
							       placeholder="+7 (900) 000 00 00"
							       name="phoneValue"
							>
							<input type="text"
							       class="input input--default phone_input--description"
							       placeholder="Описание"
							       name="phoneDescription"
							>
						</div>
					</div>
				</div>
				<div class="contacts_social">
					<div class="social_title title--sub">
						<h3>Соц. сети</h3>
					</div>
					<div class="social_list">
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							       name="nameSocial"
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							       name="linkSocial"
							>
						</div>
					</div>
				</div>
			</section>
			<section class="setting block" id="setting">
				<div class="setting_title title">
					<h3>
						Настройки
					</h3>
				</div>
				<div class="setting_list">
					<div class="setting_item--medium">
						<div class="setting_label">
							<label for="">Адрес</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="г. Калининград"
						       name="address"
						>
					</div>
					<div class="setting_item--medium">
						<div class="setting_label">
							<label for="">Карта</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="https//:maps"
						       name="linkMap"
						>
					</div>
					<div class="setting_item--medium">
						<div class="setting_label">
							<label for="">График</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="Пн-Пт 7:00 - 18:00"
						       name="workTime"
						>
					</div>
					<div class="setting_item--big">
						<div class="setting_label">
							<label for="">Логотип Светлый</label>
						</div>
						<input type="file"
						       class="input setting_input--big"
						       placeholder=""
						>
					</div>
					<div class="setting_item--big">
						<div class="setting_label">
							<label for="">Логотип темный</label>
						</div>
						<input type="file"
						       class="input setting_input--big"
						       placeholder=""
						>
					</div>
				</div>
			</section>
		</main>
	</form>
</div>
