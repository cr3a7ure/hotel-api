<?php

// src/AppBundle/DataProvider/GeoCoordinatesCollectionDataProvider.php

namespace AppBundle\DataProvider;

use AppBundle\Entity\GeoCoordinates;
use AppBundle\Entity\PostalAddress;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use Unirest;
// use Mashape\UnirestPhp\Unirest;

final class GeoCoordinatesCollectionDataProvider implements CollectionDataProviderInterface
{
  protected $requestStack;

  public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        if (GeoCoordinates::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }
        //Temp Objects
        $postal = new PostalAddress();
        $postal->setId(1);
        $postal->setAddressCountry("GR");
        $postal->setAddressLocality("jkhkjh");
        $postal->setAddressRegion("kjhkj");
        $postal->setPostalCode("82100");
        $postal->setStreetAddress('sdf');

        $geoC = new GeoCoordinates();
        $geoC->setId(100);
        $geoC->setAddress($postal);
        $geoC->setLatitude(null);
        $geoC->setLongitude(null);

        $gmapsApiKey = 'AIzaSyDzVG8kyjhNB-y8wpRk_6KCl-DtQFPsTQw';

        $request = $this->requestStack->getCurrentRequest();
        // $test = $request->query;//->get('');
        $props = $request->query->all();
        $propKeys = array_keys($props);
        // dump($propKeys[1]);
        // dump($props[$propKeys[1]]);
        $headers = array('Accept' => 'application/json');
        $query = array();
        $query['key'] = $gmapsApiKey;

        if ((isset($props['latitude'])) && (isset($props['latitude']))) {
            $query['latlng'] = $props['latitude'].",". $props['longitude'];
            $response = Unirest\Request::get('https://maps.googleapis.com/maps/api/geocode/json',$headers,$query);
            $responseResults = $response->body->results;
            $postal->setAddressCountry("GR");
            $postal->setAddressLocality("jkhkjh");
            $postal->setAddressRegion("kjhkj");
            $postal->setPostalCode("82100");
            $postal->setStreetAddress('sdf');
            $geoC->setLatitude($props['latitude']);
            $geoC->setLongitude($props['longitude']);
        } else if(isset($props['address'])) {
            $query['address'] = $props['address'];
            $response = Unirest\Request::get('https://maps.googleapis.com/maps/api/geocode/json',$headers,$query);

            $responseResults = $response->body->results;
            $geoC->setLatitude($responseResults[0]->geometry->location->lat);
            $geoC->setLongitude($responseResults[0]->geometry->location->lng);
            $postal->setId(666);
            $postal->setAddressCountry($responseResults[0]->address_components[5]->short_name);
            $postal->setAddressLocality($responseResults[0]->address_components[4]->long_name);
            $postal->setAddressRegion($responseResults[0]->address_components[4]->short_name);
            $postal->setPostalCode($responseResults[0]->address_components[6]->long_name);
            // $comma_separated = implode(", ", array_slice($$responseResults[0]->address_components[0]->long_name, 0, 3));
            $street = 'test';
            $street = $responseResults[0]->address_components[0]->long_name.", ".$responseResults[0]->address_components[1]->long_name .", ".$responseResults[0]->address_components[2]->long_name.", ".$responseResults[0]->address_components[3]->long_name;
            $postal->setStreetAddress($street);

        } else {
            return [$geoC];
        }

        // $response = Unirest\Request::get('https://maps.googleapis.com/maps/api/geocode/json',$headers,$query);
        // $responseResults = $response->body->results;
        // dump($responseResults[0]->formatted_address);

//"CV-110, 12317 Herbés, Castellón, Spain"

// $response = Unirest\Request::post('http://mockbin.com/request', $headers, $query);

//       $response->code;        // HTTP Status code
//       $response->headers;     // Headers
//       $response->body;        // Parsed body
//       $response->raw_body;    // Unparsed body

// formatted_address
// Display the result
// dump();
// dump($response->body);
// $array = array(
// "title" => "Harry Potter and the Prisoner of Azkaban",
// "author" => "J. K. Rowling",
// "publisher" => "Arthur A. Levine Books",
// "amazon_link" => "http://www.amazon.com/dp/0439136369/"
// );

// $postal = new PostalAddress();
// $postal->setId(2);

// $postal = "\1";
// $postal->getId(1);
// $books = (object) $array;

// new GeoCoordinates(1) = (object)array(
//   'id' => 1,
//   'address' =>"skataa",
//   'latitude' => 40.714224,
//   'longitude' => -73.961452
//   )



        // var_dump(key($troll[0])); //get the 
        // dump($response);
        // dump($postal);
        // if ($props!=null) {
        //     return null;
        // } else {
            return [$geoC];
        // }
    }
}