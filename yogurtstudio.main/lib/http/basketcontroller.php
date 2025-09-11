<?php

namespace YogurtStudio\Main\Http;

use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Internals\DiscountCouponTable;
use Bitrix\Sale\Order;
use Bitrix\Catalog\Product\Basket as CatalogBasket;

class BasketController
{
	/**
	 * Проверяет подключение необходимых модулей
	 *
	 * @return bool
	 */
	protected static function checkModules()
	: bool
	{
		return Loader::includeModule("sale") && Loader::includeModule("catalog");
	}

	/**
	 * Рассчитывает общее количество товаров в корзине
	 *
	 * @param \Bitrix\Sale\Basket $basket
	 *
	 * @return float
	 */
	public static function calculateTotalQuantity(Basket $basket)
	: float {
		$quantity = 0;
		foreach ($basket as $ignored) {
			$quantity++;
		}

		return $quantity;
	}

	/**
	 * Формирует базовый ответ с информацией о корзине
	 *
	 * @param array $couponInfo
	 *
	 * @return array
	 */
	protected static function prepareBasketResponse(array $couponInfo)
	: array {
		$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());

		if ($basket->isEmpty()) {
			return [
				'status'        => 200,
				'totalQuantity' => 0,
				'totalPrice'    => 0,
				'totalRawPrice' => 0,
				'totalDiscount' => 0,
				'couponInfo'    => $couponInfo,
			];
		}

		$basketItems = $basket->getBasketItems();
		$arProducts  = [];

		foreach ($basketItems as $item) {
			$arProducts[$item->getId()] = $item->getQuantity();
		}

		$basePrice = 0;
		$price     = 0;

