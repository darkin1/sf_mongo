<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add('lucky', new Route('/blog/{slug}', array(
    '_controller' => 'AppBundle:Lucky:number',
)));

return $collection;