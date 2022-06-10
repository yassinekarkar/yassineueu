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
class CurrencyController extends AbstractController
{

    /**
     *
     * @var \App\Manager\CurrencyManager
     */
    private $manager;

    public function __construct(\App\Manager\CurrencyManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/currencies", name="front-list-currencies", methods={"GET"})
     * @Mapping(object="App\ApiModel\Currency\Currencies", as="Currencies")
     */
    public function currencies(): Array
    {
        return $this->manager->currencies();
    }

    /**
     * @Route("/currencies/choices", name="front-list-currencies-choices", methods={"GET"})
     */
    public function currenciesChoice(): Array
    {
        return $this->manager->listChoices();
    }

    /**
     * @Route("/currency/{code}", name="front-get-currency", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getCurrency(true)
        ];
    }

    /**
     * @Route("/currency", name="front-create-currency", methods={"POST"})
     * @Mapping(object="App\ApiModel\Currency\Currency", as="Currency")
     */
    public function create(): Array
    {
        return $this->manager
            ->set();
    }

    /**
     * @Route("/currency/{code}", name="front-set-currency", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Currency\Currency", as="Currency")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->set();
    }

}
