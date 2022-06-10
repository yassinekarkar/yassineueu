<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/front")
 * @Security("is_granted('ROLE_FRONT')")
 */
class ProductController extends AbstractController
{

     /**
     *
     * @var \App\Manager\ProductManager
     */
    private $manager;

    public function __construct(\App\Manager\ProductManager $manager)
    {
        $this->manager = $manager;
    }


    
    /**
     * @Route("/products", name="front-Products", methods={"GET"})
     * @Mapping(object="App\ApiModel\Product\Products", as="Products")
     *
     */
    public function Products(): Array
    {
        return $this->manager
                        ->init()
                        ->products();
    }

    /**
     * @Route("/products/choices", name="front-products", methods={"GET"})
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function ProductsChoice(): Array
    {
        return $this->manager
                        ->init()
                        ->productsChoice();
    }

    /**
     * @Route("/product", name="front-create-product", methods={"POST"})
     * @Mapping(object="App\ApiModel\Product\Product", as="Product")
     */
    public function create(): Array
    {
        return $this->manager
                        ->init()
                        ->set();
    }

    /**
     * @Route("/product/{code}", name="front-get-product", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getProduct(true)
        ];
    }

    /**
     * @Route("/product/{code}", name="front-update-product", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Product\Product", as="Product")
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

    /**
     * @Route("/product/{code}", name="front-delete-product", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\Product\Product", as="Product")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }

}
