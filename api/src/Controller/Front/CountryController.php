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
class CountryController extends AbstractController
{

    /**
     *
     * @var \App\Manager\CountryManager
     */
    private $manager;

    public function __construct(\App\Manager\CountryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/countries", name="front-list-countries", methods={"GET"})
     * @Mapping(object="App\ApiModel\Country\Countries", as="Countries")
     */
    public function countries(): Array
    {
        return $this->manager->countries();
    }

    /**
     * @Route("/countries/choices", name="front-list-countries-choices", methods={"GET"})
     */
    public function countriesChoice(): Array
    {
        return $this->manager->countriesChoice();
    }

    /**
     * @Route("/country/{code}", name="front-get-country", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getCountry(true)
        ];
    }

    /**
     * @Route("/country", name="front-create-country", methods={"POST"})
     * @Mapping(object="App\ApiModel\Country\Country", as="Country")
     */
    public function create(): Array
    {
        return $this->manager
            ->set();
    }

    /**
     * @Route("/country/{code}", name="front-set-country", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Country\Country", as="Country")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->set();
    }

}
