<?php

namespace App\Manager;

use App\Entity\Company;
use App\Entity\Client;
use App\Entity\Country;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Contracts\Translation\TranslatorInterface;

class ClientManager extends AbstractManager
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
     * @var Client
     */
    private $client;

    /**
     *
     * @var Company
     */
    private $company;

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
        $this->client = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->client = $this->apiEntityManager
                ->getRepository(Client::class)
                ->findOneBy(['code' => $this->getCode()]);

            if (!($this->client instanceof Client)) {
                $this->exceptionManager->throwNotFoundException('no_client_found');
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

    /*/**
     * Get getClient
     *
     * @return Client
     */
    /*public function getClient($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->client->toArray($toSnake);
        }

        return $this->client;
    }*/

  /*  /**
     * Get client
     *
     * @return Client
     */
    /*public function getClient() {
        $data = $this->client->toArray(true);
        $data['country'] = $this->apiEntityManager
            ->getRepository(Country::class)
            ->getByFilters(['data' => $this->client->getId()]);

        return ['data' => $data];
    }*/


     /**
       * Get client
       *
       * @return Client
       */
    public function getClient() {
        $day = 'days';
        $data = $this->client->toArray(true);
        $data['countryName'] = $this->client->getCountry()->getName();
        $data['payment_condition_value'] = $this->client->getPaymentCondition()->getValue().' '. $day;

        return ['data' => $data];
    }





    /**
     * list
     *
     * @return array
     */
    public function clients()
    {

        $filters = (array) $this->request->get('Clients');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $clients = $this->apiEntityManager
            ->getRepository(Client::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($clients, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($clients, $filters['index'], $filters['size'], $total)];
    }

    public function clientsChoice()
    {
        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $clients = $this->apiEntityManager
            ->getRepository(Client::class)
            ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($clients, 'code', ['code', 'name']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $client = (array) $this->request->get('Client');

     //   $this->validateUnicity(Client::class, 'reference', ['reference' => $client['reference']], $this->client);

        $this->findObjects($client ,  ['country' , 'paymentCondition']);
        $client['company'] = $this->company;

        if (is_a($this->client, Client::class)) {
            return $this->updateObject($this->client, $client);
        }

        $this->client = $this->insertObject($client, Client::class);

        return ['data' => [
            'messages' => 'create_success',
            'code' => $this->client->getCode(),
        ]];
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->client);
    }

}