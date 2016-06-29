<?php

namespace AppBundle\Controller;

use Acme\StoreBundle\Document\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class LuckyController
 * @package AppBundle\Controller
 */
class LuckyController extends Controller
{

    /**
     * @return Response
     */
    public function numberAction()
    {
//        var_dump('test'); exit;
        $number = rand(0, 100);

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }

    /**
     * @Route("/api/lucky/number")
     */
    public function apiNumberAction()
    {
        $data = array(
            'lucky_number' => rand(0, 100),
        );

        return new Response(
            json_encode($data),
            200,
            array('Content-Type' => 'application/json')
        );
    }

    /**
     * @Route("/lucky/number/{count}")
     * @param $count
     *
     * @return Response
     * @internal param int $numbers
     *
     */
    public function number2Action($count)
    {

        $product = new Product();
        $product->setName('A foo bar');
        $product->setPrice('19.99');

//        $em = $this->getDoctrine()->getManager();
//        $em->persist($product);
//        $em->flush();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();

        var_dump($product->getId());

        $repository = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('AcmeStoreBundle:Product');

        $product = $repository->find('5773a74495054707008b4576');
        echo '<pre>'; var_dump($product); echo '</pre>';

//        $id = '5773a74495054707008b4576';
//        $product2 = $this->get('doctrine_mongodb')
//            ->getRepository('AcmeStoreBundle:Product')
//            ->find($id);
//
//        if (!$product2) {
//            throw $this->createNotFoundException('No product found for id '.$id);
//        }
        /*****************/

        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }

        $numbersList = implode(',', $numbers);

        return $this->render(
            'lucky/number.html.twig',
            array('luckyNumberList' => $numbersList)
        );

        return new Response($html);
    }
}