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
class UnityController extends AbstractController
{

    /**
     *
     * @var \App\Manager\UnityManager
     */
    private $manager;

    public function __construct(\App\Manager\UnityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/unities", name="front-Unities", methods={"GET"})
     * @Mapping(object="App\ApiModel\Unity\Unities", as="Unities")
     *
     */
    public function Unities(): Array
    {
        return $this->manager
                        ->init()
                        ->unities();
    }

    /**
     * @Route("/unities/choices", name="front-unities", methods={"GET"})
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function UnitiesChoice(): Array
    {
        return $this->manager
                        ->init()
                        ->unitiesChoice();
    }

    /**
     * @Route("/unity", name="front-create-unity", methods={"POST"})
     * @Mapping(object="App\ApiModel\Unity\Unity", as="Unity")
     */
    public function create(): Array
    {
        return $this->manager
                        ->init()
                        ->set();
    }

    /**
     * @Route("/unity/{code}", name="front-get-unity", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getUnity(true)
        ];
    }

    /**
     * @Route("/unity/{code}", name="front-update-unity", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Unity\Unity", as="Unity")
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

}
