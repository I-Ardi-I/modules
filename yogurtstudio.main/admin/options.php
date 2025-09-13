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
							>
							<input type="text"
							       class="input input--default phone_input--link"
							       placeholder="tel: +79000000000"
							>
							<input type="file"
							       class="input input--default phone_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default phone_input--description"
							       placeholder="Описание"
							>
						</div>
						<div class="phones_item">
							<input type="text"
							       class="input input--default phone_input--number"
							       placeholder="+7 (900) 000 00 00"
							>
							<input type="text"
							       class="input input--default phone_input--link"
							       placeholder="tel: +79000000000"
							>
							<input type="file"
							       class="input input--default phone_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default phone_input--description"
							       placeholder="Описание"
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
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
							>
						</div>
						<div class="social_item">
							<input type="text"
							       class="input input--default social_input--name"
							       placeholder="ВКонтаке"
							>
							<input type="file"
							       class="input social_input--icon input--icon"
							       placeholder=""
							>
							<input type="text"
							       class="input input--default social_input--link"
							       placeholder="https//:vk.ru"
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
							<label for="">Название</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="YogurtStudio"
						>
					</div>
					<div class="setting_item--medium">
						<div class="setting_label">
							<label for="">Адрес</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="г. Калининград"
						>
					</div>
					<div class="setting_item--medium">
						<div class="setting_label">
							<label for="">Карта</label>
						</div>
						<input type="text"
						       class="input input--default setting_input--medium"
						       placeholder="https//:maps"
						>
					</div>
					<div class="setting_item--big">
						<div class="setting_label">
							<label for="">Логотип</label>
						</div>
						<input type="file"
						       class="input setting_input--big"
						       placeholder=""
						>
					</div>
					<div class="setting_item--big">
						<div class="setting_label">
							<label for="">Favicon</label>
						</div>
						<input type="file"
						       class="input setting_input--big"
						       placeholder=""
						>
					</div>
					<div class="list_item--block">
						<div class="setting_item--long">
							<div class="setting_label">
								<label for="">График</label>
							</div>
							<input type="text"
							       class="input input--default setting_input--long"
							       placeholder="Пн-Пт 7:00 - 18:00"
							>
						</div>
						<div class="setting_item--long">
							<div class="setting_label">
								<label for="">Политика конфидициальности</label>
							</div>
							<input type="text"
							       class="input input--default setting_input--long"
							       placeholder="https//:policy/"
							>
						</div>
					</div>
				</div>
			</section>
		</main>
		<footer class="footer">
			<button class="footer_button--red">
					<span class="button_text">
						Применить
					</span>
			</button>
		</footer>
	</form>
</div>
