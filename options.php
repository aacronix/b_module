<?
global $CACHE_MANAGER;
global $APPLICATION;

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CWidget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

CModule::IncludeModule(WEATHER_SERVICE_MODULE_ID);

CJSCore::Init(array("jquery"));
$APPLICATION->AddHeadScript('/bitrix/js/weather_service/spectrum/spectrum.js');

$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/spectrum/spectrum.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/main/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/module/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/module/sun-animation.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/component/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons.min.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons-wind.min.css");

$latitudeSelector = 'latitude';
$longitudeSelector = 'longitude';
$weatherProviderSelector = 'weather_provider';
$widgetTitleSelector = 'widget_title';
$wundergroundApiKeySelector = 'wunderground_api_key';
$forecastioApiKeySelector = 'forecastio_api_key';
$weathertriggerApiKeySelector = 'weathertrigger_api_key';
$weathertriggerAppKeySelector = 'weathertrigger_app_key';
$apixuApiKeySelector = 'apixu_api_key';
$openweatherApiKeySelector = 'openweather_api_key';
$backgroundColorKeySelector = 'background_color';
$majorTextColorSelector = 'major_text_color';
$extraTextColorSelector = 'extra_text_color';
$updateIntervalSelector = 'update_interval';
$providerInfoSelector = 'show_provider_info';
$measurementSystemSelector = 'measurement_system';

IncludeModuleLangFile(__FILE__);

$dbWidgets = main\CWeatherWidget::SelectWeatherWidgetsList($b = "sort", $o = "asc", array("ACTIVE" => "Y"));

$arWidgets = [];

while ($arWidget = $dbWidgets->Fetch()) {
    $arWidgets[] = $arWidget;
}

foreach ($arWidgets as $arWidget) {
    $widgetId = $arWidget["WIDGET_ID"];
    $widgetTabs[] = array(
        "DIV" => $widgetId,
        "TAB" => htmlspecialcharsbx($arWidget["NAME"]),
        'TITLE' => 'Настройка: ' . htmlspecialcharsbx($arWidget["NAME"]),
        'ONSELECT' => "document.forms['weather_widgets'].siteTabControl_active_tab.value='$widgetId'; app.tabChanging('$widgetId')",
    );
}

$mainTabs = array(
    array(
        "DIV" => "edit2",
        "TAB" => 'Настройка',
        "ICON" => "",
        "TITLE" => 'Настройка',
    ),
);

$mainTabControl = new CAdminTabControl('mainTabControl', $mainTabs);

$widgetTabControl = new CAdminViewTabControl('widgetsTabControl', $widgetTabs);

if ($REQUEST_METHOD == 'POST' && $_POST['add-new-widget-text'] != '' && $_POST['add-new-widget-button'] != '') {
    main\CWeatherWidget::InsertNewWidget($_POST['add-new-widget-text']);

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode(WEATHER_SERVICE_MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $mainTabControl->ActiveTabParam() . ($_REQUEST["siteTabControl_active_tab"] <> '' ? "&siteTabControl_active_tab=" . urlencode($_REQUEST["siteTabControl_active_tab"]) : ''));
}

if ($REQUEST_METHOD == 'POST' && $_POST['add-new-widget-text'] != '' && $_POST['add-new-widget-button'] != '') {
    main\CWeatherWidget::InsertNewWidget($_POST['add-new-widget-text']);

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode(WEATHER_SERVICE_MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $mainTabControl->ActiveTabParam() . ($_REQUEST["siteTabControl_active_tab"] <> '' ? "&siteTabControl_active_tab=" . urlencode($_REQUEST["siteTabControl_active_tab"]) : ''));
}

if ($REQUEST_METHOD == 'POST' && $_POST['delete-widget-button'] != '') {
    $activeTab = urlencode($_REQUEST["siteTabControl_active_tab"]);

    main\CWeatherWidget::DeleteWidgetByWidgetId($activeTab);
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode(WEATHER_SERVICE_MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $mainTabControl->ActiveTabParam() . ($_REQUEST["siteTabControl_active_tab"] <> '' ? "&siteTabControl_active_tab=" . urlencode($_REQUEST["siteTabControl_active_tab"]) : ''));
}

