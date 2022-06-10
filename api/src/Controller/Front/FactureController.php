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
class FactureController extends AbstractController
{

    /**
     *
     * @var \App\Manager\FactureManager
     */
    private $manager;

    public function __construct(\App\Manager\FactureManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route("/factures", name="front-factures", methods={"GET"})
     * @Mapping(object="App\ApiModel\Facture\Factures", as="Factures")
     *
     */
    public function Factures(): array
    {
        return $this->manager
            ->init()
            ->factures();
    }

    /**
     * @Route("/facture/{code}", name="front-get-facture", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getFacture(true)
        ];
    }

    /**
     * @Route("/factures/choices", name="front-list-factures", methods={"GET"})
     */
    public function FacturesChoice(): Array
    {
        return $this->manager
            ->init()
            ->facturessChoice();
    }


    /**
     * @Route("/facture", name="front-create-facture", methods={"POST"})
     * @Mapping(object="App\ApiModel\Facture\Facture", as="Facture")
     */
    public function create(): Array
    {
        return $this->manager
            ->init()
            ->create();
    }

    /**
     * @Route("/facture/{code}", name="front-update-facture", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Facture\Facture", as="Facture")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->edit();
    }

    /**
     * @Route("/facture/{code}", name="front-delete-facture", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\Facture\Facture", as="Facture")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }


    /* /**
      * @Route("/quote/quoteProduct", name="front-create-productquote", methods={"POST"})
      * @Mapping(object="App\ApiModel\QuoteProduct\QuoteProduct", as="QuoteProduct")
      */
    /* public function createQuoteProduct(): Array
     {
         return $this->manager
             ->init()
             ->createQuoteProduct();
     }
 */



}