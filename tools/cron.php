#!/usr/bin/php
<?php
	$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS", true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	set_time_limit(0);

	//ваш код...

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>