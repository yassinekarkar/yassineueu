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
class ClientController extends AbstractController
{
    /**
     *
     * @var \App\Manager\ClientManager
     */
    private $manager;

    public function __construct(\App\Manager\ClientManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/clients", name="front-Clients", methods={"GET"})
     * @Mapping(object="App\ApiModel\Client\Clients", as="Clients")
     *
     */
    public function Clients(): Array
    {
        return $this->manager
            ->init()
            ->clients();
    }

    /**
     * @Route("/clients/choices", name="front-clients", methods={"GET"})
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function ClientsChoice(): Array
    {
        return $this->manager
            ->init()
            ->clientsChoice();
    }

    /**
     * @Route("/client", name="front-create-client", methods={"POST"})
     * @Mapping(object="App\ApiModel\Client\Client", as="Client")
     */
    public function create(): Array
    {
        return $this->manager
            ->init()
            ->set();
    }

    /**
     * @Route("/client/{code}", name="front-get-client", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getClient(true)
        ];
    }

    /**
     * @Route("/client/{code}", name="front-update-client", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Client\Client", as="Client")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->set();
    }

    /**
     * @Route("/client/{code}", name="front-delete-client", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\Client\Client", as="Client")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }
}