const OPENWEATHER_API_KEY = "9149f1272cc3234e8d8db7652eabbb68";

// Default fallback: Troy, NY (used if user denies location access)
const DEFAULT_COORDS = {lat: 42.7284, lon: -73.6918};

const weatherDiv = document.getElementById("weather");
const daylightDiv = document.getElementById("daylight");

let map;

// === Initialize the map after getting user's location ===
function initMap(lat, lon) {
    map = L.map('map').setView([lat, lon], 11);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lon])
        .addTo(map)
        .bindPopup("Your Location 📍")
        .openPopup();

    getWeather(lat, lon);
    getDaylight(lat, lon);
}

// === Try to get user location using HTML5 Geolocation API ===
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            initMap(lat, lon);
        },
        (error) => {
            console.warn("⚠️ Geolocation denied or failed. Using Troy, NY instead.");
            initMap(DEFAULT_COORDS.lat, DEFAULT_COORDS.lon);
        }
    );
} else {
    console.warn("⚠️ Geolocation not supported. Using Troy, NY instead.");
    initMap(DEFAULT_COORDS.lat, DEFAULT_COORDS.lon);
}

// === Fetches live weather data ===
function getWeather(lat, lon) {
    const url = "https://api.openweathermap.org/data/2.5/weather?lat=" +
        lat + "&lon=" + lon + "&units=metric&appid=" + OPENWEATHER_API_KEY;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const city = data.name || "Your Location";
            const temp = data.main.temp;
            const desc = data.weather[0].description;
            const humidity = data.main.humidity;
            const wind = data.wind.speed;
            const icon = data.weather[0].icon;

            let tempEmoji = "";
            let humidityEmoji = "";
            let windEmoji = "";

            // Temperature emojis
            if (temp <= 5) tempEmoji = "❄️🥶";
            else if (temp <= 15) tempEmoji = "🧥🍂";
            else if (temp <= 25) tempEmoji = "🌤️😊";
            else if (temp <= 32) tempEmoji = "☀️😎";
            else tempEmoji = "🔥🥵";

            // Humidity emojis
            if (humidity <= 30) humidityEmoji = "🌵💨";
            else if (humidity <= 60) humidityEmoji = "💧🌤️";
            else if (humidity <= 80) humidityEmoji = "💦☁️";
            else humidityEmoji = "🥵💦";

            // Wind emojis
            if (wind <= 1) windEmoji = "😌";
            else if (wind <= 5) windEmoji = "🍃🌬️";
            else if (wind <= 10) windEmoji = "💨🌪️";
            else windEmoji = "😳🌪️";

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

// === Fetches daylight info ===
function getDaylight(lat, lon) {
    const url = "https://api.sunrise-sunset.org/json?lat=" +
        lat + "&lng=" + lon + "&formatted=0";

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const sunrise = new Date(data.results.sunrise).toLocaleTimeString();
            const sunset = new Date(data.results.sunset).toLocaleTimeString();
            const totalSeconds = data.results.day_length;
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            const dayLength = `${hours}h ${minutes}m ${seconds}s`;

            daylightDiv.innerHTML = `
                <h2>Daylight Info</h2>
                <p>🌅 Sunrise: ${sunrise}</p>
                <p>🌇 Sunset: ${sunset}</p>
                <p>🕒 Day Length: ${dayLength}</p>
            `;
        })
        .catch(error => {
            daylightDiv.innerHTML = "⚠️ Unable to fetch daylight data.";
            console.error(error);
        });
}
