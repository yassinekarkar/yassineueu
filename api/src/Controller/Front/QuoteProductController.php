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
class QuoteProductController extends AbstractController
{

    /**
     *
     * @var \App\Manager\QuoteProductManager
     */
    private $manager;

    public function __construct(\App\Manager\QuoteProductManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/quoteProducts", name="front-quoteproducts", methods={"GET"})
     * @Mapping(object="App\ApiModel\QuoteProduct\QuoteProducts", as="QuoteProducts")
     *
     */
    public function QuoteProducts(): Array
    {
        return $this->manager
            ->init()
            ->quoteProducts();
    }

    /**
     * @Route("/quoteProduct/{code}", name="front-get-quoteProduct", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getQuoteProduct(true)
        ];
    }


    /**
     * @Route("/quoteProduct", name="front-create-quoteproduct", methods={"POST"})
     * @Mapping(object="App\ApiModel\QuoteProduct\QuoteProduct", as="QuoteProduct")
     */
    public function create(): Array
    {
        return $this->manager
            ->init()
            ->create();
    }



    /**
     * @Route("/quoteProduct/{code}", name="front-set-quoteproduct", methods={"PUT"})
     * @Mapping(object="App\ApiModel\QuoteProduct\QuoteProduct", as="QuoteProduct")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->edit();
    }

    /**
     * @Route("/quoteProduct/{code}", name="front-delete-quoteproduct", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\QuoteProduct\QuoteProduct", as="QuoteProduct")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }





}
