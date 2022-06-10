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
class QuoteController extends AbstractController
{

    /**
     *
     * @var \App\Manager\QuoteManager
     */
    private $manager;

    public function __construct(\App\Manager\QuoteManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route("/quotes", name="front-quotes", methods={"GET"})
     * @Mapping(object="App\ApiModel\Quote\Quotes", as="Quotes")
     *
     */
    public function Quotes(): array
    {
        return $this->manager
            ->init()
            ->quotes();
    }

    /**
     * @Route("/quote/{code}", name="front-get-quote", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getQuote(true)
        ];
    }

    /**
     * @Route("/quotes/choices", name="front-list-quotes", methods={"GET"})
     */
    public function QuotesChoice(): Array
    {
        return $this->manager
            ->init()
            ->quotesChoice();
    }


    /**
     * @Route("/quote", name="front-create-quote", methods={"POST"})
     * @Mapping(object="App\ApiModel\Quote\Quote", as="Quote")
     */
    public function create(): Array
    {
        return $this->manager
            ->init()
            ->create();
    }

    /**
     * @Route("/quote/{code}", name="front-update-quote", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Quote\Quote", as="Quote")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->edit();
    }

    /**
     * @Route("/quote/{code}", name="front-set-status-quote", methods={"PATCH"})
     * @Mapping(object="App\ApiModel\Quote\Quotes", as="Quotes")
     */
    public function setStatus($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->setStatus();
    }


    /**
     * @Route("/quote/{code}", name="front-delete-quote", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\Quote\Quote", as="Quote")
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