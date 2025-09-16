<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Routing\RoutingConfigurator;
use Bitrix\Main\HttpRequest;
use YogurtStudio\Main\Http\FormController;
use YogurtStudio\Main\Http\BasketController;
use YogurtStudio\Main\Http\UserController;
use YogurtStudio\Main\Http\FilterController;
use YogurtStudio\Main\General\FormOptions;

return static function (RoutingConfigurator $routes) {
	$checkModule = static function () {
		if (!\Bitrix\Main\Loader::includeModule('yogurtstudio.main')) {
			return json_encode(["error" => "Модуль не подключен"], JSON_UNESCAPED_UNICODE);
		}

		return null;
	};

	$formSave = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$arFields = [];
		foreach ($requestArr as $key => $arField) {
			if ($key !== 'formId') {
				if (str_contains($key, 'form_file')) {
					$arFields[$key] = $request->getFile($arField);
				} else {
					$arFields[$key] = $arField;
				}
			}
		}

		$formHelper = new FormController();
		$result     = $formHelper->sendForm($requestArr["formId"], $arFields);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$formSaveOptions = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$arFields = [];
		foreach ($requestArr as $key => $arField) {
			if ($key !== 'formId') {
				if (str_contains($key, 'form_file')) {
					$arFields[$key] = $request->getFile($arField);
				} else {
					$arFields[$key] = $arField;
				}
			}
		}

		$formHelper = new FormOptions();
		$result     = $formHelper->saveAction($arFields);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$productQuantity = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray();

		$countHelper = new FilterController();
		$result      = $countHelper->updateQuantityFilter($requestArr["data"], $requestArr["priceMin"], $requestArr["priceMax"], $requestArr["sectionId"], $requestArr["filter"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$basketAdd = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray()["data"];

		$result = BasketController::addProductBasket((int)$requestArr["productId"], (int)$requestArr["productQuantity"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$basketCheck = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray()["data"];

		$result = BasketController::checkProductBasket((int)$requestArr["productId"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$basketDelete = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray()["data"];

		$result = BasketController::removeProductFromBasket($requestArr["productIds"], $requestArr["promocode"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$basketQuantity = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray()["data"];

		$result = BasketController::updateProductQuantity((int)$requestArr["productId"], $requestArr["productQuantity"], $requestArr["promocode"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$orderSave = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray();

		$arUserFields     = [
			'name'    => $requestArr['user']["name"],
			'surname' => $requestArr['user']["surname"],
			'phone'   => $requestArr['user']["phone"],
			'email'   => $requestArr['user']["email"],
			'company' => $requestArr['user']["company"],
			'inn'     => $requestArr['user']["inn"],
			'comment' => $requestArr['user']["comment"]
		];
		$arDeliveryFields = [
			'deliveryId'     => $requestArr['delivery']["method"],
			'deliveryFields' => $requestArr['delivery']["details"]
		];
		$arPaymentFields  = [
			'paymentId' => $requestArr["payment"]
		];

		$result = BasketController::createOrderFromBasket($arUserFields, $arDeliveryFields, $arPaymentFields);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$orderPromo = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getJsonList()->toArray();

		$result = BasketController::applypromocodeToBasket($requestArr["promocode"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	};

	$register = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->registerAction(
			$requestArr['email'],
			$requestArr['password'],
			$requestArr['confirmPassword'],
			$requestArr['name'],
			$requestArr['secondName'] ?? '',
			$requestArr['phone'] ?? '',
			$requestArr['company'] ?? ''
		);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$resetPassword = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->restorePasswordAction($requestArr['email']);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$login = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->loginAction(
			$requestArr['email'],
			$requestArr['password'],
		);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$userInfo = function () use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$result = (new UserController())->getCurrentUserAction();

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$userAddress = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->updateUserDelivery($requestArr["name"], $requestArr["secondName"], $requestArr["company"], $requestArr["address"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$userChangeInfo = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->updateUserInfo($requestArr["name"], $requestArr["lastName"], $requestArr["secondName"], $requestArr["phone"], $requestArr["email"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	$userChangePassword = function (HttpRequest $request) use ($checkModule) {
		if ($error = $checkModule()) {
			return $error;
		}

		$requestArr = $request->getPostList()->toArray();

		$result = (new UserController())->updateUserPassword($requestArr["newPassword"], $requestArr["newPasswordConfirm"], $requestArr["oldPassword"]);

		header('Content-Type: application/json; charset=utf-8');

		return json_encode($result);
	};

	// Авторизация/Регистрация/Восстановление пароля
	$routes->post('/api/auth/register/', $register);
	$routes->post('/api/auth/login/', $login);
	$routes->post('/api/auth/change-password/', $resetPassword);
	// ЛК
	$routes->get('/api/user/', $userInfo);
	$routes->post('/api/user/address/', $userAddress);
	$routes->post('/api/user/change-password/', $userChangePassword);
	$routes->post('/api/user/change-info/', $userChangeInfo);
	// Формы
	$routes->post('/api/form/save/', $formSave);
	$routes->post('/api/formOption/save/', $formSaveOptions);
	// Число товаров
	$routes->post('/api/filter/quantity/', $productQuantity);
	// Корзина
	$routes->post('/api/basket/add/', $basketAdd);
	$routes->post('/api/basket/check/', $basketCheck);
	$routes->post('/api/basket/delete/', $basketDelete);
	$routes->post('/api/basket/quantity/', $basketQuantity);
	// Оформление заказа
	$routes->post('/api/order/save/', $orderSave);
	$routes->post('/api/order/promo/', $orderPromo);
};