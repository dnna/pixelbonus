<?php
namespace Pixelbonus\CommonBundle\Extension;

use Doctrine\ORM\Query\QueryException;

class PaginatorExtension extends \Twig_Extension {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
        $this->container->get('twig.loader')->addPath(__DIR__.'/../../../../vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form');
    }

    public function getFilters() {
        return array(
            'paginate'  => new \Twig_Filter_Method($this, 'paginate'),
        );
    }

    public function paginate($obj, $limitPerPage = 10, $pageParam = 'page') {
        if(!isset($limitPerPage)) {
            $limitPerPage = 10;
        }
        $paginator  = $this->container->get('knp_paginator');
        if($this->container->isScopeActive('request')) {
            try {
                $pagination = $paginator->paginate(
                    $obj,
                    $this->container->get('request')->query->get($pageParam, 1) /*page number*/,
                    $limitPerPage /*limit per page*/
                );
            } catch(QueryException $e) {
                $pagination = $paginator->paginate(
                    $obj,
                    1,
                    $limitPerPage /*limit per page*/
                );
            }
        } else {
            $pagination = $paginator->paginate(
                $obj,
                1/*page number*/,
                9999999999999999999999999999999999 /*limit per page*/
            );
        }
        return $pagination;
    }

    public function getName() {
        return 'pixelbonus.paginator.extension';
    }
}
