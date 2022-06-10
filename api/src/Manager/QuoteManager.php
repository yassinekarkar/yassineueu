<?php

namespace App\Manager;

use App\Entity\Client;
use App\Entity\Company;
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
class QuoteManager extends AbstractManager
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
     * @var Quote
     */
    private $quote;

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
     * @var QuoteConfig
     */
    private $quoteConfig;

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
        $this->quote = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->quote = $this->apiEntityManager
                ->getRepository(Quote::class)
                ->findOneBy(['code' => $this->getCode()]);

            if (!($this->quote instanceof Quote)) {
                $this->exceptionManager->throwNotFoundException('no_devis_found');
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
     * @return Quote
     */
    public function getQuote()
    {
        $data = $this->quote->toArray(true);

        // dd($this->quote->getCurrency()->getName());
         $data['currency'] = $this->quote->getCurrency()->getName();
         $data['language'] = $this->quote->getLanguage()->getName();

        $data['products'] = $this->apiEntityManager
            ->getRepository(QuoteProduct::class)
            ->getByFilters(['quote' => $this->quote->getId()]);

      //dd($config);

       //$this->language = $config['language'];
       //dd($data['products']);
       
        return ['quote' => $data];

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
    public function quotes()
    {

        $filters = (array) $this->request->get('Quotes');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $quotes = $this->apiEntityManager
            ->getRepository(Quote::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($quotes, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($quotes, $filters['index'], $filters['size'], $total)];
    }

    public function quotesChoice()
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
        $quote = (array) $this->request->get('Quote');
        $connection = $this->apiEntityManager->getConnection();
        $connection->beginTransaction();

        //$this->company = $this->request->get('companyUserCaller');
        $this->findObjects($quote , ['client','language','currency']);
        $this->validateUnicity(Quote::class, 'estimateNumber', ['estimateNumber' => $quote['estimateNumber']], $this->quote);

        $this->formatDatetime($quote);

        $quote['creator'] = $this->companyUserCaller ;
       // dd($this->companyUserCaller);
       // $quote['UserName'] = $this->companyUserCaller->getFirstname() ;
       //dd( $quote['UserName']);
        $companyData = $this->company->getCompanyInfo();

        $this->client = $quote['client'];
        $clientData = $this->client->getClientInfo();

        $quoteData = array_merge($quote,$companyData,$clientData);


        try {

            $this->quote = $this->insertObject($quoteData, Quote::class);

            foreach ($quote['products']  as $product) {
                $this->findObjects($product ,  ['vat' , 'unity']);

                $this->insertObject([
                     'quote' => $this->quote,
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
               ], QuoteProduct::class);
            }

            $connection->commit();
            return ['data' => [
                'messages' => 'create_success',
                'code' => $this->quote->getCode(),
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

    public function setStatus() {

      /* $quote = (array) $this->request->get('Quote');

        $quote['status'] = $this->request->get('status');

        $this->updateObject($this->quote->setStatus(),$quote['status']);*/
        $quote = (array) $this->request->get('Quotes');
        $return = $this->updateObject($this->quote, $quote);
     

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