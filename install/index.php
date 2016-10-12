<?
	IncludeModuleLangFile( __FILE__ );

	Class weather_service extends CModule {

		var $MODULE_ID = "weather_service";
		var $MODULE_VERSION;
		var $MODULE_VERSION_DATE;
		var $MODULE_NAME;
		var $MODULE_DESCRIPTION;
		var $MODULE_CSS;

		function weather_service() {
			$arModuleVersion = array();
			$path            = str_replace( "\\", "/", __FILE__ );
			$path            = substr( $path, 0, strlen( $path ) - strlen( "/index.php" ) );
			include( $path."/version.php" );
			if ( is_array( $arModuleVersion ) && array_key_exists( "VERSION", $arModuleVersion ) ) {
				$this->MODULE_VERSION      = $arModuleVersion["VERSION"];
				$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
			}
			$this->MODULE_NAME        = GetMessage( "MODULE_NAME" );
			$this->MODULE_DESCRIPTION = GetMessage( 'INSTALLATION_TEXT' );
		}

		function InstallFiles( $arParams = array() ) {
			CopyDirFiles( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true );
			CopyDirFiles( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/tools", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/{$this->MODULE_ID}", true, true );
			CopyDirFiles( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/css/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/css/{$this->MODULE_ID}/", true, true );
			CopyDirFiles( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/fonts/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/fonts/{$this->MODULE_ID}/", true, true );

			return true;
		}

		function UnInstallFiles() {
			DeleteDirFiles( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/tools/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/{$this->MODULE_ID}" );
			DeleteDirFilesEx( "/bitrix/components/".$this->MODULE_ID );
			DeleteDirFilesEx( "/bitrix/css/{$this->MODULE_ID}/" );
			DeleteDirFilesEx( "/bitrix/fonts/{$this->MODULE_ID}/" );

			return true;
		}

		function DoInstall() {
			global $DOCUMENT_ROOT, $APPLICATION, $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag( "/".md5( serialize( $this->MODULE_ID ) ) );
			$this->InstallFiles();
			$this->InstallDB();
			RegisterModule( $this->MODULE_ID );
			$APPLICATION->IncludeAdminFile( "Установка модуля {$this->MODULE_ID}", $DOCUMENT_ROOT."/bitrix/modules/{$this->MODULE_ID}/install/step.php" );
		}

		function DoUninstall() {
			global $DOCUMENT_ROOT, $APPLICATION, $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag( "/".md5( serialize( $this->MODULE_ID ) ) );
			$this->UnInstallFiles();
			$this->UnInstallDB();
			UnRegisterModule( $this->MODULE_ID );
			$APPLICATION->IncludeAdminFile( "Деинсталляция модуля {$this->MODULE_ID}", $DOCUMENT_ROOT."/bitrix/modules/{$this->MODULE_ID}/install/unstep.php" );
		}

		public function InstallDB() {
			global $DB, $DBType, $APPLICATION;

			$DB->RunSQLBatch( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/db/".$DBType."/install.sql" );

			return true;
		}

		public function UnInstallDB() {
			global $APPLICATION, $DB, $DOCUMENT_ROOT;

			$DB->RunSQLBatch( $DOCUMENT_ROOT."/bitrix/modules/{$this->MODULE_ID}/install/db/".strtolower( $DB->type )."/uninstall.sql" );

			return true;
		}
	}

?>