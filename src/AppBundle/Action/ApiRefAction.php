<?php

// src/AppBundle/Action/ApiRefAction.php

namespace AppBundle\Action;

use Symfony\Component\Serializer\Annotation\Groups;
use AppBundle\Entity\ApiRef;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Unirest;
use Easyrdf;
use ML\JsonLD\JsonLD as JsonLD;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiRefAction
{

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function validateData(string $data)
    {
        return $data;
    }
    protected function retrieveData(string $data)
    {
        return $data;
    }
/**
 * [retrieveClass description]
 * @param  \EasyRdf_Graph $graph [description]
 * @return [type]                [description]
 */
    protected function retrieveClass(\EasyRdf_Graph $graph)
    {
        $nodes = $graph->resources();
        $classes = array();
        foreach( $nodes as $value ) {
            if (!($value->isBnode())) {
                array_push($classes,$value->getUri());
            }
        }
        return $classes;
    }

    protected function getPrefix()
    {
        $prefix = '
        PREFIX schema: <http://schema.org/>
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        prefix hydra: <http://www.w3.org/ns/hydra/core#>';

        return $prefix;
    }

    protected function retrieveProperty(\EasyRdf_Graph $graph,string $class)
    {
        $q = $graph->allOfType($class);
        // $props = array();
        if (count($q)>1) {
            foreach( $q as $key => $value ) {
                $graph->addType($value,"dup:".$key);
                // array_push($props,$value->getUri());
            }
        }
        dump($q);
        $props = $graph->properties($q[0]->getUri());
        unset($props[0]);
        return $props; //rmeove rdf:type property
    }

    protected function searchQuery(\EasyRdf_Graph $graph,string $class)
    {
        $propList = $this->retrieveProperty($graph,$class);
        // $values = 'VALUES ?property { ';
        $values = '';
        foreach( $propList as $key => $value ) {
            $values .= '?prop'.$key . ' hydra:property ' . $value . " .\n          ";
        }
        // $values .= '}';
        dump($values);
        $prefix = $this->getPrefix();
        $query = $prefix .
        'SELECT DISTINCT ?class ?entrypoint
        FROM <http://localhost:8090/test1/data/apiv7>
        WHERE  {
          ?class rdf:type schema:test.
          ?server hydra:supportedClass ?class.
          ?server hydra:entrypoint ?entrypoint .
          ?class hydra:supportedProperty ?props
          ' . $values . '}';
        return $query;
    }

    protected function testHydra() {
        $prefix = $this->getPrefix();
        $query = $prefix .
        'DESCRIBE *
        FROM <http://localhost:8090/test1/data/apiv7>
        WHERE  { <http://localhost:8091/docs.jsonld> ?p ?o }';
        return $query;
    }

    protected function getBindings(string $class)
    {
        $query = 'prefix hydra: <http://www.w3.org/ns/hydra/core#>
            DESCRIBE ?subject
            FROM <http://localhost:8090/test1/data/test1>
            WHERE {
              ?subject hydra:supportedProperty ?object
            }
            LIMIT 10';
        return $data;
    }
    /**
     * @Route(
     *     name="api_ref_action",
     *     path="/api_ref/special",
     *     defaults={"_api_resource_class"=ApiRef::class, "_api_collection_operation_name"="special"}
     * )
     * @Method("PUT")
     */
    // public function __invoke($data) // API Platform retrieves the PHP entity using the data provider then (for POST and
    public function __invoke($data) // API Platform retrieves the PHP entity using the data provider then (for POST and
                                    // PUT method) deserializes user data in it. Then passes it to the action. Here $data
                                    // is an instance of Book having the given ID. By convention, the action's parameter
                                    // must be called $data.
    {
        $request = $this->getRequest()->getContent();;
        dump($request);

        $graph = new \EasyRdf_Graph();

        $test = $request;
        dump($test);
        $expanded = JsonLD::expand($test);
        $graph->parse($expanded,'jsonld',null);
        // dump($jsonld);
        dump($graph);
        // GRAPH retrieve
        $arr = $graph->resources();//epistrefei ola ta res 4
        dump($arr);
        $arr2 = $graph->allOfType("schema:test"); //epistrefei ton gid2
        dump($arr2);
        $props = $graph->properties($arr2[0]->getUri());
        dump($props);
        // dump($arr[0]->getUri());
        $tt = $this->retrieveClass($graph);
        $tt2 = $this->retrieveProperty($graph,"schema:test");
        // $tt3 = $this->searchQuery($graph,$tt[0]);
        dump($tt);
        dump($tt2);
        // dump($tt3);
        dump($graph);



        $graphOut = $graph->serialise('jsonld');
        $lal = $this->validateData($graphOut);
        dump($graphOut);

        $select = '
        SELECT ?desco ?subject
        FROM <http://localhost:8090/test1/data/flight-api>
        WHERE {
          schema:PostalAddress hydra:supportedProperty ?test .
          ?subject hydra:supportedClass schema:PostalAddress .
          ?test hydra:description ?desco .
        }';

        $sparql = new \EasyRdf_Sparql_Client('http://localhost:8090/test1/query');
        $query = 'prefix hydra: <http://www.w3.org/ns/hydra/core#>
            DESCRIBE ?subject
            FROM <http://localhost:8090/test1/data/test1>
            WHERE {
              ?subject hydra:supportedProperty ?object
            }
            LIMIT 10';
        $q = $sparql->query($this->testHydra());
        // $qout = $
        // $q = $sparql->query($query);
        dump($q);
        $out = $q->serialise('jsonld');
        // $out = $q->serialise('jsonld'); //
        // $result = $sparql->query(
        // 'SELECT * WHERE {'.
        // '  ?country rdf:type dbo:Country .'.
        // '  ?country rdfs:label ?label .'.
        // '  ?country dc:subject category:Member_states_of_the_United_Nations .'.
        // '  FILTER ( lang(?label) = "en" )'.
        // '} ORDER BY ?label'
        // );
        // $this->myService->doSomething($data);
        // $data = $request->getContent();

        // throw new \Exception('DUMPSTERRRRR!');


$testHydra = '{
  "@context": {
    "@vocab": "http://localhost:8091/docs.jsonld#",
    "@base": "http://localhost:8091",
    "hydra": "http://www.w3.org/ns/hydra/core#",
    "rdf": "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
    "rdfs": "http://www.w3.org/2000/01/rdf-schema#",
    "xmls": "http://www.w3.org/2001/XMLSchema#",
    "owl": "http://www.w3.org/2002/07/owl#",
    "domain": {
      "@id": "rdfs:domain",
      "@type": "@id"
    },
    "range": {
      "@id": "rdfs:range",
      "@type": "@id"
    },
    "subClassOf": {
      "@id": "rdfs:subClassOf",
      "@type": "@id"
    },
    "expects": {
      "@id": "hydra:expects",
      "@type": "@id"
    },
    "returns": {
      "@id": "hydra:returns",
      "@type": "@id"
    },
    "hydra:property": {
        "@type": "rdf:Property"
    }
  },
  "@id": "/docs.jsonld",
  "hydra:title": "API Platforms demo",
  "hydra:description": "This is a demo application of the [API Platform](https://api-platform.com) framework.\n[Its source code](https://github.com/api-platform/demo) includes various examples, check it out!\n",
  "hydra:entrypoint": "/",
  "hydra:supportedClass": [
    {
      "@id": "/things",
      "@type": "Thing",
      "jsonld_context": "test",
      "rdfs:label": "Thing",
      "hydra:title": "Thing",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Thing/name",
            "@type": "rdf:Property",
            "rdfs:label": "name",
            "domain": "http://schema.org/Thing",
            "range": "xmls:string"
          },
          "hydra:title": "name",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "Gets name."
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Thing/id",
            "@type": "rdf:Property",
            "rdfs:label": "id",
            "domain": "http://schema.org/Thing",
            "range": "xmls:integer"
          },
          "hydra:title": "id",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves Thing resource.",
          "rdfs:label": "Retrieves Thing resource.",
          "returns": "http://schema.org/Thing"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the Thing resource.",
          "rdfs:label": "Deletes the Thing resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "/places",
      "@type": "Place",
      "jsonld_context": "test",
      "rdfs:label": "Place",
      "hydra:title": "Place",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Place/name",
            "@type": "rdf:Property",
            "rdfs:label": "name",
            "domain": "http://schema.org/Place",
            "range": "xmls:string"
          },
          "hydra:title": "name",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "Gets name."
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Place/id",
            "@type": "rdf:Property",
            "rdfs:label": "id",
            "domain": "http://schema.org/Place",
            "range": "xmls:integer"
          },
          "hydra:title": "id",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves Place resource.",
          "rdfs:label": "Retrieves Place resource.",
          "returns": "http://schema.org/Place"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the Place resource.",
          "rdfs:label": "Deletes the Place resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "/civic_structures",
      "@type": "CivicStructure",
      "jsonld_context": "test",
      "rdfs:label": "CivicStructure",
      "hydra:title": "CivicStructure",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#CivicStructure/name",
            "@type": "rdf:Property",
            "rdfs:label": "name",
            "domain": "http://schema.org/CivicStructure",
            "range": "xmls:string"
          },
          "hydra:title": "name",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "Gets name."
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves CivicStructure resource.",
          "rdfs:label": "Retrieves CivicStructure resource.",
          "returns": "http://schema.org/CivicStructure"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "http://schema.org/CivicStructure",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the CivicStructure resource.",
          "rdfs:label": "Replaces the CivicStructure resource.",
          "returns": "http://schema.org/CivicStructure"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the CivicStructure resource.",
          "rdfs:label": "Deletes the CivicStructure resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "/postal_addresses",
      "@type": "http://schema.org/PostalAddress",
      "jsonld_context": "test",
      "rdfs:label": "PostalAddress",
      "hydra:title": "PostalAddress",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#PostalAddress/addressCountry",
            "@type": "rdf:Property",
            "rdfs:label": "addressCountry",
            "domain": "http://schema.org/PostalAddress",
            "range": "xmls:string"
          },
          "hydra:title": "addressCountry",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The country. For example, USA. You can also provide the two-letter [ISO 3166-1 alpha-2 country code](http://en.wikipedia.org/wiki/ISO-3166-1)"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#PostalAddress/addressLocality",
            "@type": "rdf:Property",
            "rdfs:label": "addressLocality",
            "domain": "http://schema.org/PostalAddress",
            "range": "xmls:string"
          },
          "hydra:title": "addressLocality",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The locality. For example, Mountain View"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#PostalAddress/addressRegion",
            "@type": "rdf:Property",
            "rdfs:label": "addressRegion",
            "domain": "http://schema.org/PostalAddress",
            "range": "xmls:string"
          },
          "hydra:title": "addressRegion",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The region. For example, CA"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#PostalAddress/postalCode",
            "@type": "rdf:Property",
            "rdfs:label": "postalCode",
            "domain": "http://schema.org/PostalAddress",
            "range": "xmls:string"
          },
          "hydra:title": "postalCode",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The postal code. For example, 94043"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#PostalAddress/streetAddress",
            "@type": "rdf:Property",
            "rdfs:label": "streetAddress",
            "domain": "http://schema.org/PostalAddress",
            "range": "xmls:string"
          },
          "hydra:title": "streetAddress",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The street address. For example, 1600 Amphitheatre Pkwy"
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves PostalAddress resource.",
          "rdfs:label": "Retrieves PostalAddress resource.",
          "returns": "http://schema.org/PostalAddress"
        },
        {
          "@type": "hydra:Operation",
          "expects": "hydra:IriTemplate",
          "hydra:template": "/geo_coordinates{?longitude,longitude[],latitude,latitude[],address,address[]}",
          "hydra:variableRepresentation": "BasicRepresentation",
          "hydra:mapping": [
          {
            "@type": "IriTemplateMapping",
            "variable": "longitude",
            "property": "longitude",
            "required": false
          },
          {
            "@type": "IriTemplateMapping",
            "variable": "longitude[]",
            "property": "longitude",
            "required": false
          },
          {
            "@type": "IriTemplateMapping",
            "variable": "latitude",
            "property": "latitude",
            "required": false
          },
          {
            "@type": "IriTemplateMapping",
            "variable": "latitude[]",
            "property": "latitude",
            "required": false
          },
          {
            "@type": "IriTemplateMapping",
            "variable": "address",
            "property": "address",
            "required": false
          },
          {
            "@type": "IriTemplateMapping",
            "variable": "address[]",
            "property": "address",
            "required": false
          }],
          "hydra:method": "GET",
          "hydra:title": "Retrieves PostalAddress resource.",
          "rdfs:label": "Retrieves PostalAddress resource.",
          "returns": "http://schema.org/PostalAddress"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "http://schema.org/PostalAddress",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the PostalAddress resource.",
          "rdfs:label": "Replaces the PostalAddress resource.",
          "returns": "http://schema.org/PostalAddress"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the PostalAddress resource.",
          "rdfs:label": "Deletes the PostalAddress resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "/geo_coordinates",
      "@type": "http://schema.org/GeoCoordinates",
      "jsonld_context": "test",
      "rdfs:label": "GeoCoordinates",
      "hydra:title": "GeoCoordinates",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "http://schema.org/address",
            "@type": "hydra:Link",
            "rdfs:label": "address",
            "domain": "http://schema.org/GeoCoordinates",
            "range": "http://schema.org/PostalAddress"
          },
          "hydra:title": "address",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "Physical address of the item"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "http://schema.org/latitude",
            "@type": "rdf:Property",
            "rdfs:label": "latitude",
            "domain": "http://schema.org/GeoCoordinates",
            "range": "xmls:decimal"
          },
          "hydra:title": "latitude",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The latitude of a location. For example ```37.42242``` ([WGS 84](https://en.wikipedia.org/wiki/World_Geodetic_System))"
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "http://schema.org/longitude",
            "@type": "http://schema.org/longitude",
            "rdfs:label": "longitude",
            "domain": "http://schema.org/GeoCoordinates",
            "range": "xmls:decimal"
          },
          "hydra:title": "longitude",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The longitude of a location. For example ```-122.08585``` ([WGS 84](https://en.wikipedia.org/wiki/World_Geodetic_System))"
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves GeoCoordinates resource.",
          "rdfs:label": "Retrieves GeoCoordinates resource.",
          "returns": "http://schema.org/GeoCoordinates"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "http://schema.org/GeoCoordinates",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the GeoCoordinates resource.",
          "rdfs:label": "Replaces the GeoCoordinates resource.",
          "returns": "http://schema.org/GeoCoordinates"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the GeoCoordinates resource.",
          "rdfs:label": "Deletes the GeoCoordinates resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "/api_refs",
      "@type": "test",
      "jsonld_context": "test",
      "rdfs:label": "ApiRef",
      "hydra:title": "ApiRef",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "http://schema.org/name",
            "@type": "rdf:Property",
            "rdfs:label": "articleBody",
            "domain": "/apiRef"
          },
          "hydra:title": "articleBody",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves ApiRef resource.",
          "rdfs:label": "Retrieves ApiRef resource.",
          "returns": "/apiRef"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "/apiRef",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the ApiRef resource.",
          "rdfs:label": "Replaces the ApiRef resource.",
          "returns": "/apiRef"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the ApiRef resource.",
          "rdfs:label": "Deletes the ApiRef resource.",
          "returns": "owl:Nothing"
        }
      ],
      "hydra:description": "A book."
    },
    {
      "@id": "/rdf_graphs",
      "@type": "hydra:Class",
      "jsonld_context": "test",
      "rdfs:label": "RdfGraph",
      "hydra:title": "RdfGraph",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#RdfGraph/articleBody",
            "@type": "rdf:Property",
            "rdfs:label": "articleBody",
            "domain": "#RdfGraph"
          },
          "hydra:title": "articleBody",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The description of this api."
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves RdfGraph resource.",
          "rdfs:label": "Retrieves RdfGraph resource.",
          "returns": "#RdfGraph"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "#RdfGraph",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the RdfGraph resource.",
          "rdfs:label": "Replaces the RdfGraph resource.",
          "returns": "#RdfGraph"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the RdfGraph resource.",
          "rdfs:label": "Deletes the RdfGraph resource.",
          "returns": "owl:Nothing"
        }
      ],
      "hydra:description": "A book."
    },
    {
      "@id": "/a_p_i_references",
      "@type": "hydra:Class",
      "jsonld_context": "test",
      "rdfs:label": "APIReference",
      "hydra:title": "APIReference",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#APIReference/articleBody",
            "@type": "rdf:Property",
            "rdfs:label": "articleBody",
            "domain": "#APIReference",
            "range": "xmls:string"
          },
          "hydra:title": "articleBody",
          "hydra:required": false,
          "hydra:readable": true,
          "hydra:writable": true,
          "hydra:description": "The description of this api."
        }
      ],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves APIReference resource.",
          "rdfs:label": "Retrieves APIReference resource.",
          "returns": "#APIReference"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "#APIReference",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the APIReference resource.",
          "rdfs:label": "Replaces the APIReference resource.",
          "returns": "#APIReference"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the APIReference resource.",
          "rdfs:label": "Deletes the APIReference resource.",
          "returns": "owl:Nothing"
        }
      ],
      "hydra:description": "A book."
    },
    {
      "@id": "/intangibles",
      "@type": "hydra:Class",
      "jsonld_context": "test",
      "rdfs:label": "Intangible",
      "hydra:title": "Intangible",
      "hydra:supportedProperty": [],
      "hydra:supportedOperation": [
        {
          "@type": "hydra:Operation",
          "hydra:method": "GET",
          "hydra:title": "Retrieves Intangible resource.",
          "rdfs:label": "Retrieves Intangible resource.",
          "returns": "http://schema.org/Intangible"
        },
        {
          "@type": "hydra:ReplaceResourceOperation",
          "expects": "http://schema.org/Intangible",
          "hydra:method": "PUT",
          "hydra:title": "Replaces the Intangible resource.",
          "rdfs:label": "Replaces the Intangible resource.",
          "returns": "http://schema.org/Intangible"
        },
        {
          "@type": "hydra:Operation",
          "hydra:method": "DELETE",
          "hydra:title": "Deletes the Intangible resource.",
          "rdfs:label": "Deletes the Intangible resource.",
          "returns": "owl:Nothing"
        }
      ]
    },
    {
      "@id": "#Entrypoint",
      "@type": "hydra:Class",
      "hydra:title": "The API entrypoint",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/thing",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of Thing resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of Thing resources.",
                "rdfs:label": "Retrieves the collection of Thing resources.",
                "returns": "hydra:PagedCollection"
              }
            ]
          },
          "hydra:title": "The collection of Thing resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/place",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of Place resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of Place resources.",
                "rdfs:label": "Retrieves the collection of Place resources.",
                "returns": "hydra:PagedCollection"
              }
            ]
          },
          "hydra:title": "The collection of Place resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/civicStructure",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of CivicStructure resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of CivicStructure resources.",
                "rdfs:label": "Retrieves the collection of CivicStructure resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "http://schema.org/CivicStructure",
                "hydra:method": "POST",
                "hydra:title": "Creates a CivicStructure resource.",
                "rdfs:label": "Creates a CivicStructure resource.",
                "returns": "http://schema.org/CivicStructure"
              }
            ]
          },
          "hydra:title": "The collection of CivicStructure resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/postalAddress",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of PostalAddress resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of PostalAddress resources.",
                "rdfs:label": "Retrieves the collection of PostalAddress resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "http://schema.org/PostalAddress",
                "hydra:method": "POST",
                "hydra:title": "Creates a PostalAddress resource.",
                "rdfs:label": "Creates a PostalAddress resource.",
                "returns": "http://schema.org/PostalAddress"
              }
            ]
          },
          "hydra:title": "The collection of PostalAddress resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/geoCoordinates",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of GeoCoordinates resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of GeoCoordinates resources.",
                "rdfs:label": "Retrieves the collection of GeoCoordinates resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "http://schema.org/GeoCoordinates",
                "hydra:method": "POST",
                "hydra:title": "Creates a GeoCoordinates resource.",
                "rdfs:label": "Creates a GeoCoordinates resource.",
                "returns": "http://schema.org/GeoCoordinates"
              }
            ]
          },
          "hydra:title": "The collection of GeoCoordinates resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/apiRef",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of ApiRef resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:ReplaceResourceOperation",
                "expects": "/apiRef",
                "foo": "bar",
                "hydra:method": "PUT",
                "hydra:title": "Replaces the ApiRef resource.",
                "rdfs:label": "Replaces the ApiRef resource.",
                "returns": "/apiRef"
              },
              {
                "@type": "hydra:Operation",
                "expects": "hydra:template",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of ApiRef resources.",
                "rdfs:label": "Retrieves the collection of ApiRef resources.",
                "returns": "hydra:PagedCollection"
              }
            ]
          },
          "hydra:title": "The collection of ApiRef resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/rdfGraph",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of RdfGraph resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of RdfGraph resources.",
                "rdfs:label": "Retrieves the collection of RdfGraph resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "#RdfGraph",
                "hydra:method": "POST",
                "hydra:title": "Creates a RdfGraph resource.",
                "rdfs:label": "Creates a RdfGraph resource.",
                "returns": "#RdfGraph"
              }
            ]
          },
          "hydra:title": "The collection of RdfGraph resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/aPIReference",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of APIReference resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of APIReference resources.",
                "rdfs:label": "Retrieves the collection of APIReference resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "#APIReference",
                "hydra:method": "POST",
                "hydra:title": "Creates a APIReference resource.",
                "rdfs:label": "Creates a APIReference resource.",
                "returns": "#APIReference"
              }
            ]
          },
          "hydra:title": "The collection of APIReference resources",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#Entrypoint/intangible",
            "@type": "hydra:Link",
            "domain": "#Entrypoint",
            "rdfs:label": "The collection of Intangible resources",
            "range": "hydra:PagedCollection",
            "hydra:supportedOperation": [
              {
                "@type": "hydra:Operation",
                "hydra:method": "GET",
                "hydra:title": "Retrieves the collection of Intangible resources.",
                "rdfs:label": "Retrieves the collection of Intangible resources.",
                "returns": "hydra:PagedCollection"
              },
              {
                "@type": "hydra:CreateResourceOperation",
                "expects": "http://schema.org/Intangible",
                "hydra:method": "POST",
                "hydra:title": "Creates a Intangible resource.",
                "rdfs:label": "Creates a Intangible resource.",
                "returns": "http://schema.org/Intangible"
              }
            ]
          },
          "hydra:title": "The collection of Intangible resources",
          "hydra:readable": true,
          "hydra:writable": false
        }
      ],
      "hydra:supportedOperation": {
        "@type": "hydra:Operation",
        "hydra:method": "GET",
        "rdfs:label": "The API entrypoint.",
        "returns": "#EntryPoint"
      }
    },
    {
      "@id": "#ConstraintViolation",
      "@type": "hydra:Class",
      "hydra:title": "A constraint violation",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#ConstraintViolation/propertyPath",
            "@type": "rdf:Property",
            "rdfs:label": "propertyPath",
            "domain": "#ConstraintViolation",
            "range": "xmls:string"
          },
          "hydra:title": "propertyPath",
          "hydra:description": "The property path of the violation",
          "hydra:readable": true,
          "hydra:writable": false
        },
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#ConstraintViolation/message",
            "@type": "rdf:Property",
            "rdfs:label": "message",
            "domain": "#ConstraintViolation",
            "range": "xmls:string"
          },
          "hydra:title": "message",
          "hydra:description": "The message associated with the violation",
          "hydra:readable": true,
          "hydra:writable": false
        }
      ]
    },
    {
      "@id": "#ConstraintViolationList",
      "@type": "hydra:Class",
      "subClassOf": "hydra:Error",
      "hydra:title": "A constraint violation list",
      "hydra:supportedProperty": [
        {
          "@type": "hydra:SupportedProperty",
          "hydra:property": {
            "@id": "#ConstraintViolationList/violations",
            "@type": "rdf:Property",
            "rdfs:label": "violations",
            "domain": "#ConstraintViolationList",
            "range": "#ConstraintViolation"
          },
          "hydra:title": "violations",
          "hydra:description": "The violations",
          "hydra:readable": true,
          "hydra:writable": false
        }
      ]
    }
  ]
}';
        return new Response($testHydra); // API Platform will automatically validate, persist (if you use Doctrine) and serialize an entity
                      // for you. If you prefer to do it yourself, return an instance of Symfony\Component\HttpFoundation\Response
    }
}