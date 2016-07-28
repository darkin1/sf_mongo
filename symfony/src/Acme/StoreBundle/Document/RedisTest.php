<?php
namespace Acme\StoreBundle\Document;

//use Snc\RedisBundle\Doctrine\Cache\RedisCache;
use Doctrine\Common\Cache\RedisCache;
use Predis\Client;

class RedisTest {

    public function findMostPopular($limit = 3)
    {
//        new RedisCache();
        # init predis client
        $predis = new RedisCache();
//        $predis->setRedis(new Client());
        # define cache lifetime period as 1 hour in seconds
        $cache_lifetime = 3600;

//        return $this->getEntityManager()
//            ->createQuery('SELECT c FROM AcmeBundle:Country c '
//                . 'WHERE c.active = 1 ORDER BY c.sort DESC')
//            ->setMaxResults($limit)
//            # pass predis object as driver
//            ->setResultCacheDriver($predis)
//            # set cache lifetime
//            ->setResultCacheLifetime($cache_lifetime)
//            ->getResult();
    }

}