## Overview
In this lab, I built a **Weather & Daylight Web App** that shows real-time data for the user’s **current location** using the **HTML5 Geolocation API**, **OpenWeatherMap**, and **Sunrise–Sunset** APIs.  

I originally planned to use the **Google Maps API**, but switched to **Leaflet.js** since it’s completely free and uses **OpenStreetMap** tiles. I wanted the app to look more dynamic than the example in the lab, so I added smooth sky animations, gradient cards, and emojis that change based on live weather conditions.

---------------------------------------------------------------------------------------------------------------

## How It Works
- **HTML** sets up the layout with a header, interactive Leaflet map, and two info cards (Weather & Daylight).  
- **JavaScript (Lab3.js)** fetches JSON data from:
  - **HTML5 Geolocation API** → detects the user’s current latitude and longitude.  
  - **OpenWeatherMap** → retrieves temperature, humidity, wind, and weather description.  
  - **Sunrise–Sunset** → provides sunrise, sunset, and total daylight duration.  
- Data is displayed dynamically, with emojis changing depending on the values (e.g., ❄️ for cold, 💦 for humidity, 🌪️ for strong wind).  
- **Leaflet.js** displays the map centered on the user’s current location (or defaults to Troy, NY if location is denied).  
- **CSS** handles animations, gradient cards, and a smooth background that shifts colors to mimic the sky throughout the day.

----------------------------------------------------------------------------------------------------------------

## Biggest Challenges
1. **Smooth Background Transition**  
   The background colors changed instantly at first. I learned to animate the **background-position** of a long gradient instead, which made the sky transition smoothly.  

2. **Adding Emojis**  
   I couldn’t type emojis directly on my laptop, so I had to look them up or ask ChatGPT for each one. It took a bit of testing to match the right emoji ranges for temperature, humidity, and wind.  

3. **API Choice**  
   I initially planned to use Google Maps but switched to **Leaflet.js** after realizing Google’s API required billing. Leaflet turned out to be a free, open-source alternative that fit perfectly.  

4. **Learning HTML5 Geolocation**  
   I had to learn how the **Geolocation API** works — handling location permissions, fallback behavior when denied, and linking coordinates to the weather and daylight APIs. It was tricky at first, but it made the project feel more personal and interactive.  

---------------------------------------------------------------------------------------------------------------

## API & Library References
- [OpenWeatherMap API](https://openweathermap.org/api) – Used to retrieve current weather data such as temperature, humidity, wind speed, and conditions.
- [Sunrise–Sunset API](https://sunrise-sunset.org/api) – Used to get sunrise, sunset, and total daylight duration based on location.
- [HTML5 Geolocation API](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation_API) – Used to detect the user’s current latitude and longitude in the browser.
- [Leaflet.js Library](https://leafletjs.com/) – Used to display the interactive map with OpenStreetMap tiles.