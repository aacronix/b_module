<div id='weather-block' style='background: $elemColor !important;'>
  <span class='title'>Погода в Йошкар-Оле</span>
  <div class='weather-row clearfix'>
      <div class='weather-cell condition' style='color: $condColor !important;'>
          <i class='weather-condition wi $icon'></i>
      </div>
      <div class='weather-cell temperature' style='color: $tempColor !important;'>
          $_GET["temp"] <i class='wi wi-celsius'></i>
      </div>
  </div>
  <div class='weather-row' title='Ветер $windDirectionMessage'>
      <div class='weather-cell wind-degree'>
          <i class='wi wi-wind towards-$windDegreeInt-deg'></i>
      </div>
      <div class='weather-cell wind-speed'>
          <span>$windSpeed $windSpeedUnit</span>
      </div>
  </div>
</div>
