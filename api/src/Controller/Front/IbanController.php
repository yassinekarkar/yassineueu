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
class IbanController extends AbstractController
{

    /**
     *
     * @var \App\Manager\IbanManager
     */
    private $manager;

    public function __construct(\App\Manager\IbanManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/ibans", name="front-iban", methods={"GET"})
     * @Mapping(object="App\ApiModel\Iban\Ibans", as="Ibans")
     *
     */
    public function Ibans(): Array
    {
        return $this->manager
                        ->init()
                        ->ibans();
    }

    /**
     * @Route("/ibans/choices", name="front-list-ibans", methods={"GET"})
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function IbansChoice(): Array
    {
        return $this->manager
                        ->init()
                        ->IbansChoice();
    }

    /**
     * @Route("/iban", name="front-create-iban", methods={"POST"})
     * @Mapping(object="App\ApiModel\Iban\Iban", as="Iban")
     *  @Security("is_granted('ROLE_FRONT')")
     */
    public function create(): Array
    {
        return $this->manager
                        ->init()
                        ->set();
    }

    /**
     * @Route("/iban/{code}", name="front-get-iban", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getIban(true)
        ];
    }

    /**
     * @Route("/iban/{code}", name="front-set-iban", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Iban\Iban", as="Iban")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

}