if ($REQUEST_METHOD == 'POST' && $_POST['Update'] != '' && $_POST['Update'] == 'Y') {
    foreach ($widgetTabs as $element) {
        $widgetId = $element['DIV'];

        main\CWeatherOption::InsertOption($widgetId, "$latitudeSelector", $_POST[$latitudeSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$longitudeSelector", $_POST[$longitudeSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$widgetTitleSelector", $_POST[$widgetTitleSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$weatherProviderSelector", $_POST[$weatherProviderSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$wundergroundApiKeySelector", $_POST[$wundergroundApiKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$forecastioApiKeySelector", $_POST[$forecastioApiKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$weathertriggerApiKeySelector", $_POST[$weathertriggerApiKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$weathertriggerAppKeySelector", $_POST[$weathertriggerAppKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$apixuApiKeySelector", $_POST[$apixuApiKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$openweatherApiKeySelector", $_POST[$openweatherApiKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$backgroundColorKeySelector", $_POST[$backgroundColorKeySelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$majorTextColorSelector", $_POST[$majorTextColorSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$extraTextColorSelector", $_POST[$extraTextColorSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$updateIntervalSelector", $_POST[$updateIntervalSelector . '_' . $widgetId]);
        main\CWeatherOption::InsertOption($widgetId, "$measurementSystemSelector", $_POST[$measurementSystemSelector . '_' . $widgetId]);

        if (isset($_POST[$providerInfoSelector]) && $_POST[$providerInfoSelector] != 'Y') {
            main\CWeatherOption::InsertOption($widgetId, "$providerInfoSelector", 'N');
        } else {
            main\CWeatherOption::InsertOption($widgetId, "$providerInfoSelector", $_POST[$providerInfoSelector . '_' . $widgetId]);
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode(WEATHER_SERVICE_MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $mainTabControl->ActiveTabParam() . ($_REQUEST["siteTabControl_active_tab"] <> '' ? "&siteTabControl_active_tab=" . urlencode($_REQUEST["siteTabControl_active_tab"]) : ''));
}

$widgetsList = main\CWeatherOption::GetOptionList();

$widgetsParametres = array();

if (count($widgetsList) > 0) {
    foreach ($widgetsList as $widget) {
        $widgetId = $widget->getName();

        $widgetsParametres[$widgetId] = $widget->toJson();
    }
}

$_REQUEST["siteTabControl_active_tab"] = DEFAULT_TAB;
?>
<style>

</style>
<form method="POST" name="weather_widgets" id="weather_widgets">
    <div class="loader">
        <div class='uil-sunny-css' style='transform:scale(0.88);'>
            <div class="uil-sunny-circle">
            </div>
            <div class="uil-sunny-light">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <label for=add-new-widget-text">Введите название виджета</label>
    <input type="text" class="add-new-widget-text" name="add-new-widget-text" value=""/>
    <input type="Submit" class="add-new-widget-button" name="add-new-widget-button" value="+ Добавить виджет"/>
    <?
    $mainTabControl->Begin();
    $mainTabControl->BeginNextTab(); ?>
    <ul class="settings-list">

        <li class="item">
            <p class="title"><?= GetMessage('SHOW_POINT_LOCATION') ?></p>
            <ul class="item-settings-list">
                <li class="item">
                    <div class="points-control">
                        <? $widgetTabControl->Begin(); ?>
                    </div>
                    <div id="map" style="width:100%; height:800px"></div>
                </li>
            </ul>
        </li>
        <li class="item">
            <? foreach ($widgetTabs as $arWidget):
            $widgetTabControl->BeginNextTab();
            $suffix = $arWidget["DIV"];
            $widgetName = $arWidget["TAB"];
            $parametres = $widgetsParametres[$suffix];
            $latitude = $parametres['latitude'];
            $longitude = $parametres['longitude'];
            $widgetTitle = $parametres['widget_title'];
            $weatherProvider = $parametres['weather_provider'];
            $wundergroundApiKey = $parametres['wunderground_api_key'];
            $forecastioApiKey = $parametres['forecastio_api_key'];
            $weathertriggerApiKey = $parametres['weathertrigger_api_key'];
            $weathertriggerAppKey = $parametres['weathertrigger_app_key'];
            $apixuApiKey = $parametres['apixu_api_key'];
            $openweatherApiKey = $parametres['openweather_api_key'];
            $backgroundColor = $parametres['background_color'];
            $majorTextColor = $parametres['major_text_color'];
            $extraTextColor = $parametres['extra_text_color'];
            $updateInterval = $parametres['update_interval'];
            $customCss = $parametres['custom_css'];
            $showProviderInfo = $parametres['show_provider_info'];
            $measurementSystem = $parametres['measurement_system'];
            ?>
            <ul class="item-settings-list">
                <li class="item">
                    <p class="title"><?= GetMessage('PROVIDERS_SETTINGS_GROUP_TITLE') ?></p>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio" value="wunderground"
                                   id="wunderground_<?= $suffix ?>" class=""><a
                                href="https://www.wunderground.com/" target="_blank"
                                class="weather_provider_title">Wunderground</a>
                        </div>
                        <div class="second-line">
                            <label for="wunderground_api_key">ApiKey:</label>
                            <input type="text" name="wunderground_api_key_<?= $suffix ?>"
                                   id="wunderground_api_key_<?= $suffix ?>" class="api_key_field"
                                   value="<?= $wundergroundApiKey ?>"/>
                        </div>
                    </div>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio" value="forecastio"
                                   id="forecastio_<?= $suffix ?>"><a
                                href="http://forecast.io/" target="_blank"
                                class="weather_provider_title">Forecast.io</a>
                        </div>
                        <div class="second-line">
                            <label for="forecastio_api_key">ApiKey:</label>
                            <input type="text" name="forecastio_api_key_<?= $suffix ?>"
                                   id="forecastio_api_key_<?= $suffix ?>" class="api_key_field"
                                   value="<?= $forecastioApiKey ?>"/>
                        </div>
                    </div>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio"
                                   value="weathertrigger"
                                   id="weathertrigger_<?= $suffix ?>">
                            <a href="http://www.weatherunlocked.com/" target="_blank"
                               class="weather_provider_title">Weather Trigger</a>
                        </div>
                        <div class="second-line">
                            <label for="weathertrigger_api_key">ApiKey:</label>
                            <input type="text" name="weathertrigger_api_key_<?= $suffix ?>"
                                   id="weathertrigger_api_key_<?= $suffix ?>" class="api_key_field"
                                   value="<?= $weathertriggerApiKey ?>"/>
                        </div>
                        <div class="second-line">
                            <label for="weathertrigger_app_key">AppKey:</label>
                            <input type="text" name="weathertrigger_app_key_<?= $suffix ?>"
                                   id="weathertrigger_app_key_<?= $suffix ?>" class="app_key_field"
                                   value="<?= $weathertriggerAppKey ?>"/>
                        </div>
                    </div>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio" value="apixu"
                                   id="apixu_<?= $suffix ?>">
                            <a href="http://www.apixu.com/" target="_blank"
                               class="weather_provider_title">Apixu</a>
                        </div>
                        <div class="second-line">
                            <label for="apixu_api_key">ApiKey:</label>
                            <input type="text" name="apixu_api_key_<?= $suffix ?>"
                                   id="apixu_api_key_<?= $suffix ?>" class="api_key_field"
                                   value="<?= $apixuApiKey ?>"/>
                        </div>
                    </div>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio" value="openweather"
                                   id="openweather_<?= $suffix ?>"><a
                                href="https://openweathermap.org/" target="_blank"
                                class="weather_provider_title">OpenWeatherMap</a>
                        </div>
                        <div class="second-line">
                            <label for="openweather_api_key">ApiKey:</label>
                            <input type="text" name="openweather_api_key_<?= $suffix ?>"
                                   id="openweather_api_key_<?= $suffix ?>" class="api_key_field"
                                   value="<?= $openweatherApiKey ?>"/>
                        </div>
                    </div>
                    <div class="provider-item">
                        <div class="first-line">
                            <input name="weather_provider_<?= $suffix ?>" type="radio" value="yahooweather"
                                   id="yahooweather_<?= $suffix ?>">Yahoo Weather
                        </div>
                    </div>
                    <? if ($suffix != 'w_0'): ?>
                        <input type="Submit" class="delete-widget-button" name="delete-widget-button"
                               value="Удалить виджет"/>
                    <? endif; ?>
                    <input type="button" class="update-preview" value="<?= GetMessage('UPDATE_PREVIEW') ?>">
                </li>
                <li class="item">
                    <p class="title"><?= GetMessage('ESTABLISHED_COORDINATES') ?></p>
                    <label for="latitude_<?= $suffix ?>"><?= GetMessage('LATITUDE') ?>:</label>
                    <input type="text" name="latitude_<?= $suffix ?>" id="latitude_<?= $suffix ?>"
                           value="<?= $latitude ?>" readonly/>
                    <label for="longitude_<?= $suffix ?>"><?= GetMessage('LONGITUDE') ?>:</label>
                    <input type="text" name="longitude_<?= $suffix ?>" id="longitude_<?= $suffix ?>"
                           value="<?= $longitude ?>" readonly/>
                </li>
                <li class="item">
                    <p class="title"><?= GetMessage('VIEW_SETTINGS') ?></p>
                    <label for="latitude_<?= $suffix ?>">Заголовок виджета:</label>
                    <input type="text" name="widget_title_<?= $suffix ?>" id="widget_title_<?= $suffix ?>"
                           value="<?= $widgetTitle ?>"/>
                </li>
                <li class="item">
                    <label for="update_interval_<?= $suffix ?>"><?= GetMessage('WIDGET_UPDATE_INTERVAL') ?></label>
                    <select name="update_interval_<?= $suffix ?>"
                            id="update-interval_<?= $suffix ?>">
                        <option value="20"<?= ($updateInterval == 20) ? 'selected' : ''; ?>>
                            20 <?= GetMessage('MINUTES') ?></option>
                        <option value="30"<?= ($updateInterval == 30) ? 'selected' : ''; ?>>
                            30 <?= GetMessage('MINUTES') ?></option>
                        <option value="60"<?= ($updateInterval == 60) ? 'selected' : ''; ?>>
                            1 <?= GetMessage('HOURS_NOMINATIVE') ?></option>
                        <option value="120"<?= ($updateInterval == 120) ? 'selected' : ''; ?>>
                            2 <?= GetMessage('HOURS_GENITIVE_2') ?></option>
                        <option value="360" <?= ($updateInterval == 360) ? 'selected' : ''; ?>>
                            6 <?= GetMessage('HOURS_GENITIVE') ?></option>
                    </select>
                </li>
                <li class="item">
                    <label for="measurement_system_<?= $suffix ?>">Система измерений</label>
                    <select name="measurement_system_<?= $suffix ?>"
                            id="measurement_system_<?= $suffix ?>">
                        <option value="metrical"<?= ($measurementSystem == 'metrical') ? 'selected' : ''; ?>>
                            Метрическая</option>
                        <option value="britain"<?= ($measurementSystem == 'britain') ? 'selected' : ''; ?>>
                            Британская</option>
                    </select>
                </li>
                <li class="item">
                    <label for="background_color_<?= $suffix ?>">Цвет заднего фона</label>
                    <input type='text' class="bg-colorpicker" value='<?= $backgroundColor ?>'
                           name="background_color_<?= $suffix ?>" id="background_color_<?= $suffix ?>"/>
                </li>
                <li class="item">
                    <label for="major_text_color_<?= $suffix ?>">Цвет основного текста</label>
                    <input type='text' class="font-colorpicker" value='<?= $majorTextColor ?>'
                           name="major_text_color_<?= $suffix ?>" id="major_text_color_<?= $suffix ?>"/>
                </li>
                <li class="item">
                    <label for="extra_text_color_<?= $suffix ?>">Цвет дополнительного шрифта</label>
                    <input type='text' class="font-colorpicker" value='<?= $extraTextColor ?>'
                           name="extra_text_color_<?= $suffix ?>" id="extra_text_color_<?= $suffix ?>"/>
                </li>
                <li class="item">
                    <label for="show_provider_info_<?= $suffix ?>">Показывать каким провайдером предоставляется
                        погода?</label>
                    <input type='checkbox' value="Y" id="show_provider_info_<?= $suffix ?>"
                           name="show_provider_info_<?= $suffix ?>" <?php if ($showProviderInfo == 'Y') {
                        echo 'checked';
                    } ?>/>
                </li>
                <? if ($suffix != DEFAULT_TAB) : ?>
                    <li class="item">
                        <label for=""></label>
                        <input type="text" value="<?= $widgetName ?>" name="widget_name_text_<?= $suffix ?>"
                               id="widget_name_text_<?= $suffix ?>">
                        <input type="button" value="Переименовать виджет" class="copy-widget"
                               name="widget_name_<?= $suffix ?>" id="widget_name_<?= $suffix ?>"
                               onclick="app.renameWidget()">
                    </li>
                <? endif; ?>
                <? endforeach; ?>
                </li>
            </ul>
            <? $widgetTabControl->End(); ?>
            <? $mainTabControl->Buttons(); ?>
            <input type="submit" name="Update" value="<?= GetMessage('SAVE_BUTTON') ?>" class="adm-btn-save"/>
            <input type="submit" name="Apply" value="<?= GetMessage('APPLY_BUTTON') ?>" class="disabled"/>
            <input type="hidden" name="Update" value="Y"/>
            <input type="hidden" name="siteTabControl_active_tab"
                   value="<?= htmlspecialcharsbx($_REQUEST["siteTabControl_active_tab"]) ?>"/>
            <? $mainTabControl->End(); ?>
</form>
<!--yandexmaps -->
<?php
if (LANGUAGE_ID == 'ru'):
    echo '<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>';
else:
    echo '<script src="//api-maps.yandex.ru/2.1/?lang=en_US" type="text/javascript"></script>';
endif;
?>
<script>
    function app() {

    }

    $(document).ready(function () {

        $('body').addClass('form-sending');

        appendButton();

        var currentWidgetRef = $("input[name=siteTabControl_active_tab]");

        var widgetsParametres = <?=json_encode($widgetsParametres)?>;
        var weather_provider = widgetsParametres[currentWidgetRef.val()]["weather_provider"];

        document.getElementById(weather_provider + '_' + currentWidgetRef.val()).checked = true;
        getWeather(null);

        function setPosition(position) {
            var currentWidget = document.forms['weather_widgets'].siteTabControl_active_tab.value;

            document.getElementById('latitude_' + currentWidget).value = position[0];
            document.getElementById('longitude_' + currentWidget).value = position[1];
        }

        var oldStyle;

        $(".bg-colorpicker").spectrum({
            preferredFormat: "rgb",
            showAlpha: true,
            showButtons: false,
            showInput: true,
            containerClassName: 'bg-colorpicker-container',
            replacerClassName: 'bg-colorpicker-replacer',
            showPalette: true,
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: 'Настройка',
            togglePaletteLessText: 'Закрыть',
            hideAfterPaletteSelect: true,
            showInitial: true,
            palette: [
                ['IndianRed', 'DarkRed'],
                ['Pink', 'PaleVioletRed'],
                ['Coral', 'Orange'],
                ['Gold', 'DarkKhaki'],
                ['Lavender', 'DarkSlateBlue'],
                ['GreenYellow', 'Teal'],
                ['Aqua', 'MidnightBlue'],
                ['Cornsilk', 'Maroon'],
                ['White', 'MistyRose'],
                ['Gainsboro', 'Black'],
                ['rgba 0 0 0 0']
            ]
        });

        $(".font-colorpicker").spectrum({
            preferredFormat: "rgb",
            showInput: true,
            showButtons: false,
            containerClassName: 'font-colorpicker-container',
            replacerClassName: 'font-colorpicker-replacer',
            showPalette: true,
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: 'Настройка',
            togglePaletteLessText: 'Закрыть',
            hideAfterPaletteSelect: true,
            showInitial: true,
            palette: [
                ['IndianRed', 'DarkRed'],
                ['Pink', 'PaleVioletRed'],
                ['Coral', 'Orange'],
                ['Gold', 'DarkKhaki'],
                ['Lavender', 'DarkSlateBlue'],
                ['GreenYellow', 'Teal'],
                ['Aqua', 'MidnightBlue'],
                ['Cornsilk', 'Maroon'],
                ['White', 'MistyRose'],
                ['Gainsboro', 'Black'],
                ['rgba 0 0 0 0']
            ]
        });

        function getPositionToWidget(widgetId) {
            return ([
                document.getElementById('latitude_' + widgetId).value, document.getElementById(
                    'longitude_' + widgetId).value
            ]);
        }

        function getNormalizedCoords(coords) {
            return [Math.round(coords[0] * 1000) / 1000, Math.round(coords[1] * 1000) / 1000];
        }

        $('.save-position').click(function () {
            document.getElementById('latitude').value = document.getElementById('latitude_set').value;
            document.getElementById('longitude').value = document.getElementById('longitude_set').value;
            getWeather(null);
        });

        $('.cache-reset-button').click(function () {//нажатие на кнопку сброса кеша
            var url = "<?=WEATHER_TOOLS_RELATIVE?>" + '/weather_cache_reset.php';

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    moduleId: "<?=WEATHER_SERVICE_MODULE_ID?>"
                }
            });
        });

        $('.update-preview').click(function () {//нажатие на кнопку сброса кеша
            getWeather(null);
        });

//        $('.weather-providers input:radio').change(function () {
//            getWeather(null);
//        });

        function showPreviewError() {
            $('#weather-block').remove();
            var template = "<div id='weather-block' class='b-widget weather-widget-preview wrong-keys v_2_2'><?= GetMessage('CANT_GET_WEATHER') ?></div>";

            var html = $.parseHTML(template);
            $('body').append(html)
        }

        function getWeather(cwidget) {
            var currentWidget = cwidget;

            if (currentWidget == null) {
                currentWidget = currentWidgetRef.val();
            }
            currentWidget = "_" + currentWidget;

            var weatherProviderElement = $('input[name=weather_provider' + currentWidget + ']:checked');

            var weatherProviderElementValue = weatherProviderElement.val();
            var weatherProviderParentElement = weatherProviderElement.parent().parent();
            var weatherProviderApiKeyElement = $('#' + weatherProviderElementValue + "_api_key" + currentWidget);
            var weatherProviderAppKeyElement = $('#' + weatherProviderElementValue + "_app_key" + currentWidget);
            var weatherProviderApiKeyValue = weatherProviderApiKeyElement.val() == null ? -1 : weatherProviderApiKeyElement.val();
            var weatherProviderAppKeyValue = weatherProviderAppKeyElement.val() == null ? -1 : weatherProviderAppKeyElement.val();
            var latitude = $('#latitude' + currentWidget).val();
            var longitude = $('#longitude' + currentWidget).val();

            var url = "<?=WEATHER_TOOLS_RELATIVE?>" + '/weather_api.php';

            if ((weatherProviderElementValue == 'yahooweather') || ((weatherProviderApiKeyValue != '') && (weatherProviderApiKeyValue != -1)) || (
                (weatherProviderApiKeyValue != '') && (weatherProviderApiKeyValue != -1) &&
                (weatherProviderAppKeyValue != '') && (weatherProviderAppKeyValue != -1))) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    data: {
                        action: 'get-weather',
                        provider: weatherProviderElementValue,
                        apiKey: weatherProviderApiKeyValue,
                        appKey: weatherProviderAppKeyValue,
                        latitude: latitude,
                        longitude: longitude,
                        unit: 'metrical'
                    },
                    beforeSend: function () {
                        $('body').addClass('get-weather-process');
                    },
                    complete: function () {
                        $('body').removeClass('get-weather-process');
                    },
                    success: function (data) {
                        var icon = data.icon;
                        var temp = data.temp;
                        var windDegree = data.wind.windDegree;
                        var windSpeed = data.wind.windSpeed;
                        $('#weather-block').remove();

                        if (icon == null || temp == null || windDegree == null || windSpeed == null) {
                            showPreviewError();
                        } else {
                            template = "<div id='weather-block' " +
                                "class='b-widget v_2_2 clearfix weather-widget-preview'><p class='preview-text'><?= GetMessage('WIDGET_PREVIEW') ?></p>" +
                                "<div class='weather-row'><div class='weather-cell condition'><i class='weather-condition wi " + icon + "'>" +
                                "</i></div><div class='weather-cell temperature'>" + temp + " <i class='wi wi-celsius'>" +
                                "</i></div></div><div class='weather-row'>" +
                                "<div class='weather-cell wind-degree'><i class='wi wi-wind towards-" + Math.round(windDegree) + "-deg'>" +
                                "</i></div><div class='weather-cell wind-speed'>" +
                                "<span>" + windSpeed + " <?= GetMessage('KPH') ?></span></div></div></div>";
                            var html = $.parseHTML(template);
                            $('body').append(html)
                        }
                    }
                });
            } else {
                showPreviewError();
            }
        }

        function keysIsValid(report) {
            var url = "<?=WEATHER_TOOLS_RELATIVE?>" + '/weather_api.php';

            console.log(url);

            return $.ajax({
                typ: "GET",
                url: url,
                dataType: 'json',
                data: {
                    action: 'validateKey',
                    provider: report.provider,
                    apiKey: report.apiKey,
                    appKey: report.appKey
                },
                dataFilter: function (data) {
                    console.log(data);
                    var data = JSON.parse(data);

                    var response = [];
                    response = {
                        code: data.code,
                        widget: report.widget,
                        provider: report.provider,
                        apiKeyValidation: report.apiKeyValidation,
                        appKeyValidation: report.appKeyValidation,
                        apiKey: report.appKey,
                        appKey: report.appKey
                    };

                    return JSON.stringify(response);
                }
            });
        }

        function clearNoticeClasses() {
            $('.error-message').remove();
            $('.provider-item').removeClass('miss_api_key').removeClass('miss_app_key').removeClass('invalid-key');
        }

        function sendForm() {
            $.ajax({
                type: 'post',
                url: '<? echo $APPLICATION->GetCurPage() . "?mid=" . urlencode(WEATHER_SERVICE_MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $mainTabControl->ActiveTabParam() . ($_REQUEST["siteTabControl_active_tab"] <> '' ? "&siteTabControl_active_tab=" . urlencode($_REQUEST["siteTabControl_active_tab"]) : '') ?>',
                data: $('form').serialize(),
                beforeSend: function () {
                    $('body').addClass('form-sending');
                },
                complete: function () {
                    $('body').removeClass('form-sending');
                },
                success: function () {
                    location.reload();
                }
            });
        }

        function appendButton() {
            $('#view_tab_w_0').append('<input type="button" class="add-copy" id="add-copy-widget" name="add-copy-widget" value="+" onclick="app.makeCopy()" title="Копировать виджет"/>')
        }

        function validateForm() {
            $('body').addClass('form-sending');
            clearNoticeClasses();
            var hasErrorGlobal = false;
            var providers = [];

            for (var key in widgetsParametres) {
                var provider = $('input[name=weather_provider_' + key + ']:checked').val();
                if (provider != undefined) {
                    var node = [{widget: key, provider: provider}];
                    providers = providers.concat(node);
                }
            }

            var errorReportsStack = [];

            for (var i = 0; i < providers.length; i++) {
                var widget = providers[i].widget;
                var provider = providers[i].provider;
                var hasError = false;

                errorReportsStack[i] = [];

                errorReportsStack[i]['widget'] = widget;
                errorReportsStack[i]['provider'] = provider;

                var weatherProviderElement = $('input[name=weather_provider_' + widget + ']input[value=' + provider + ']');
                var weatherProviderParentElement = weatherProviderElement.parent().parent();
                var weatherProviderApiKeyElement = $('#' + provider + "_api_key_" + widget);
                var weatherProviderAppKeyElement = $('#' + provider + "_app_key_" + widget);
                var weatherProviderApiKeyValue = weatherProviderApiKeyElement.val() == null ? -1 : weatherProviderApiKeyElement.val();
                var weatherProviderAppKeyValue = weatherProviderAppKeyElement.val() == null ? -1 : weatherProviderAppKeyElement.val();

                if (weatherProviderApiKeyValue.length == 0 && weatherProviderApiKeyValue != -1) {
                    console.log('api key empty field');
                    weatherProviderParentElement.addClass("miss_api_key");

                    var msgElem = document.createElement('p');
                    msgElem.className = "error-message";
                    msgElem.innerHTML = "<?= GetMessage('API_KEY_NEED') ?>";

                    weatherProviderParentElement.append(msgElem);

                    errorReportsStack[i]['apiKeyValidation'] = false;
                } else {
                    console.log('api key filled');
                    errorReportsStack[i]['apiKeyValidation'] = true;
                    errorReportsStack[i]['apiKey'] = weatherProviderApiKeyValue;
                }

                if (weatherProviderAppKeyValue.length == 0 && weatherProviderAppKeyValue != -1) {
                    weatherProviderParentElement.addClass("miss_app_key");

                    var msgElem = document.createElement('p');
                    msgElem.className = "error-message";
                    msgElem.innerHTML = "<?= GetMessage('APP_KEY_NEED') ?>";

                    weatherProviderParentElement.append(msgElem);

                    errorReportsStack[i]['appKeyValidation'] = false;
                } else if (weatherProviderAppKeyValue.length != 0 && weatherProviderAppKeyValue != -1) {
                    console.log('app key filled');
                    errorReportsStack[i]['appKeyValidation'] = true;
                    errorReportsStack[i]['appKey'] = weatherProviderAppKeyValue;
                } else {
                    errorReportsStack[i]['appKeyValidation'] = true;
                    errorReportsStack[i]['appKey'] = '';
                }
            }

            console.log(errorReportsStack);
//            $('body').removeClass('get-weather-process');

            return errorReportsStack;
        }

        $('form').on('submit', function (e) {
            var $button = $(document.activeElement);
            var btnAction = $button.attr('name');

            if (btnAction == 'Update') {
                event.preventDefault ? event.preventDefault() : (event.returnValue = false);

                var errorOnForm = false;
                var formValidationReport = validateForm();

                var deferred = [];
                var iterator = 0;
                for (var i = 0; i < formValidationReport.length; i++) {
                    if (formValidationReport[i].apiKeyValidation && formValidationReport[i].appKeyValidation) {
                        deferred[iterator] = [];
                        deferred[iterator] = keysIsValid(formValidationReport[i]);

                        iterator++;
                    } else {
                        errorOnForm = true;
                    }
                }

                $.when.apply(null, deferred).done(function () {
                    var objects = arguments;
                    var errorAccumulator = false;

                    console.log("objs ");
                    console.log(objects);
                    if (deferred.length > 1) {
                        for (var i = 0; i < deferred.length; i++) {

                            console.log(objects[i][0].code);
                            if (!objects[i][0].code) {
                                errorAccumulator = true;
                            }
                        }
                    } else {
                        for (var i = 0; i < deferred.length; i++) {

                            console.log(objects[i].code);
                            if (!objects[i].code) {

                                var element = $('input[name=weather_provider_' + objects[i].widget + ']input[value=' + objects[i].provider + ']').parent().parent();
                                element.addClass('invalid-key');

                                var msgElem = document.createElement('p');
                                msgElem.className = "error-message";
                                msgElem.innerHTML = "<?= GetMessage('WRONG_API_KEY_ERROR') ?>";

                                if (objects[i].provider == 'weathertrigger') {
                                    msgElem.innerHTML = "<?= GetMessage('WRONG_APP_KEY_ERROR') ?>";
                                }

                                element.append(msgElem);
                                errorAccumulator = true;
                            }
                        }
                    }

                    if (errorAccumulator || errorOnForm) {
                        console.log('form has errors');
                    } else {
                        console.log('form can send');
                        sendForm();
                    }
                });
            }
        });

        function removeDuplicatesInJSON(json) {
            var arr = [],
                collection = [];

            $.each(json, function (index, value) {
                if ($.inArray(value.id, arr) == -1) {
                    arr.push(value.id);
                    collection.push(value);
                }
            });

            return collection;
        }

        var positionPoint; // геообъект позиции с карты, выбранной пользователем
        var position; // позиция с карты, выбранная пользователем
        ymaps.ready(init).then(function () {
            $('body').removeClass('form-sending');
        });

        function init() {
            const DEFAULT_ZOOM = 10;
            var gCollection = new ymaps.GeoObjectCollection();
            var geolocation = ymaps.geolocation,
                myMap = new ymaps.Map('map', {
                    center: [55, 34],
                    zoom: DEFAULT_ZOOM,
                    controls: {}
                });
            positionPoint = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: getPositionToWidget(currentWidgetRef.val())
                },
                properties: {
                    widgetLink: currentWidgetRef.val(),
                    remove: true
                }
            });

            var searchControl = new ymaps.control.SearchControl({
                options: {
                    noPlacemark: true
                }
            });

            myMap.controls.add(searchControl, {left: '40px', top: '10px'});
            gCollection.add(positionPoint);
            myMap.geoObjects.add(gCollection);

            function clearMarkList(point) {
                gCollection.each(function (element) {
                    var elementWidgetId = element.properties._data.widgetLink ? element.properties._data.widgetLink :
                        false;
                    var canRemove = element.properties._data.remove ? element.properties._data.remove : false;
                    if (elementWidgetId && elementWidgetId != undefined && canRemove) {
                        if (elementWidgetId == point) {
                            gCollection.remove(element);
                        }
                    }
                })
            }

            function refreshMap() {
                myMap.geoObjects.removeAll(gCollection);
                myMap.geoObjects.add(gCollection);
            }

            geolocation.get({
                provider: 'yandex',
                mapStateAutoApply: true
            }).then(function (result) {
                result.geoObjects.options.set('preset', 'islands#redCircleIcon');
                result.geoObjects.get(0).properties.set({
                    balloonContentBody: '<?= GetMessage('YOUR_LOCATION') ?>'
                });
                myMap.geoObjects.add(result.geoObjects);
            });

            geolocation.get({
                provider: 'browser',
                mapStateAutoApply: true
            }).then(function (result) {
                result.geoObjects.options.set('preset', 'islands#blueCircleIcon');
                myMap.geoObjects.add(result.geoObjects);
            });

            myMap.events.add('click', function (e) {
                clearMarkList(currentWidgetRef.val());
                var coords = e.get('coords');
                position = getNormalizedCoords([coords[0], coords[1]]);
                setPosition(position);
                positionPoint = new ymaps.GeoObject({
                    geometry: {
                        type: "Point",
                        coordinates: coords
                    },
                    properties: {
                        widgetLink: currentWidgetRef.val(),
                        remove: true
                    }
                });

                gCollection.add(positionPoint);
                getWeather(null);
            });

            app.tabChanging = function (currentWidget) {
                var weather_provider = widgetsParametres[currentWidget]["weather_provider"];

                var weatherProviderElement = $('input[name=weather_provider_' + currentWidgetRef.val() +
                    ']:checked', 'form');
                if (!weatherProviderElement.val()) {
                    document.getElementById(weather_provider + '_' + currentWidget).checked = true;
                }

                gCollection.each(function (element) {
                    var elementWidgetId = element.properties._data.widgetLink ? element.properties._data.widgetLink :
                        false;
                    if (elementWidgetId && elementWidgetId != undefined) {
                        if (elementWidgetId != currentWidget) {
                            gCollection.remove(element);
                        }
                    }
                });

                var coords = getPositionToWidget(currentWidget);

                var position = new ymaps.GeoObject({
                    geometry: {
                        type: "Point",
                        coordinates: coords
                    },
                    properties: {
                        widgetLink: currentWidget,
                        remove: true
                    }
                });

                myMap.setCenter(coords, DEFAULT_ZOOM, {
                    checkZoomRange: true
                });

                gCollection.add(position);
                getWeather(currentWidget);
            };


            function copyWidgetAjax(config) {
                var url = "<?=WEATHER_TOOLS_RELATIVE?>" + '/copy_widget.php';

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'text',
                    data: config,
                    beforeSend: function () {
                        $('body').addClass('get-weather-process');
                    },
                    complete: function () {
                        $('body').removeClass('get-weather-process');
                    },
                    success: function (data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            app.makeCopy = function () {
                var latitudeSelector = 'latitude_w_0';
                var longitudeSelector = 'longitude_w_0';
                var weatherProviderSelector = 'weather_provider_w_0';
                var widgetTitleSelector = 'widget_title_w_0';
                var wundergroundApiKeySelector = 'wunderground_api_key_w_0';
                var forecastioApiKeySelector = 'forecastio_api_key_w_0';
                var weathertriggerApiKeySelector = 'weathertrigger_api_key_w_0';
                var weathertriggerAppKeySelector = 'weathertrigger_app_key_w_0';
                var apixuApiKeySelector = 'apixu_api_key_w_0';
                var openweatherApiKeySelector = 'openweather_api_key_w_0';
                var backgroundColorKeySelector = 'background_color_w_0';
                var majorTextColorSelector = 'major_text_color_w_0';
                var extraTextColorSelector = 'extra_text_color_w_0';
                var updateIntervalSelector = 'update_interval_w_0';
                var showProviderInfoSelector = 'show_provider_info_w_0';

                var latitudeVal = $('#' + latitudeSelector).val();
                var longitudeVal = $('#' + longitudeSelector).val();
                var weatherProviderVal = $('input[name=' + weatherProviderSelector + ']:checked').val();
                var widgetTitle = $('#' + widgetTitleSelector).val();
                var wundergroundApiKeyVal = $('#' + wundergroundApiKeySelector).val();
                var forecastioApiKeyVal = $('#' + forecastioApiKeySelector).val();
                var weathertriggerApiKeyVal = $('#' + weathertriggerApiKeySelector).val();
                var weathertriggerAppKeyVal = $('#' + weathertriggerAppKeySelector).val();
                var apixuApiKeyVal = $('#' + apixuApiKeySelector).val();
                var openweatherApiKeyVal = $('#' + openweatherApiKeySelector).val();
                var backgroundColorKeyVal = $('#' + backgroundColorKeySelector).val();
                var majorTextColorVal = $('#' + majorTextColorSelector).val();
                var extraTextColorVal = $('#' + extraTextColorSelector).val();
                var updateIntervalVal = $('input[name=' + updateIntervalSelector + ']:checked').val();
                var showProviderInfoVal = 'Y';

                var config = {
                    latitude: latitudeVal,
                    longitude: longitudeVal,
                    weatherProvider: weatherProviderVal,
                    widgetTitle: widgetTitle,
                    wundergroundApiKey: wundergroundApiKeyVal,
                    forecastioApiKey: forecastioApiKeyVal,
                    weathertriggerApiKey: weathertriggerApiKeyVal,
                    weathertriggerAppKey: weathertriggerAppKeyVal,
                    apixuApiKey: apixuApiKeyVal,
                    openweatherApiKey: openweatherApiKeyVal,
                    backgroundColorKey: backgroundColorKeyVal,
                    majorTextColor: majorTextColorVal,
                    extraTextColor: extraTextColorVal,
                    updateInterval: updateIntervalVal,
                    showProviderInfo: showProviderInfoVal
                };

                copyWidgetAjax(config);
            };

            app.renameWidget = function () {
                $('body').addClass('form-sending');
                var url = "<?=WEATHER_TOOLS_RELATIVE?>" + '/rename_widget.php';
                var currentWidget = currentWidgetRef.val();

                var widgetNameSelector = 'widget_name_text_' + currentWidget;
                var widgetNameElement = $('#' + widgetNameSelector);

                var widgetNameVal = widgetNameElement.val();

                var config = {
                    widgetId: currentWidget,
                    newName: widgetNameVal
                };

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'text',
                    data: config,
                    complete: function () {
                        $('body').removeClass('form-sending');
                    },
                    success: function (data) {
                        console.log(data);
                        widgetNameElement.val(data);
                        $('#view_tab_' + currentWidget).text(data);
                    }
                });
            }
        }

    })
    ;
</script>