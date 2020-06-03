<?php
namespace Core;

use App\Model\Collection;
use App\Model\Ticket;
use Geocoder\Exception\Exception;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\LocationIQ\LocationIQ;
use Geocoder\Provider\OpenCage\OpenCage;

class Map {

    static $apiKeys = [
        GoogleMaps::class => "AIzaSyA1nLnzBbe66OjBbBSTZBtiAX0ZGDNse4I",
        LocationIQ::class => "300f2efa12ada7",
        OpenCage::class   => "87b03411e9d7452e876923a19500e61d"
    ];

    private $provider;

    function __construct() {
        $httpClient = \Http\Adapter\Guzzle6\Client::createWithConfig(['verify' => FALSE]);
        $this->provider = $this->getProvider($httpClient, OpenCage::class);
    }

    /**
     * @param \Http\Adapter\Guzzle6\Client $httpClient
     * @param $class
     * @return bool|\Geocoder\Provider\Provider
     */
    private function getProvider(\Http\Adapter\Guzzle6\Client $httpClient, $class) {
        if (!array_key_exists($class, self::$apiKeys)) {
            return FALSE;
        }
        return new $class($httpClient, self::$apiKeys[$class]);
    }

    /**
     * @param $latitude float
     * @param $longitude float
     * @return array|bool cap, city, street
     */
    function positionDetails($latitude, $longitude) {
        $geocoder = new \Geocoder\StatefulGeocoder($this->provider);
        try {
            if (!$result = $geocoder->reverse($latitude, $longitude)) {
                return FALSE;
            }
        } catch (Exception $ex) {
            logLn(now()->toDateTimeString() . " " . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
            return FALSE;
        }
        if (!$first = $result->first()) {
            return FALSE;
        }
        return [
            "cap"    => $first->getPostalCode(),
            "city"   => $first->getLocality(),
            "street" => $first->getStreetName()
        ];
    }


    /**
     * @param $lat1 float latitude 1
     * @param $lon1 float longitude 1
     * @param $lat2 float latitude 2
     * @param $lon2 float longitude 2
     * @return float|int distance in meters between two coordinates
     */
    static function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        return $dist * 60 * 1.853159616 * 1000;
    }

    /**
     * @param $lat1 float latitude 1
     * @param $lon1 float longitude 1
     * @param $lat2 float latitude 2
     * @param $lon2 float longitude 2
     * @return float|int distance in meters between two coordinates
     */
    function distanceHaversine($lat1, $lon1, $lat2, $lon2) {
        $R = 6371; // Radius of the earth in km
        $dLat = deg2rad($lat2 - $lat1);  // deg2rad below
        $dLon = deg2rad($lon2 - $lon1);
        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c * 1000;
    }

    /**
     * @param Collection $tickets
     * @return string img url
     */
    static function markersMap(Collection $tickets) {
        $url = "https://maps.locationiq.com/v2/staticmap?" . http_build_query([
                "key"  => self::$apiKeys[LocationIQ::class],
                "zoom" => 18
            ]);
        foreach ($tickets as $ticket) {
            $url .= "&markers=size:small|color:" . strtolower($ticket->priority) . "|"
                . $ticket->latitude . "," . $ticket->longitude;
        }
        return $url;
    }

}