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
class ConfigQuoteController extends AbstractController
{
    /**
     *
     * @var \App\Manager\ConfigQuoteManager
     */
    private $manager;

    public function __construct(\App\Manager\ConfigQuoteManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route("/configQuotes", name="front-configQuotes", methods={"GET"})
     * @Mapping(object="App\ApiModel\ConfigQuote\ConfigQuotes", as="ConfigQuotes")
     *
     */
    public function ConfigQuotes(): Array
    {
        return $this->manager
            ->init()
            ->configQuotes();
    }

    /**
     * @Route("/configQuote/{code}", name="front-get-quoteConfig", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getQuoteConfig(true)
        ];
    }

    /**
     * @Route("/configquote", name="front-create-configquote", methods={"POST"})
     * @Mapping(object="App\ApiModel\ConfigQuote\ConfigQuote", as="ConfigQuote")
     */
    public function create(): Array
    {
        return $this->manager
            ->init()
            ->create();
    }

    /**
     * @Route("/configquote/{code}", name="front-set-configquote", methods={"PUT"})
     * @Mapping(object="App\ApiModel\ConfigQuote\ConfigQuote", as="ConfigQuote")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->edit();
    }


}