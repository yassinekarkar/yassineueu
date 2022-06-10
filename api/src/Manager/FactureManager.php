<?php

namespace App\Manager;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Facture;
use App\Entity\FactureProduct;
use App\Entity\Language;
use App\Entity\Product;
use App\Entity\QuoteConfig;
use App\Entity\QuoteProduct;
use App\Entity\Unity;
use App\Entity\Vat;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Quote;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of QuoteManager
 *
 * @author yassinekarkar
 */
class FactureManager extends AbstractManager
{
    /**
     *  @var string
     */
    private $code;

    /**
     *  @var string
     */
    private $companyCode;

    /**
     *
     * @var Facture
     */
    private $facture;

    /**
     *
     * @var Company
     */
    private $company;
    /**
     *
     * @var User
     */
    private $creator;

    /**
     *
     * @var Client
     */
    public $client;

    /**
     *
     * @var Language
     */
    private $language;


    /**
     *
     * @var QuoteProduct
     */
    private $quoteProduct;


    /**
     *
     * @var Unity
     */
    private $unity;

    /**
     *
     * @var Vat
     */
    private $vat;


    /**
     * @var CompanyManager
     */
    private $companyManager;

    public function __construct(
        Registry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        CompanyManager $companyManager
    )
    {
        $this->companyManager = $companyManager;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    /**
     * AbstractManager initializer.
     */
    public function init($settings = [])
    {
        parent::setSettings($settings);
        $this->company = null;
        //$this->client = null;
        $this->facture = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->facture = $this->apiEntityManager
                ->getRepository(Facture::class)
                ->findOneBy(['code' => $this->getCode()]);

            if (!($this->facture instanceof Facture)) {
                $this->exceptionManager->throwNotFoundException('no_facture_found');
            }
            //$this->company = $this->vat->getCompany();
        }

        if (!$this->company && $this->getCompanyCode()) {
            $this->company = $this->companyManager
                ->init(['code' => $this->getCompanyCode()])
                ->getCompany();
        }

        return $this;
    }

    /**
     * Setter  code.
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Getter code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Setter  companyCode.
     *
     * @param string $companyCode
     */
    public function setCompanyCode($companyCode)
    {
        $this->companyCode = $companyCode;

        return $this;
    }

    /**
     * Getter code.
     *
     * @return string
     */
    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    /**
     * Get getQuote
     *
     * @return Facture
     */
    public function getFacture()
    {
        $data = $this->facture->toArray(true);

        // dd($this->quote->getCurrency()->getName());
        $data['currency'] = $this->facture->getCurrency()->getName();
        $data['language'] = $this->facture->getLanguage()->getName();
        $data['payment condition'] = $this->facture->getClient()->getPaymentCondition()->getValue().' days';


        $data['products'] = $this->apiEntityManager
            ->getRepository(FactureProduct::class)
            ->getByFilters(['facture' => $this->facture->getId()]);

        //dd($config);

        //$this->language = $config['language'];
        //dd($data['products']);

        return ['facture' => $data];

    }

    /*private function makeClassName($index, $entity = true, $suffix = ''){
        $classes = ['product' , 'config'];
        $class = (in_array($index, $classes) ? 'quote' : '') . ucfirst($index);

        if ($entity) {
            return '\App\Entity\\' . ucfirst($class) . $suffix;
        }

        return $class;
    }*/


    /**
     * list
     *
     * @return array
     */
    public function factures()
    {

        $filters = (array) $this->request->get('Factures');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $factures = $this->apiEntityManager
            ->getRepository(Facture::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($factures, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($factures, $filters['index'], $filters['size'], $total)];
    }

    public function facturesChoice()
    {

        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $quote = $this->apiEntityManager
            ->getRepository(Quote::class)
            ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($quote, 'code', ['code', 'estimate_number']))];
    }

    /*
        /**
         * Update
         *
         *
         * @return array
         */
    /* public function set()
     {
         $quote = (array) $this->request->get('Quote');

         $this->validateUnicity(Quote::class, 'estimateNumber', ['estimateNumber' => $quote['estimateNumber']], $this->quote);
         $this->findObjects($quote ,  ['client','language','currency']);
         $this->formatDatetime($quote);
         $quote['company'] = $this->company;
         $quote['companyMail'] = $this->company->getMail();
         $quote['companyName'] = $this->company->getName();
         $quote['companyAddress'] = $this->company->getAddress();
         $quote['companyZipcode'] = $this->company->getZipcode();
         $quote['companyCity'] = $this->company->getCity();
         $this->client = $quote['client'];
         $quote['clientName'] = $this->client->getName();
         $quote['clientAddress'] = $this->client->getAddress();
         $quote['clientZipcode'] = $this->client->getZipcode();
         $quote['clientCity'] = $this->client->getCity();
        // dd($this->client = $quote['client']);
         $quote['creator'] = $this->companyUserCaller ;
       //  dd($quote);
         if (is_a($this->quote, Quote::class)) {
             return $this->updateObject($this->quote, $quote);
         }


         $this->quote = $this->insertObject($quote, Quote::class);

         dd($quote);
         return ['data' => [
             'messages' => 'create_success',
             'code' => $this->quote->getCode(),
         ]];
     }*/


    /**
     * Create
     *
     * @return array
     */
    public function create()
    {
        $facture = (array) $this->request->get('Facture');
        $connection = $this->apiEntityManager->getConnection();
        $connection->beginTransaction();

        //$this->company = $this->request->get('companyUserCaller');
        $this->findObjects($facture , ['client','language','currency']);
        $this->validateUnicity(Facture::class, 'reference', ['reference' => $facture['reference']], $this->facture);

        $this->formatDatetime($facture);

        $facture['creator'] = $this->companyUserCaller ;

        $companyData = $this->company->getCompanyInfo();

        $this->client = $facture['client'];
        $clientData = $this->client->getClientInfo();

        $factureData = array_merge($facture,$companyData,$clientData);


        try {

            $this->facture = $this->insertObject($factureData, Facture::class);

            foreach ($facture['products']  as $product) {
                $this->findObjects($product ,  ['vat' , 'unity']);

                $this->insertObject([
                    'facture' => $this->facture,
                    'name' => $product['name'],
                    'porder' => $product['porder'],
                    'unit_price' => $product['unit_price'],
                    'amount' => $product['amount'],
                    'discount' => $product['discount'],
                    'discount_fixed_value' => $product['discount_fixed_value'],
                    'unit_price' => $product['unit_price'],
                    'unity' => $product['unity'],
                    'vat' => $product['vat'],
                    'unityValue' => $product['unity']->getName(),
                    'vatValue' => $product['vat']->getValue()
                ], FactureProduct::class);
            }

            $connection->commit();
            return ['data' => [
                'messages' => 'create_success',
                'code' => $this->facture->getCode(),
            ]];

        } catch (\Exception $ex) {
            $connection->rollback();
            if ($ex instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                throw $ex;
            }
            $this->exceptionManager->throwConflictException($ex);
        }

        return ['data' => [
            'messages' => 'create_fail',
        ]];
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function edit()
    {
        $quote = (array) $this->request->get('Quote');
        $this->findObjects($quote , ['client','language','currency']);
        $quote['creator'] = $this->companyUserCaller ;
        $this->formatDatetime($quote);
        //        $this->validateUnicity(BackGroup::class, 'code', ['code' => $groupData['code']], $this->backgroup);
        unset($quote['code']);
        $return = $this->updateObject($this->quote, $quote);
        $return['data']['object'] = $this->quote->getCode();

        return $return;
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->quote);
    }



}