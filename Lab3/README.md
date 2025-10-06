## Overview
In this lab, I built a **Weather & Daylight Web App** that shows real-time data for **Troy, NY** using the **OpenWeatherMap** and **Sunrise–Sunset** APIs.  

I originally planned to use the **Google Maps API**, but switched to **Leaflet.js** since it’s completely free and uses **OpenStreetMap** tiles. I wanted the app to look more dynamic than the example in the lab, so I added smooth sky animations, gradient cards, and emojis that change based on weather conditions.

---------------------------------------------------------------------------------------------------------------

## How It Works
- **HTML** sets up the layout with a header, Leaflet map, and two info cards (Weather & Daylight).  
- **JavaScript (Lab3.js)** fetches JSON data from:
  - **OpenWeatherMap** → temperature, humidity, wind, and weather description.  
  - **Sunrise–Sunset** → sunrise, sunset, and day length.  
- Data is displayed dynamically, with emojis changing depending on the values (e.g., ❄️ for cold, 💦 for humidity, 🌪️ for strong wind).  
- **Leaflet.js** displays the map centered on Troy, NY with a marker.  
- **CSS** handles animations, card design, and the background gradient that cycles smoothly to mimic the sky during different times of day.

----------------------------------------------------------------------------------------------------------------

## Biggest Challenges
1. **Smooth Background Transition**  
   The background colors changed instantly at first. I learned to animate the **background-position** of one long gradient instead, creating a smooth sky flow.

2. **Adding Emojis**  
   I couldn’t type emojis directly on my laptop, so I had to look them up or ask ChatGPT for each one. Matching them to data ranges took some testing.

3. **API Choice**  
   I planned to use Google Maps but switched to **Leaflet.js** after realizing Google’s API required billing. Leaflet was free, simpler, and fit the lab perfectly.
