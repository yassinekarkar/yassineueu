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
class PaymentConditionController extends AbstractController
{

    /**
     *
     * @var \App\Manager\PaymentConditionManager
     */
    private $manager;

    public function __construct(\App\Manager\PaymentConditionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/payment-conditions", name="front-payment-condition", methods={"GET"})
     * @Mapping(object="App\ApiModel\PaymentCondition\PaymentConditions", as="PaymentConditions")
     *
     */
    public function PaymentConditions(): Array
    {
        return $this->manager
                        ->init()
                        ->paymentConditions();
    }

    /**
     * @Route("/payment-conditions/choices", name="front-list-payment-conditions", methods={"GET"})
     */
    public function PaymentConditionsChoice(): Array
    {
        return $this->manager->init()->paymentConditionsChoice();
    }

    /**
     * @Route("/payment-condition", name="front-create-payment-condition", methods={"POST"})
     * @Mapping(object="App\ApiModel\PaymentCondition\PaymentCondition", as="PaymentCondition")
     *
     */
    public function create(): Array
    {
        return $this->manager
                        ->init()
                        ->set();
    }

    /**
     * @Route("/payment-condition/{code}", name="front-get-payment-condition", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
                    ->init(['code' => $code])
                    ->getPaymentCondition(true)
        ];
    }

    /**
     * @Route("/payment-condition/{code}", name="front-set-payment-condition", methods={"PUT"})
     * @Mapping(object="App\ApiModel\PaymentCondition\PaymentCondition", as="PaymentCondition")
     */
    public function set($code): Array
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set();
    }

    /**
     * @Route("/payment-condition/{code}", name="front-delete-payment-condition", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\PaymentCOndition\PaymentCondition", as="PaymentCondition")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }
}
