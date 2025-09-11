<?php

namespace YogurtStudio\Main\Http;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

class UserController extends
	Controller
{
	public function updateUserDelivery(?string $name, ?string $secondName, ?string $company, ?string $address)
	: array {
		try {
			$user = new \CUser();

			if (!empty($name)) {
				$fields["UF_NAME_DELIVERY"] = $name;
			}

			if (!empty($secondName)) {
				$fields["UF_LAST_NAME"] = $secondName;
			}

			if (!empty($company)) {
				$fields["UF_COMPANY_DELIVERY"] = $company;
			}

			if (!empty($address)) {
				$fields["UF_ADDRESS"] = $address;
			}

			if (!empty($fields)) {
				$result = $user->Update($user->GetID(), $fields);
				if ($result === 0) {
					return [
						'status'  => 400,
						'message' => 'Ошибка при сохранении данных!'
					];
				}

				return [
					'status' => 200,
					'data'   => [
						"result" => $result
					],
					'action' => "reload"
				];
			} else {
				return [
					'status'  => 400,
					'message' => 'Не заполнены поля'
				];
			}
		} catch (Exception $e) {
			return [
				'status'  => 400,
				'message' => 'Произошла непредвиденная ошибка' . $e->getMessage()
			];
		}
	}

	public function updateUserInfo(?string $name, ?string $lastName, ?string $secondName, ?string $phone, ?string $email)
	: array {
		try {
			$user = new \CUser();

			if (!empty($name)) {
				$fields["NAME"] = $name;
			}

			if (!empty($secondName)) {
				$fields["SECOND_NAME"] = $secondName;
			}

			if (!empty($lastName)) {
				$fields["LAST_NAME"] = $lastName;
			}

			if (!empty($phone)) {
				$fields["PERSONAL_PHONE"] = $phone;
			}

			if (!empty($email)) {
				$fields["EMAIL"] = $email;
			}

			if (!empty($fields)) {
				$result = $user->Update($user->GetID(), $fields);
				if ($result === 0) {
					return [
						'status'  => 400,
						'message' => 'Ошибка при сохранении данных!'
					];
				}

				return [
					'status' => 200,
					'data'   => [
						"result" => $result
					],
					'action' => 'reload'
				];
			} else {
				return [
					'status'  => 400,
					'message' => 'Не заполнены поля'
				];
			}
		} catch (Exception $e) {
			return [
				'status'  => 400,
				'message' => 'Произошла непредвиденная ошибка' . $e->getMessage()
			];
		}
	}

	public function updateUserPassword(?string $newPassword, ?string $newPasswordConfirm, ?string $oldPassword)
	: array {
		try {
			global $USER;

			$user = new \CUser();

			$userID = $user->GetID();
			$arUser = $user::GetByID($GLOBALS["USER"]->GetId())->GetNext();

			if (empty($newPassword) || empty($newPasswordConfirm) || empty($oldPassword)) {
				return [
					'status'  => 400,
					'message' => 'Не заполнены обязательные поля'
				];
			}

			if (!empty($USER->Login($arUser["LOGIN"], $oldPassword, "N")["MESSAGE"])) {
				return [
					'status'  => 400,
					'message' => 'Старый пароль введен не верно!',
				];
			}

			if ($newPassword !== $newPasswordConfirm) {
				return [
					'status'  => 400,
					'message' => 'Пароль и подтверждение пароля не совпадают'
				];
			}

			$fields["PASSWORD"] = $newPassword;

			if (!empty($fields)) {
				$result = $user->Update($userID, $fields);
				if ($result === 0) {
					return [
						'status'  => 400,
						'message' => 'Ошибка при сохранении данных!'
					];
				}

				return [
					'status' => 200,
					'data'   => [
						"result" => $result
					],
				];
			} else {
				return [
					'status'  => 400,
					'message' => 'Не заполнены поля'
				];
			}
		} catch (Exception $e) {
			return [
				'status'  => 400,
				'message' => 'Произошла непредвиденная ошибка' . $e->getMessage()
			];
		}
	}

	/**
	 * Регистрация нового пользователя
	 *
	 * @param string|null $email           Email пользователя (обязательное поле)
	 * @param string|null $password        Пароль (обязательное поле)
	 * @param string|null $confirmPassword Подтверждение пароля (обязательное поле)
	 * @param string|null $name            Имя пользователя (обязательное поле)
	 * @param string|null $secondName      Фамилия пользователя
	 * @param string|null $phone           Телефон пользователя
	 * @param string|null $company         Компания пользователя
	 *
	 * @return array Всегда возвращает массив с ключами:
	 *               - status: 200 | 400
	 *               - message: Сообщение об ошибке
	 *               - data Дополнительные данные (при успехе)
	 *
	 * Возможные ошибки:
	 * - Не заполнены обязательные поля
	 * - Пароль и подтверждение пароля не совпадают
	 * - Пользователь с таким email уже существует
	 * - Ошибка при создании пользователя в БД
	 */
	public function registerAction(?string $email, ?string $password, ?string $confirmPassword, ?string $name, ?string $secondName, ?string $phone, ?string $company)
	: array {
		try {
			global $USER;

			if (empty($email) || empty($password) || empty($confirmPassword) || empty($name)) {
				return [
					'status'  => 400,
					'message' => 'Не заполнены обязательные поля'
				];
			}

			if ($password !== $confirmPassword) {
				return [
					'status'  => 400,
					'message' => 'Пароль и подтверждение пароля не совпадают'
				];
			}

			$existingUser = UserTable::getList([
				'filter' => ['EMAIL' => $email]
			])->fetch();

			if ($existingUser) {
				return [
					'status'  => 400,
					'message' => 'Пользователь с таким email уже существует'
				];
			}

			$user   = new \CUser();
			$fields = [
				'NAME'             => $name,
				'EMAIL'            => $email,
				'LOGIN'            => $email,
				'PASSWORD'         => $password,
				'CONFIRM_PASSWORD' => $password,
			];

			if (!empty($secondName)) {
				$fields["LAST_NAME"] = $secondName;
			}

			if (!empty($company)) {
				$fields["WORK_COMPANY"] = $company;
			}

			if (!empty($phone)) {
				$fields["PERSONAL_PHONE"] = $phone;
			}

			$userId = $user->Add($fields);
			if (!$userId) {
				return [
					'status'  => 400,
					'message' => 'Ошибка при регистрации пользователя: ' . $user->LAST_ERROR
				];
			}

			$authResult = $USER->Login($email, $password, 'Y', 'Y');

			return [
				'status'        => 200,
				'data'          => [
					"userId" => $userId,
					"login"  => $authResult
				],
				'action'        => 'redirect',
				'actionPayload' => '/personal/'
			];
		} catch (Exception $e) {
			return [
				'status'  => 400,
				'message' => 'Произошла непредвиденная ошибка' . $e->getMessage()
			];
		}
	}

	/**
	 * Авторизация пользователя
	 *
	 * @return array{status: string, message: string, data?: array}
	 *   - status: 200 | 400
	 *   - message: Сообщение об ошибке
	 *   - data: Дополнительные данные (при успехе)
	 */
	public function loginAction(?string $email, ?string $password)
	: array {
		global $USER;

		if ($USER->IsAuthorized()) {
			return [
				'status'  => 400,
				'message' => 'Вы уже авторизованы в системе'
			];
		}

		if (empty($email)) {
			return [
				'status'  => 400,
				'message' => 'Email не может быть пустым'
			];
		}

		if (empty($password)) {
			return [
				'status'  => 400,
				'message' => 'Пароль не может быть пустым'
			];
		}

		$authResult = $USER->Login($email, $password, 'Y', 'Y');
		if ($authResult) {
			if (!$USER->IsAuthorized()) {
				return [
					'status'  => 400,
					'message' => 'Ошибка создания сессии пользователя ' . $authResult["MESSAGE"],
				];
			}

			return [
				'status' => 200,
				'data'   => [
					'userId' => $USER->GetID(),
				],
				'action' => 'reload'
			];
		} else {
			return [
				'status'  => 400,
				'message' => $authResult
			];
		}
	}

	/**
	 * Восстановление пароля пользователя
	 *
	 * @return array{status: string, message: string}
	 *   - status: 200 | 400
	 *   - message: Сообщение об ошибке
	 *   - data Дополнительные данные (при успехе)
	 *
	 * @throws \Random\RandomException При ошибке генерации пароля
	 */
	public function restorePasswordAction(?string $email)
	: array {
		if (empty($email)) {
			return [
				'status'  => 400,
				'message' => 'Поле email обязательно для восстановления пароля'
			];
		}

		try {
			$user = UserTable::getList([
				'filter' => ['=EMAIL' => $email],
				'limit'  => 1
			])->fetch();

			if (!$user) {
				return [
					'status'  => 400,
					'message' => 'Пользователь с таким email не найден'
				];
			}

			$newPassword = bin2hex(random_bytes(16));
			$cUser       = new \CUser();

			if (!$cUser->Update($user['ID'], [
				'PASSWORD'         => $newPassword,
				'CONFIRM_PASSWORD' => $newPassword
			])) {
				return [
					'status'  => 400,
					'message' => 'Ошибка при обновлении пароля: ' . $cUser->LAST_ERROR
				];
			}

			$result = Event::send([
				'EVENT_NAME' => 'USER_PASSWORD_RESET',
				'LID'        => SITE_ID,
				'C_FIELDS'   => [
					'EMAIL'    => $user['EMAIL'],
					'LOGIN'    => $user['LOGIN'],
					'PASSWORD' => $newPassword,
				]
			]);

			if (!$result->isSuccess()) {
				return [
					'status'  => 400,
					'message' => 'Ошибка при отправке письма: ' . implode(', ', $result->getErrorMessages())
				];
			}

			return [
				'status' => 200,
				'data'   => [
					'result' => $result
				]
			];
		} catch (Exception $e) {
			return [
				'status'  => 400,
				'message' => 'Произошла системная ошибка при восстановлении пароля' . $e->getMessage()
			];
		}
	}

	/**
	 * Получение информации о текущем авторизованном пользователе
	 *
	 * @return array{status: int, message?: string, data?: array}
	 *   - status: 200 | 400
	 *   - message: Сообщение об ошибке (если есть)
	 *   - data: Массив с данными пользователя (если авторизован)
	 *     - id: ID пользователя
	 *     - email: Email пользователя
	 *     - name: Имя пользователя
	 *     - lastName: Фамилия пользователя
	 */
	public function getCurrentUserAction()
	: array
	{
		global $USER;

		if (!$USER->IsAuthorized()) {
			return [
				'status'  => 400,
				'message' => 'Пользователь не авторизован'
			];
		}

		$userId   = $USER->GetID();
		$userData = UserTable::getById($userId)->fetch();

		if (!$userData) {
			return [
				'status'  => 400,
				'message' => 'Данные пользователя не найдены'
			];
		}

		return [
			'status' => 200,
			'data'   => [
				'id'       => $userId,
				'email'    => $userData['EMAIL'],
				'name'     => $userData['NAME'],
				'lastName' => $userData['LAST_NAME']
			]
		];
	}
}
