<?php

namespace Acme\StoreBundle\Controller;

use Acme\StoreBundle\Document\RedisTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {

        $redisTest = new RedisTest();
        $redisTest->findMostPopular(5);
//        var_dump($redisTest);

        return $this->render('AcmeStoreBundle:Default:index.html.twig');
    }
}
