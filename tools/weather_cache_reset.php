<?php
	require( $_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/modules/main/include/prolog_admin_before.php" );
	require_once( $_SERVER[ "DOCUMENT_ROOT" ].BX_ROOT."/modules/main/prolog.php" );
	$sModuleId = 'weather_service';
	global $CACHE_MANAGER;
	$CACHE_MANAGER->ClearByTag( "/".md5( serialize( $sModuleId ) ) );