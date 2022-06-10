<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/back")
 * @Security("is_granted('ROLE_BACK')")
 */
class CompanyController extends AbstractController
{

    /**
     *
     * @var \App\Manager\CompanyManager
     */
    private $manager;

    /**
     *
     * @var \App\Manager\PaymentConditionManager
     */
    private $pcManager;

    /**
     *
     * @var \App\Manager\IbanManager
     */
    private $ibanManager;

    /**
     *
     * @var \App\Manager\VatManager
     */
    private $vatManager;

    /**
     *
     * @var \App\Manager\UnityManager
     */
    private $unityManager;

    public function __construct(
            \App\Manager\CompanyManager $manager,
            \App\Manager\PaymentConditionManager $pcManager,
            \App\Manager\IbanManager $ibanManager,
            \App\Manager\VatManager $vatManager,
            \App\Manager\UnityManager $unityManager,
            \App\Manager\ProductManager $productManager


    )
    {
        $this->manager = $manager;
        $this->pcManager = $pcManager;
        $this->ibanManager = $ibanManager;
        $this->vatManager = $vatManager;
        $this->unityManager = $unityManager;
        $this->productManager = $productManager;
    }

    /**
     * @Route("/company/{code}", name="back-get-company", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getCompany(true)
        ];
    }

    /**
     * @Route("/companies", name="back-list-companies", methods={"GET"})
     * @Mapping(object="App\ApiModel\Company\Companies", as="Companies")
     */
    public function companies(): Array
    {
        return $this->manager->companies();
    }

    /**
     * @Route("/company/{code}", name="back-set-company", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Company\CompanyEdit", as="Company")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

    /**
     * @Route("/company/{code}/payment-conditions", name="back-payment-conditions", methods={"GET"})
     * @Mapping(object="App\ApiModel\PaymentCondition\PaymentConditions", as="PaymentConditions")
     *
     */
    public function PaymentConditions($code): Array
    {
        return $this->pcManager
                        ->init(['companyCode' => $code])
                        ->paymentConditions();
    }

    /**
     * @Route("/company/{code}/ibans", name="back-ibans", methods={"GET"})
     * @Mapping(object="App\ApiModel\Iban\Ibans", as="Ibans")
     *
     */
    public function Ibans($code): Array
    {
        return $this->ibanManager
                        ->init(['companyCode' => $code])
                        ->ibans();
    }

    /**
     * @Route("/company/{code}/vats", name="back-vats", methods={"GET"})
     * @Mapping(object="App\ApiModel\Vat\Vats", as="Vats")
     */
    public function Vats($code): Array
    {
        return $this->vatManager
            ->init(['companyCode' => $code])
            ->vats();
    }



    /**
     * @Route("/company/{code}/unities", name="back-unities", methods={"GET"})
     * @Mapping(object="App\ApiModel\Unity\Unities", as="Unities")
     */
    public function Unities($code): Array
    {
        return $this->unityManager
            ->init(['companyCode' => $code])
            ->unities();
    }


    /**
     * @Route("/company/{code}/products", name="back-products", methods={"GET"})
     * @Mapping(object="App\ApiModel\Product\Products", as="Products")
     */
    public function Products($code): Array
    {
        return $this->productManager
            ->init(['companyCode' => $code])
            ->products();
    }





}
