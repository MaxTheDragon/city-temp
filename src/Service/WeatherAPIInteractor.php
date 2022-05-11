<?php
/**
 * City Temperature System
 */
namespace App\Service;

use App\Entity\City;
use App\Entity\Temperature;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * This class handles all interaction with OpenWeather and WeatherAPI.
 *
 * @package   App\Service
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2022 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class WeatherAPIInteractor
{
    /**
     * The API URL for the OpenWeather service.
     */
    public const API_URL_OPEN_WEATHER = 'https://api.openweathermap.org/data/2.5/weather?q=%s&appid=%s';
    
    /**
     * The API URL for the WeatherAPI service.
     */
    public const API_URL_WEATHER_API = 'http://api.weatherapi.com/v1/current.json?aqi=no&q=%s&key=%s';
    
    /**
     * Symfony HTTP client.
     */
    private $client;
    
    /**
     * The API key for the OpenWeather API service.
     */
    private $api1Key;
    
    /**
     * The API key for the WeatherAPI API service.
     */
    private $api2Key;

    /**
     * Creates a new WeatherAPIInteractor object.
     * 
     * @param HttpClientInterface $client The Symfony HTTP client to assign to this WeatherAPIInteractor object.
     * @param string $api1Key The API key for the OpenWeather API service.
     * @param string $api2Key The API key for the WeatherAPI API service.
     */
    public function __construct(HttpClientInterface $client, string $api1Key, string $api2Key)
    {
        $this->client = $client;
        $this->api1Key = $api1Key;
        $this->api2Key = $api2Key;
    }
    
    /**
     * Creates a new Temperature entity object and fills it with current temperature data requested from the weather API's.
     *
     * @param City The City entity for which to create a new Temperature entity object.
     * @return Temperature The new Temperature entity object.
     */
    public function requestTemperatureForCity(City $city): ?Temperature
    {
        // Initialize temperature values.
        $api1Value = 0.0;
        $api2Value = 0.0;
        
        // Request data from the OpenWeather API service.
        $response = $this->client->request('GET', sprintf(self::API_URL_OPEN_WEATHER, $city->getName(), $this->api1Key));

        // If the response is OK, process its dataset.
        if ($response->getStatusCode() === Response::HTTP_OK)
        {
            // Convert dataset to array.
            $data = $response->toArray();
            
            // If dataset contains the current temperature, convert from Kelvin to Celsius and store temperature value in api1Value.
            if (isset($data['main']['temp']))
                $api1Value = $data['main']['temp'] - 273.15;
        }
        else
            // Return null if this city doesn't exist (geographically) or some other error occured.
            return null;
        
        // Request data from the WeatherAPI API service.
        $response = $this->client->request('GET', sprintf(self::API_URL_WEATHER_API, $city->getName(), $this->api2Key));
        
        // If the response is OK, process its dataset.
        if ($response->getStatusCode() === Response::HTTP_OK)
        {
            // Convert dataset to array.
            $data = $response->toArray();
            
            // If dataset contains the current temperature, store temperature value in api2Value.
            if (isset($data['current']['temp_c']))
                $api2Value = $data['current']['temp_c'];
        }
        else
            // Return null if this city doesn't exist (geographically) or some other error occured.
            return null;
        
        // Create and return the new Temperature entity object with the requested data.
        return new Temperature($city, $api1Value, $api2Value);
    }
}
