var wxAppId = "3XAMPLK3YW1THL3TT3RSANDNUMB3RS";

function formatCurrentWx(wxData){
    var wxArray = {};
    if(wxData.cod != 200){
        wxArray = {
            cod: wxData.cod
        }
    }else{
        wxArray = {
            cod:        wxData.cod,
            temp:       wxData.main.temp,
            humidity:   wxData.main.humidity,
            pressure:   wxData.main.pressure,
            temp_max:   wxData.main.temp_max,
            temp_min:   wxData.main.temp_min,
            wind_deg:   wxData.wind.deg,
            wind_speed: wxData.wind.speed,
            name:       wxData.name,
            conditions: wxData['weather']['0']['main'],
            icon:       wxData['weather']['0']['icon']
        };
    }
    jsPost('format-weather.php', toQueryString(wxArray), showWx);
}

function toQueryString(obj) {
    var parts = [];
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) {
            parts.push(encodeURIComponent(i) + "=" + encodeURIComponent(obj[i]));
        }
    }
    return parts.join("&");
}

function showWx(html){
    document.querySelector('.wx-container').innerHTML = html;
}

function jsPost(url, data, callback){
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function() {
        if(request.readyState == 4 && request.status == 200) {
            callback(request.responseText);
        }
    }
    request.send(data);
}

function jsGet(getJsonUrl, callback){
    var request = new XMLHttpRequest();
    request.open('GET', getJsonUrl, true);
    request.onload = function(){
        if(request.readyState == 4 && request.status >= 200 && request.status < 500){
            var data = JSON.parse(request.responseText);
            return callback(data);
        }
    }
    request.onerror = function(){ };
    request.send();
}

function submitForm(e){
    e.preventDefault();
    var cityState = document.getElementById('location-input'),
        query = cityState.value.split(",")[0],
        wxCurrent = "http://api.openweathermap.org/data/2.5/weather?q="+query+'&APPID='+wxAppId;
    if(cityState.value == '' || /^[a-zA-Z, ]*$/.test(cityState.value) == false){
        cityState.classList.add('error');
    }else{
        cityState.classList = cityState.className.replace('error', '');
        updateUrl(query);
        jsGet(wxCurrent, formatCurrentWx);
    }
}

function updateUrl(query){
    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?city='+query;
        window.history.pushState({path:newurl},'',newurl);
    }
}

document.querySelector('.location-submit').addEventListener('click', submitForm);