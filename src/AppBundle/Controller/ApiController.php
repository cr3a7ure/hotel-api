<?php

// src/AppBundle/Controller/ApiController.php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiRef;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
      /**
     * @Route("/api_ref/poster")
     * @Method("POST")
     */
    public function specialAction(Request $request)
    {
        // dump($data);
        $test = new ApiRef();
        $postal->setId(10);
        $postal->setArticleBody("dasdasdasd");
        return new Response('Let\'s do this!');
    }
}