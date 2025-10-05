const OPENWEATHER_API_KEY = "9149f1272cc3234e8d8db7652eabbb68";
const TROY_COORDS = {lat: 42.7284, lon: -73.6918};

const weatherDiv = document.getElementById("weather");
const daylightDiv = document.getElementById("daylight");

const map = L.map('map').setView([TROY_COORDS.lat, TROY_COORDS.lon], 11);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
}).addTo(map);

const marker = L.marker([TROY_COORDS.lat, TROY_COORDS.lon])
    .addTo(map)
    .bindPopup("Troy, NY")
    .openPopup();

getWeather();
getDaylight();

function getWeather() {
    const url = "https://api.openweathermap.org/data/2.5/weather?lat=" +
        TROY_COORDS.lat + "&lon=" + TROY_COORDS.lon +
        "&units=metric&appid=" + OPENWEATHER_API_KEY;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const city = data.name;
            const temp = data.main.temp;
            const desc = data.weather[0].description;
            const humidity = data.main.humidity;
            const wind = data.wind.speed;
            const icon = data.weather[0].icon;

            let tempEmoji = "";
            let humidityEmoji = "";
            let windEmoji = "";

            if (temp <= 5) {
                tempEmoji = "❄️🥶";
            } else if (temp > 5 && temp <= 15) {
                tempEmoji = "🧥🍂";
            } else if (temp > 15 && temp <= 25) {
                tempEmoji = "🌤️😊";
            } else if (temp > 25 && temp <= 32) {
                tempEmoji = "☀️😎";
            } else {
                tempEmoji = "🔥🥵";
            }

            if (humidity <= 30) {
                humidityEmoji = "🌵💨";
            } else if (humidity > 30 && humidity <= 60) {
                humidityEmoji = "💧🌤️";
            } else if (humidity > 60 && humidity <= 80) {
                humidityEmoji = "💦☁️";
            } else {
                humidityEmoji = "🥵💦";
            }

            if (wind <= 1) {
                windEmoji = "😌";
            } else if (wind > 1 && wind <= 5) {
                windEmoji = "🍃🌬️";
            } else if (wind > 5 && wind <= 10) {
                windEmoji = "💨🌪️";
            } else {
                windEmoji = "😳🌪️";
            }

            weatherDiv.innerHTML = `
                <h2>Weather in ${city}</h2>
                <img src="https://openweathermap.org/img/wn/${icon}.png" alt="${desc}">
                <p><b>${desc}</b></p>
                <p>Temperature: ${temp}°C ${tempEmoji}</p>
                <p>Humidity: ${humidity}% ${humidityEmoji}</p>
                <p>Wind Speed: ${wind} m/s ${windEmoji}</p>
            `;
        })
        .catch(error => {
            weatherDiv.innerHTML = "⚠️ Unable to fetch weather data.";
            console.error(error);
        });
}

function getDaylight() {
    const url = "https://api.sunrise-sunset.org/json?lat=" +
        TROY_COORDS.lat + "&lng=" + TROY_COORDS.lon + "&formatted=0";

    fetch(url)
    .then(response => response.json())
    .then(data => {
        const sunrise = new Date(data.results.sunrise).toLocaleTimeString();
        const sunset = new Date(data.results.sunset).toLocaleTimeString();
        const dayLength = data.results.day_length;

        daylightDiv.innerHTML = `
                <h2>Daylight Info</h2>
                <p>🌅 Sunrise: ${sunrise}</p>
                <p>🌇 Sunset: ${sunset}</p>
                <p>🕒 Day Length: ${dayLength}</p>
            `;
    })
    .catch(error => {
        daylightDiv.innerHTML = "⚠️ Unable to fetch daylight data."
        console.error(error);
    })
}