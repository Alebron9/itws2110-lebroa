// script.js

// OpenWeatherMap API key provided by user
const WEATHER_API_KEY = '9149f1272cc3234e8d8db7652eabbb68';
const WEATHER_URL = `https://api.openweathermap.org/data/2.5/weather?q=Troy,US&appid=${WEATHER_API_KEY}&units=metric`;

const NASA_API_URL = 'https://api.nasa.gov/planetary/apod?api_key=DEMO_KEY'; // Demo key works for testing

$(document).ready(function() {
  // Fetch weather data
  $.ajax({
    url: WEATHER_URL,
    method: 'GET',
    success: function(data) {
      const weatherHTML = `
        <h4>${data.name}, ${data.sys.country}</h4>
        <p><strong>Temperature:</strong> ${data.main.temp} °C</p>
        <p><strong>Condition:</strong> ${data.weather[0].description}</p>
        <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png" alt="Weather icon">
      `;
      $('#weather').html(weatherHTML);
    },
    error: function(err) {
      $('#weather').html('<p>Error loading weather data.</p>');
    }
  });

  // Fetch NASA data
  $.ajax({
    url: NASA_API_URL,
    method: 'GET',
    success: function(data) {
      const nasaHTML = `
        <h4>${data.title}</h4>
        <p>${data.explanation}</p>
        <img src="${data.url}" alt="NASA Astronomy Picture of the Day" />
      `;
      $('#nasa').html(nasaHTML);
    },
    error: function(err) {
      $('#nasa').html('<p>Error loading NASA data.</p>');
    }
  });
});