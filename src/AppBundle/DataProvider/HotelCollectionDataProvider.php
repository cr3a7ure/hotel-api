<?php

// src/AppBundle/DataProvider/HotelCollectionDataProvider.php

namespace AppBundle\DataProvider;

use AppBundle\Entity\Hotel;
use AppBundle\Entity\GeoCoordinates;
use AppBundle\Entity\Offer;
use AppBundle\Entity\PostalAddress;
use AppBundle\Entity\Rating;
use AppBundle\Entity\PriceSpecification;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use \DateTime;
use \DateInterval;
use Unirest;

final class HotelCollectionDataProvider implements CollectionDataProviderInterface
{
  protected $requestStack;
  protected $managerRegistry;
  // protected $objectManager;

  public function __construct(RequestStack $requestStack,ManagerRegistry $managerRegistry)
    {
        $this->requestStack = $requestStack;
        $this->managerRegistry = $managerRegistry;
        // $this->objectManager = $objectManager;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        if (Hotel::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        // Get parameters
        $request = $this->requestStack->getCurrentRequest();
        $searchParametersObj = $request->query->all();
        $searchParametersKeys = array_keys($searchParametersObj);
        // dump($request);
        dump($searchParametersObj);
        // dump($searchParametersKeys);
        $searchQuery = [];
        $variable = '';
        foreach ($searchParametersObj as $key => $value) {
            if(is_array($value)){
                // foreach ($value as $i => $arrayValue) {
                //     $chainPropsKey = explode("_", $key);
                //     $propertyKey = end($chainPropsKey);
                //     dump($propertyKey);
                //     $searchQuery[$propertyKey] = $value;
                //     dump($searchQuery[$propertyKey]);
                // }
            } else {
                $chainPropsKey = explode("_", $key);
                $propertyKey = end($chainPropsKey);
                // dump($propertyKey);
                $searchQuery[$propertyKey] = $value;
                // dump($searchQuery[$propertyKey]);
            }
        }
        // Keyword in Airport Name, City or Code.
        // $request = $this->requestStack->getCurrentRequest();
        // // $test = $request->query;//->get('');
        // $props = $request->query->all();
        // $propKeys = array_keys($props);
        // // dump($propKeys[1]);
        // // dump($props[$propKeys[1]]);
        // $variable = 'Chios';
        // $url = 'https://api.sandbox.amadeus.com/v1.2/hotels/search-airport';
        $now = new  DateTime();
        $interval = new DateInterval('P4D');
        $now = $now->add($interval);
        $url = 'https://api.sandbox.amadeus.com/v1.2/hotels/search-circle';
        $headers = array('Accept' => 'application/json');
        $query = array();
        $query['apikey'] = 'ZRjgUbT6jlJZlEvY86DrhyOrXAGzvANA';
        // $query['location'] = 'BOS';
        $query['latitude'] = array_key_exists('latitude',$searchQuery) ? $searchQuery['latitude'] : 36.0857;
        $query['longitude'] =  array_key_exists('longitude',$searchQuery) ? $searchQuery['longitude'] : -115.1541 ;
        $query['radius'] = '50';
        $query['check_in'] = $now->format('Y-m-d');
        $interval = new DateInterval('P1W');
        $next_week = $now->add($interval);
        $query['check_out'] = $next_week->format('Y-m-d');
        // dump($query);
        $response = Unirest\Request::get($url,$headers,$query);
        // dump($response);
        $hotels = array();
        $geolocs = array();
        $addresses = array();
        $prices = array();
        $ratings = array();
        $data = $response->body->results; //array
        foreach ($data as $key => $value) {
            $hotels[$key] = new Hotel();
            $hotels[$key]->setId($key);
            $hotels[$key]->setName($value->property_name);
            if( property_exists($value,'marketing_text')) {
                $hotels[$key]->setDescription($value->marketing_text);
            }
            $adresses[$key] = new PostalAddress();
            if (property_exists($value,'address')) {
                $adresses[$key]->setId($key);
                $adresses[$key]->setAddressCountry($value->address->country);
                $adresses[$key]->setAddressLocality($value->address->city);
                if (property_exists($value->address,'region')) {
                    $adresses[$key]->setAddressRegion($value->address->region);
                }
                if (property_exists($value->address,'postal_code')) {
                    $adresses[$key]->setPostalCode($value->address->postal_code);
                }
                if (property_exists($value->address,'line1')) {
                    $adresses[$key]->setStreetAddress($value->address->line1);
                }
            }

            $geolocs[$key] = new GeoCoordinates();
            $geolocs[$key]->setId(($key));
            $geolocs[$key]->setLatitude($value->location->latitude);
            $geolocs[$key]->setLongitude($value->location->longitude);

            $prices[$key] = new PriceSpecification();
            $prices[$key]->setId($key);
            $prices[$key]->setPriceCurrency($value->min_daily_rate->currency);
            $prices[$key]->setMinPrice($value->min_daily_rate->amount);
            $prices[$key]->setMaxPrice($value->total_price->amount);
            if(!empty($value->awards[0])) {
                $ratings[$key] = new Rating();
                $ratings[$key]->setId($key);
                $ratings[$key]->setAuthor($value->awards[0]->provider);
                $ratings[$key]->setBestRating(5.0);
                $ratings[$key]->setWorstRating(1.0);
                $ratings[$key]->setRatingValue($value->awards[0]->rating);
                $hotels[$key]->setStarRating($ratings[$key]);
            } else {
                $hotels[$key]->setStarRating(null);
            }

            $hotels[$key]->setAddress($adresses[$key]);
            $hotels[$key]->setFaxNumber($value->contacts[0]->detail);
            $hotels[$key]->setTelephone($value->contacts[0]->detail);
            $hotels[$key]->setMakesOffer(null);
            $hotels[$key]->setPriceRange($prices[$key]);
            $hotels[$key]->setAward("Check rating");
            $hotels[$key]->setGeo($geolocs[$key]);
            // $hotels[$key]->setGeo(null);
        }
        // $em = $this->managerRegistry->getManagerForClass('AppBundle\Entity\Airport');
        // // $emOrm = ObjectManager::getDoctrine();
        // $em->persist($airport);
        // $em->flush();
        return $hotels;
    }
}