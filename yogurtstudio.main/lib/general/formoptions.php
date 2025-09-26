<?php

namespace YogurtStudio\Main\General;

class FormOptions
{
	public function saveAction($data): array
	{
		$file = $_SERVER["DOCUMENT_ROOT"] . "/upload/form_options.json";
		file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

		return ["status"  => "success",
		        "message" => "Форма успешно сохранена",
		];
	}

	public function getFieldOptions()
	: array
	{
		$json = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/upload/form_options.json");

		return json_decode($json, true);
	}
}
