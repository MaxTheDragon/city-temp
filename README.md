# City Temperature
Monitors and displays the temperature in a city of choice. Assignment for sandwave.io.

## Features
- Tracks and registers the temperature of one city of choice via two API's (such as OpenWeatherMap).
- Polls the API at configurable time intervals.
- Stores this info in a simple database.
- Displays this info to a user via a web interface.

## Usage
- Install like any other Symfony project.
- Create an env.local file in your local copy's root folder and override the database settings, as well as the API key values for the OpenWeather and WeatherAPI services.
- Add a new city by requesting it in the browser (like http://localhost/Amsterdam if localhost is your installation's domain).
- Once it's added, the `app:update-temps` Symfony console command will keep the temperature data up-to-date in your database by running it periodically as a cron job.
