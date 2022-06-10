<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/front")
 */
class CompanyController extends AbstractController
{

    /**
     *
     * @var \App\Manager\CompanyManager
     */
    private $manager;

    public function __construct(
            \App\Manager\CompanyManager $manager,
            \App\Manager\CountryManager $countryManager
    )
    {
        $this->countryManager = $countryManager;
        $this->manager = $manager;
    }

    /**
     * @Route("/countries/choices", name="front-list-countries-choices", methods={"GET"})
     */
    public function countriesChoice(): Array
    {
        return $this->countryManager->countriesChoice();
    }

    /**
     * @Route("/subscribe", name="front-create-company", methods={"POST"})
     * @Mapping(object="App\ApiModel\Company\Subscribe", as="Subscribe")
     */
    public function create(): Array
    {
        return $this->manager
                        ->create();
    }

    /**
     * @Route("/validatesubscription/{token}", name="front-validate-subscription", methods={"PUT"})
     */
    public function validateSubscription($token): Array
    {
        return $this->manager
                        ->validateSubscription($token);
    }

    /**
     * @Route("/company/informations", name="front-company-informations", methods={"GET"})
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function getInformations()
    {
        return $this->manager
                        ->getInformations();
    }

    /**
     * @Route("/company/user", name="front-create-user", methods={"POST"})
     * @Mapping(object="App\ApiModel\Company\User", as="User")
     * @Security("is_granted('ROLE_FRONT')")
     */
    public function createUser(Request $request): Array
    {
        return $this->manager
                        ->init()
                        ->createUser($request->get('User'));
    }

}