		try {
			$discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser(Fuser::getId()));
			if ($discounts) {
				$discounts->calculate();
				$arPrices = $discounts->getShowPrices();

				foreach ($arPrices["BASKET"] as $key => $item) {
					$basePrice += $item["SHOW_BASE_PRICE"] * $arProducts[$key];
					$price     += $item["REAL_PRICE"] * $arProducts[$key];
				}
			} else {
				foreach ($basketItems as $item) {
					$basePrice += $item->getBasePrice() * $item->getQuantity();
					$price     += $item->getPrice() * $item->getQuantity();
				}
			}
		} catch (\Exception $e) {
			foreach ($basketItems as $item) {
				$basePrice += $item->getBasePrice() * $item->getQuantity();
				$price     += $item->getPrice() * $item->getQuantity();
			}
		}

		return [
			'status'        => 200,
			'totalQuantity' => self::calculateTotalQuantity($basket),
			'totalPrice'    => $price,
			'totalRawPrice' => $basePrice,
			'totalDiscount' => $basePrice - $price,
			'couponInfo'    => $couponInfo,
		];
	}

	/**
	 * Удаляет товары из корзины по их ID
	 *
	 * @param array|null  $productIds Массив ID товаров для удаления
	 * @param string|null $promoCode  Промокод (если есть)
	 *
	 * @return array
	 */
	public static function removeProductFromBasket(?array $productIds, ?string $promoCode = null)
	: array {
		if (!self::checkModules()) {
			return [
				'status'  => 500,
				'message' => 'Необходимые модули не подключены'
			];
		}

		if (empty($productIds)) {
			return [
				'status'  => 400,
				'message' => 'Не переданы ID товаров для удаления'
			];
		}

		$deletedIds = [];

		$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		$order  = Order::create(Context::getCurrent()->getSite(), Fuser::getId());
		$order->setBasket($basket);

		foreach ($productIds as $productId) {
			if ($productId <= 0) {
				return [
					'status'  => 400,
					'message' => 'ID товаров некорректный',
				];
			}

			foreach ($basket as $item) {
				if ((int)$item->getProductId() === (int)$productId) {
					$item->delete();
					$deletedIds[] = $productId;

					break;
				}
			}
		}

		if (empty($deletedIds)) {
			return [
				'status'  => 400,
				'message' => 'Ни один из товаров не найден в корзине',
			];
		}

		$coupon = self::applyPromoCodeToBasket($promoCode);
		$basket->save();
		unset($order);

		return self::prepareBasketResponse($coupon);
	}

	/**
	 * Изменяет количество товара в корзине
	 *
	 * @param int|null    $productId
	 * @param int|null    $productQuantity
	 * @param string|null $promoCode
	 *
	 * @return array
	 */
	public static function updateProductQuantity(?int $productId, ?int $productQuantity, ?string $promoCode = null)
	: array {
		if (!self::checkModules()) {
			return [
				'status'  => 500,
				'message' => 'Необходимые модули не подключены'
			];
		}

		if ($productId <= 0 || $productQuantity <= 0) {
			return [
				'status'  => 400,
				'message' => 'Некорректные данные'
			];
		}

		$maxQuantity = 0;
		if (Loader::includeModule('catalog')) {
			$prodData = \CCatalogProduct::GetByID($productId);
			if ($prodData && isset($prodData['QUANTITY'])) {
				$maxQuantity = (float)$prodData['QUANTITY'];
			}
		}

		$basket  = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		$updated = false;

		foreach ($basket as $item) {
			if ((int)$item->getProductId() === $productId) {
				$newQuantity = $productQuantity;

				if ($maxQuantity > 0 && $productQuantity > $maxQuantity) {
					$newQuantity = $maxQuantity;
				}

				$item->setField("QUANTITY", $newQuantity);
				$updated = true;

				break;
			}
		}

		if (!$updated) {
			return [
				'status'  => 400,
				'message' => 'Товар не найден в корзине'
			];
		}

		$coupon = self::applyPromoCodeToBasket($promoCode);
		$basket->save();

		return self::prepareBasketResponse($coupon);
	}

	/**
	 * Проверяет наличие товара в корзине по его ID
	 *
	 * @param int $productId
	 *
	 * @return array
	 */
	public static function checkProductBasket(int $productId)
	: array {
		if (!self::checkModules()) {
			return [
				'status'  => 500,
				'message' => 'Ошибка модуля!'
			];
		}

		if ($productId <= 0) {
			return [
				'status'  => 400,
				'message' => 'Некорректный ID товара!'
			];
		}

		$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		foreach ($basket as $item) {
			if ((int)$item->getProductId() === $productId) {
				return [
					'status'   => 200,
					'data'     => $productId,
					'price'    => $item->getPrice(),
					'quantity' => $item->getQuantity()
				];
			}
		}

		return [
			'status'  => 400,
			'message' => 'Товар не найден в корзине',
			'data'    => $productId
		];
	}

	/**
	 * Добавляет товар в корзину
	 *
	 * @param int|null    $productId
	 * @param int|null    $productQuantity
	 * @param string|null $promoCode
	 *
	 * @return array
	 */
	public static function addProductBasket(?int $productId, ?int $productQuantity, ?string $promoCode = null)
	: array {
		if (!self::checkModules()) {
			return [
				'status'  => 500,
				'message' => 'Ошибка загрузки модулей!'
			];
		}

		if ($productId <= 0 || $productQuantity <= 0) {
			return [
				'status'  => 400,
				'message' => 'Неверные параметры товара!'
			];
		}

		$maxQuantity = 0;
		if (Loader::includeModule('catalog')) {
			$prodData = \CCatalogProduct::GetByID($productId);
			if ($prodData && isset($prodData['QUANTITY'])) {
				$maxQuantity = (float)$prodData['QUANTITY'];
			}
		}

		$quantityToAdd = $productQuantity;
		if ($maxQuantity > 0 && $productQuantity > $maxQuantity) {
			$quantityToAdd = $maxQuantity;
		}

		$basket    = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		$existItem = null;

		foreach ($basket as $basketItem) {
			if ((int)$basketItem->getField("PRODUCT_ID") === $productId) {
				$existItem = $basketItem;

				break;
			}
		}

		$fields = [
			'PRODUCT_ID' => $productId,
			'QUANTITY'   => $quantityToAdd,
		];

		$options = $existItem ? ["QUANTITY" => $quantityToAdd] : [];

		$basketAddResult = CatalogBasket::addProduct($fields, $options, [
			'USE_MERGE'               => 'Y',
			'FILL_PRODUCT_PROPERTIES' => 'Y',
		]);

		if (!$basketAddResult->isSuccess()) {
			return [
				'status'  => 400,
				'message' => $basketAddResult->getErrorMessages()
			];
		}

		$coupon = self::applyPromoCodeToBasket($promoCode);

		return self::prepareBasketResponse($coupon);
	}

	/**
	 * Создаёт заказ на основе текущей корзины
	 *
	 * @param array|null $arUserFields
	 * @param array|null $arDeliveryFields
	 * @param array|null $arPaymentFields
	 *
	 * @return array
	 */
	public static function createOrderFromBasket(?array $arUserFields, ?array $arDeliveryFields, ?array $arPaymentFields)
	: array {
		if (!self::checkModules()) {
			return [
				'status'  => 500,
				'message' => 'Не удалось подключить модули sale или catalog'
			];
		}

		$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		if ($basket->isEmpty()) {
			return [
				'status'  => 400,
				'message' => 'Корзина пуста'
			];
		}

		global $USER;

		$requiredFields = [
			'name',
			'phone',
			'email'
		];
		foreach ($requiredFields as $field) {
			if (empty($arUserFields[$field])) {
				return [
					'status'  => 400,
					'message' => "Не заполнено обязательное поле: " . $field
				];
			}
		}

		if (empty($arDeliveryFields['deliveryId'])) {
			return [
				'status'  => 400,
				'message' => 'Не указан способ доставки'
			];
		}

		if (empty($arPaymentFields['paymentId'])) {
			return [
				'status'  => 400,
				'message' => 'Не указан способ оплаты'
			];
		}

		try {
			$order = Order::create(Context::getCurrent()->getSite(), $USER->GetID());
			$order->setPersonTypeId(1);
			$order->setBasket($basket);

			$propertyCollection = $order->getPropertyCollection();

			$propertyCollection->getPayerName()->setValue($arUserFields['name']);

			$propertyMap = [
				'FIO'           => $arUserFields['name'] . ' ' . $arUserFields['surname'] ?? '',
				'PHONE'         => $arUserFields['phone'],
				'EMAIL'         => $arUserFields['email'],
				'COMPANY'       => $arUserFields['company'] ?? '',
				'INN'           => $arUserFields['inn'] ?? '',
				'ADDRESS'       => $arDeliveryFields['deliveryFields']['order-address'] ?? '',
				'DELIVERY_TYPE' => $arDeliveryFields['deliveryFields']['order-delivery-type'] ?? '',
			];

			foreach ($propertyMap as $code => $value) {
				if ($property = $propertyCollection->getItemByOrderPropertyCode($code)) {
					$property->setValue($value);
				}
			}

			$order->setField('USER_DESCRIPTION', $arUserFields['comment']);

			$shipmentCollection = $order->getShipmentCollection();
			$shipment           = $shipmentCollection->createItem();
			$shipment->setFields([
				'DELIVERY_ID'   => $arDeliveryFields['deliveryId'],
				'DELIVERY_NAME' => \Bitrix\Sale\Delivery\Services\Manager::getById($arDeliveryFields['deliveryId'])['NAME'] ?? 'Доставка'
			]);

			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			foreach ($basket as $basketItem) {
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}

			$paymentCollection = $order->getPaymentCollection();
			$payment           = $paymentCollection->createItem();
			$paySystemService  = \Bitrix\Sale\PaySystem\Manager::getObjectById($arPaymentFields["paymentId"]);
			$payment->setFields([
				'PAY_SYSTEM_ID'   => $paySystemService->getField("PAY_SYSTEM_ID"),
				'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
			]);

			$result = $order->save();
			if (!$result->isSuccess()) {
				return [
					'status'  => 400,
					'message' => 'Ошибка сохранения заказа: ' . implode(', ', $result->getErrorMessages())
				];
			}

			return [
				'status'        => 200,
				'action'        => 'redirect',
				'actionPayload' => '/personal/complete/?order=' . $order->getId(),
				'data'          => [
					'userId'  => Fuser::getId(),
					'orderId' => $order->getId()
				]
			];
		} catch (\Exception $e) {
			return [
				'status'  => 500,
				'message' => 'Ошибка при создании заказа: ' . $e->getMessage()
			];
		}
	}

	/**
	 * Применяет промокод к корзине, если он существует и валиден
	 *
	 * @param string|null $promoCode
	 *
	 * @return array
	 */
	public static function applyPromoCodeToBasket(?string $promoCode)
	: array {
		if (!Loader::includeModule('sale')) {
			return [
				'status'  => 500,
				'message' => 'Не удалось подключить модуль sale'
			];
		}

		if (empty($promoCode)) {
			$coupons = DiscountCouponsManager::get();
			foreach ($coupons as $coupon) {
				if (!empty($coupon['COUPON'])) {
					$promoCode = trim($coupon['COUPON']);

					break;
				}
			}

			if (empty($promoCode)) {
				return [
					'status'  => 400,
					'message' => 'Промокод не указан и нет активных промокодов'
				];
			}
		}

		$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
		if ($basket->isEmpty()) {
			return [
				'status'  => 400,
				'message' => 'Корзина пуста'
			];
		}

		$discount = DiscountCouponTable::getList([
			'filter' => [
				'COUPON' => $promoCode,
				'ACTIVE' => 'Y'
			],
			'select' => [
				'ID',
				'COUPON',
				'DISCOUNT_ID'
			]
		])->fetch();

		if (!$discount) {
			return [
				'status'  => 400,
				'message' => 'Промокод не существует или не активен'
			];
		}

		$order = Order::create(Context::getCurrent()->getSite(), Fuser::getId());
		$order->setPersonTypeId(1);
		$order->setBasket($basket);

		DiscountCouponsManager::clear();
		DiscountCouponsManager::add($promoCode);

		$order->doFinalAction(true);
		$order->getDiscount()->calculate();

		$basket->refresh();
		$basket->save();

		$basketsItem = [];

		$getBasketItems = $basket->getBasketItems();
		foreach ($getBasketItems as $basketItem) {
			$basePrice          = $basketItem->getBasePrice();
			$price              = $basketItem->getPrice();
			$discountPercentage = 0;

			if ($basePrice > 0 && $basePrice > $price) {
				$discountPercentage = round((($basePrice - $price) * 100) / $basePrice);
			}

			$basketsItem[] = [
				'id'                 => $basketItem->getId(),
				'totalPrice'         => $price,
				'basePrice'          => $basePrice,
				'discountPercentage' => $discountPercentage,
			];
		}

		return [
			'status'             => 200,
			'coupon'             => $promoCode,
			'totalPrice'         => $basket->getPrice(),
			'discount'           => $basket->getBasePrice() - $basket->getPrice(),
			'discountPercentage' => round((($basket->getBasePrice() - $basket->getPrice()) * 100) / $basket->getBasePrice()),
			'promoCode'          => $discount,
			'products'           => $basketsItem
		];
	}
}