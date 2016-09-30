<?php
if(isset($_POST['cod'])){$wxData = $_POST;}

if($wxData['cod'] != 200){
    $wxHtml = "<h3>City not found.</h3>";
}
else{
    $temp        = round(kelvinToFahrenheit($wxData['temp']), 0);
    $humidity    = $wxData['humidity'];
    $pressure    = $wxData['pressure'];
    $windDeg     = $wxData['wind_deg'];
    $windSpeed   = $wxData['wind_speed'];
    $conditions  = $wxData['conditions'];
    $icon        = $wxData['icon'];

    $wxHtml  = '<div class="row">';
    $wxHtml .= '<div class="col lg"><h2 class="current-temp">'.$temp.'<span class="deg">&deg;</span></h2>';
    $wxHtml .= '<p>'.$conditions.'&nbsp;&nbsp;&nbsp;'.$humidity.'% RH&nbsp;&nbsp;&nbsp;'.$pressure.'mb</p></div>';
    $wxHtml .= '<div class="col sm icon-wrap">';
    $wxHtml .= '<img src="images/'.$icon.'.svg" alt="'.$conditions.' Icon" />';
    $wxHtml .= '</div>';
    $wxHtml .= '<div class="col sm">';
    $wxHtml .= '<div class="wind-wrap"><div class="wind-component"><div class="wind-dial" style="transform: rotate('.updateWindAxis($windDeg).'deg)"></div></div>';
    $wxHtml .= '<span class="wind-desc">'.degToCompass($windDeg).' wind<br>at '.round($windSpeed, 1).' knot'. pluralOrNot(round($windSpeed, 1)).'</span>';
    $wxHtml .= '</div></div></div>';
}

if(isset($_POST['cod'])){echo $wxHtml;}

function kelvinToFahrenheit($kelvin){
	return round(($kelvin * (9/5) - 459.67), 1);//F = K * 9/5 − 459.67
}

function updateWindAxis($deg){
    $compassDir;
    if($deg > 89){
        $compassDir = $deg - 90;
    }else{
        $compassDir = $deg + 270;
    }
    return $compassDir;
}

function degToCompass($deg){
    $conversion = (int) ($deg/(360/16));
    $direction = ["North","NNE","NE","ENE","East","ESE", "SE", "SSE","South","SSW","SW","WSW","West","WNW","NW","NNW"];
    return $direction[$conversion];
}

function pluralOrNot($value){
    if($value == 1 || $value == '1'){
        return '';
    }else{
        return 's';
    }
}