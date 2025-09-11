<?php

namespace YogurtStudio\Main\Helper;

class HrefFormatted
{
	static public function getPhoneHrefFormatted(?string $text)
	: string|null {
		$text = preg_replace('/\D/', '', $text);

		return 'tel:+' . preg_replace('/^8(\d{10})$/', '7$1', $text);
	}

	static public function getMailHrefFormatted(?string $text)
	: string|null {
		return 'mailto:' . $text;
	}
}