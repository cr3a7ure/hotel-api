<?php

// src/AppBundle/DataProvider/ApiCollectionDataProvider.php

namespace AppBundle\DataProvider;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use AppBundle\Entity\APIReference;
use Unirest;
// use Mashape\UnirestPhp\Unirest;

final class ApiCollectionDataProvider implements CollectionDataProviderInterface
{
  protected $requestStack;

  public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        // $test = new APIReference();
        // $test->setArticleBody("dasdasdasd");
        return new Response(22);
        // return $test;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return 101;
        // }
    }
//den douleuei! giati mln exei na kanei mono me read!!!!!!!!!!
    public function postCollection(string $resourceClass, string $operationName = 'POST')
    {
        // $test = new APIReference();
        // $test->setArticleBody("dasdasdasd");
        return new Response(22);
        // return $test;
    }
}