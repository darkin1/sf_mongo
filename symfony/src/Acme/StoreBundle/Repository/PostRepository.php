<?php

namespace Acme\StoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends DocumentRepository
{

    public function groupCollection() {

        $qb = $this->createQueryBuilder()
            ->group(array(), array('count' => 0))
            ->reduce('function (obj, prev) { prev.count++; }')
            ->field('type')->equals('hide')
        ;

            $query = $qb->getQuery();

//            $debug = $query->debug();
//            var_dump($debug);

            return $query->execute();
    }
}