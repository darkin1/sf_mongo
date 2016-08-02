<?php

namespace Acme\StoreBundle\Controller;

use Acme\StoreBundle\Document\Comment;
use Acme\StoreBundle\Document\Post;
use Acme\StoreBundle\Document\Product;

use Acme\StoreBundle\Document\RedisTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\AbstractQuery;

use Snc\RedisBundle\Doctrine\Cache\RedisCache;
use Predis\Client;
//use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {


//        $product = new Product();
//        $product->setName('A foo bar');
//        $product->setPrice('19.99');
//
//        $dm = $this->get('doctrine_mongodb')->getManager();

        $id = '579afa64cfc49a07008b456b';

//        $product = $this->get('doctrine_mongodb')
//            ->getRepository('AcmeStoreBundle:Product')
//            ->find($id);
//
//        if (!$product) {
//            throw $this->createNotFoundException('No product found for id '.$id);
//        }


//        $dm = $this->get('doctrine_mongodb')->getManager();
//        $product = $dm->getRepository('AcmeStoreBundle:Product')->find($id);
//
//        if (!$product) {
//            throw $this->createNotFoundException('No product found for id '.$id);
//        }
//
//        $product->setName('New product name!');
//        $dm->flush();



//        $repository = $this->get('doctrine_mongodb')
//            ->getManager()
//            ->getRepository('AcmeStoreBundle:Product');
//        $product = $repository->find($id);
//        var_dump($product);

        $predis = new RedisCache();
        $predis->setRedis(new Client());
        $pr = $predis->getRedis();
//        $cache_lifetime = 3600;
//var_dump($predis);
//        $client = new Predis\Client();
//        $client->set('foo', 'bar');
//        $value = $client->get('foo');

//        $redis = $this->container->get('snc_redis.default');
//        $val = $redis->incr('foo:bar');


        $pr->set('foo', 'bar');
       echo '<pre>'; var_dump($predis); echo '</pre>';

        $products = $this->get('doctrine_mongodb')
            ->getManager()
            ->createQueryBuilder('AcmeStoreBundle:Product')
            ->field('_id')->equals($id)
            ->limit(10)
            ->sort('price', 'ASC')
//            ->setResultCacheDriver($predis)
//            ->setResultCacheLifetime($cache_lifetime)
            ->getQuery()
            ->execute()
            ;
//var_dump($products->toArray());

        $products = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('AcmeStoreBundle:Product')
            ->findAllOrderedByName();

var_dump($products->toArray());

//        $user = $dm->getRepository('Product')->find('sdad');

//        $queryBuilder = $dm->createQueryBuilder();
//
//        $query = $queryBuilder
//            ->select('id', 'name')
//            ->from('Product', 'p')
//            ->where('email = ?')
//        ;
//
//        $a = $query->getQuery()->getResult();

//
//        $dm->persist($product);
//        $dm->flush();

//        $em = $this->getDoctrine('doctrine_mongodb')->getManager();

//        $users = $dm->find('Product', 'p')->limit(1);

//        $redisTest = new RedisTest();
//        $redisTest->findMostPopular(5, $em);
//        var_dump($redisTest);

        return $this->render('AcmeStoreBundle:Default:index.html.twig');
    }

    /**
     * @Route("/add")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {

        $id = '579afa64cfc49a07008b456b';

        //#### wyciąganie z bazy
        $products = $this->get('doctrine_mongodb')
            ->getManager()
            ->createQueryBuilder('AcmeStoreBundle:Product')
//            ->field('_id')->equals($id)
            ->limit(1)
            ->sort('price', 'ASC')
            ->getQuery()
            ->execute()
        ;

        $product = new Product();
        $product->setName('A foo bar');
        $product->setPrice('19.99');

        dump($products->toArray());

        //#### obłsuga redisa
//        $client = new Predis\Client();
//        $client->set('foo', 'bar');
//        $value = $client->get('foo');

        //### obsługa sesji
        $session = $request->getSession();
        $session->set('foo', 'bar');
        $foo = $session->get('foo');
        dump($foo);


        //#### wysyłka cookie
        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        $response->headers->setCookie(new Cookie('name', 'value', 0, '/'));
        return $response;
    }

    /**
     * @Route("/addProduct")
     * @return Response
     */
    public function addProduct() {

        $product = new Product();
        $product->setName('A foo bar2');
        $product->setPrice('19.11');
        $product->addComment((new Comment())->setTitle('fdsfds'));

        dump($product);

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();


        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     * @Route("/addComment")
     * @return Response
     */
    public function addComment() {

        $comment = new Comment();
        $comment->setTitle('comment1');
        $comment->setDescription('Description1');
        $comment->setProductId('579afa64cfc49a07008b456b');


        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($comment);
        $dm->flush();

        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     * @Route("/groupPost")
     * @return Response
     */
    public function groupPost() {

        $dm = $this->get('doctrine_mongodb')->getManager();

        $post = $dm->createQueryBuilder('AcmeStoreBundle:Post')
            ->group(array(), array('count' => 0))
            ->reduce('function (obj, prev) { prev.count++; }')
            ->field('type')->equals('hide')
            ->getQuery()
            ->execute();

        dump($post->toArray());


        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     * @Route("/groupPostRepository")
     * @return Response
     */
    public function groupPostRepository() {

        $post = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('AcmeStoreBundle:Post')
            ->groupCollection();

        dump($post->toArray());

        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     * @Route("/updatePost")
     * @return Response
     */
    public function updatePost() {
        $id = '579efba622bb505a0e9d20ac';

        $dm = $this->get('doctrine_mongodb')->getManager();

        $dm->createQueryBuilder('AcmeStoreBundle:Post')
            ->update()
            ->field('type')->set('hide')
            ->field('_id')->equals($id)
            ->getQuery()
            ->execute();

        $dm->flush();


        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     * @Route("/insertObject")
     * @return Response
     */
    public function insertObject() {
        $id = '579efba622bb505a0e9d20ac';

        $dm = $this->get('doctrine_mongodb')->getManager();

        $dm->createQueryBuilder('AcmeStoreBundle:Post')
            ->setNewObj(array(
                'username' => 'jwage',
                'password' => 'password',
            ))
            ->field('_id')->equals($id)
            ->getQuery()
            ->execute();

        $dm->flush();

        $response = $this->render('AcmeStoreBundle:Default:index.html.twig');
        return $response;
    }

    /**
     *
     * @Route("/validateParam/{type}")
     * @param $type
     *
     * @return Response
     */
    public function validateParam($type) {

        $post = new Post();
        $post->setTitle('adsasd');
        $post->setType($type);

        $validator = $this->get('validator');
        $errors = $validator->validate($post);


        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new JsonResponse($errors);
        }

        $encoders = array(new JsonEncoder());
        $normalizers = array($normalizer = new ObjectNormalizer());
        $normalizer->setIgnoredAttributes(array('type'));
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($post, 'json');

//        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
//        $json = $serializer->serialize($post, 'json');

        return new JsonResponse($json);
    }
}
