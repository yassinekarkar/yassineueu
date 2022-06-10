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
class VatController extends AbstractController
{

    /**
     *
     * @var \App\Manager\VatManager
     */
    private $manager;

    public function __construct(\App\Manager\VatManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/vats", name="front-vat", methods={"GET"})
     * @Mapping(object="App\ApiModel\Vat\Vats", as="Vats")
     *
     */
    public function Vats(): Array
    {
        return $this->manager
                        ->init()
                        ->vats();
    }

    /**
     * @Route("/vats/choices", name="front-list-vats", methods={"GET"})
     */
    public function VatsChoice(): Array
    {
        return $this->manager
                        ->init()
                        ->vatsChoice();
    }

    /**
     * @Route("/vat", name="front-create-vat", methods={"POST"})
     * @Mapping(object="App\ApiModel\Vat\Vat", as="Vat")
     */
    public function create(): Array
    {
        return $this->manager
                        ->init()
                        ->set();
    }

    /**
     * @Route("/vat/{code}", name="front-get-vat", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getVat(true)
        ];
    }

    /**
     * @Route("/vat/{code}", name="front-set-vat", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Vat\Vat", as="Vat")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

}
