<?php

namespace App\Manager;

use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;
use SSH\MsJwtBundle\Manager\MailManager;

/**
 * Description of CompanyManager
 *
 * @author mariaDebawi
 */
class CompanyManager extends AbstractManager
{

    /**
     *  @var string
     */
    private $code;

    /**
     *
     * @var company
     */
    private $company;

    /**
     * @var MailManager
     */
    private $mailManager;

    /**
     * @var CompanyManager
     */
    private $countryManager;

    /**
     *
     * @var string
     */
    private $frontHost;

    public function __construct(
            Registry $entityManager,
            ExceptionManager $exceptionManager,
            RequestStack $requestStack,
            CountryManager $countryManager,
            MailManager $mailManager,
            $frontHost
    )
    {
        $this->frontHost = $frontHost;
        $this->mailManager = $mailManager;
        $this->countryManager = $countryManager;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    /**
     * AbstractManager initializer.
     */
    public function init($settings = [])
    {
        parent::setSettings($settings);

        $this->company = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->getCode()) {
            // find existing job_type
            $this->company = $this->apiEntityManager
                    ->getRepository(Company::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->company instanceof Company)) {
                $this->exceptionManager->throwNotFoundException('no_company_found');
            }
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
     * list
     *
     * @return array
     */
    public function companies()
    {

        $filters = (array) $this->request->get('Companies');

        $companies = $this->apiEntityManager
                ->getRepository(Company::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($companies, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($companies, $filters['index'], $filters['size'], $total)];
    }

    /**
     * list Choice
     *
     * @return array
     */
    public function companiesChoice()
    {
        $data = $this->apiEntityManager
                ->getRepository(Company::class)
                ->getByFilters([
            'index' => -1,
            'active' => true,
            'search' => $this->request->get('search')
        ]);

        return ['data' => array_values(MyTools::getArrayFromResultSet($data, 'code', ['code', 'longname']))];
    }

    /**
     * Get company
     *
     * @return array|Company
     */
    public function getCompany($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            $data = $this->company->toArray($toSnake);
            return $data;
        }

        return $this->company;
    }

    /**
     * Create
     *
     * @return type
     */
    public function validateSubscription($token)
    {
        $this->user = $this->apiEntityManager
                ->getRepository(User::class)
                ->findOneBy(['token' => $token]);

        if (!($this->user instanceof User)) {
            $this->exceptionManager->throwNotFoundException('invalid_token');
        }

        if ($this->user->getEnabled()) {
            $this->exceptionManager->throwExpiredSessionException('token_already_validated');
        }

        return $this->updateObject($this->user, ['enabled' => true]);
    }

    /**
     * Create
     *
     * @return array
     */
    public function create()
    {
        $company = (array) $this->request->get('Subscribe');

        $connection = $this->apiEntityManager->getConnection();

        $connection->beginTransaction();

        try {

            $this->validateUnicity(Company::class, 'mail', ['mail' => $company['mail']]);

            $company['country'] = $this->countryManager
                    ->init(['code' => $company['country']])
                    ->getCountry();
            $this->company = $this->insertObject($company, Company::class);

            // create user
            $this->createUser($company);

            $connection->commit();

            return ['data' => [
                    'messages' => 'create_success',
                    'code' => $this->company->getCode(),
            ]];
        } catch (\Exception $ex) {
            $connection->rollback();
            if ($ex instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                throw $ex;
            }

            $this->exceptionManager->throwConflictException('error_creation_company');
        }

        return ['data' => [
                'messages' => 'create_fail',
        ]];
    }

    public function set()
    {
        $company = (array) $this->request->get('Company');

        return $this->updateObject($this->company, $company);
    }

    public function products(){

    }


//    public function createUser($userdata)
//    {
//        $userdata['password'] = hash('sha512', $userdata['password']);
//        $userdata['company'] = $this->company;
//        $this->user = $this->insertObject($userdata, User::class);

    /* if ($this->user->getMail()) {
      try {
      $mailData = [
      'mail' => $this->user->getMail(),
      'password' =>  $userdata['password'],
      //   'host' => 'maria.debawi@smarteo.tn',

      ];

      $sended = $this->mailManager->sendMail($this->user->getMail(), 'new_company_subject', $mailData, 'Mail/parametreConnexion.html.twig');
      } catch (\Exception $ex) {
      $this->exceptionManager->throwOtherException('mail_not_sended');
      }
      } */
//        return ['data' => [
//                'messages' => 'create_success',
//                'code' => $this->user->getCode(),
//        ]];
//    }

    public function createUser($userdata)
    {
        $userdata = (array) $userdata;

        $this->validateUnicity(User::class, 'mail', ['mail' => $userdata['mail']]);

        $password = MyTools::getPassword();
        if (isset($userdata['password'])) {
            $password = $userdata['password'];
        }

        $userdata = array_merge($userdata, [
            'company' => $this->company,
            'role' => self::ROLE_SUPER_USER,
            'password' => hash('sha512', $password)
        ]);

        $this->user = $this->insertObject($userdata, User::class);

        if ($this->user->getMail()) {
            try {
                $mailData = [
                    'mail' => $this->user->getMail(),
                    'token' => $this->user->getToken(),
                    'password' => $password,
                    'host' => $this->frontHost
                ];

                $sended = $this->mailManager->sendMail($this->user->getMail(), 'new_user_subject', $mailData, 'Mail/parametreConnexion.html.twig');
            } catch (\Exception $ex) {
                $this->exceptionManager->throwOtherException('mail_not_sended');
            }
        }
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->user->getCode(),
        ]];
    }

    public function getInformations()
    {
        $this->companyUserCaller = $this->request->get('companyUserCaller');
        $data['user'] = $this->companyUserCaller->toArray(true);
        $data['company'] = $this->companyUserCaller->getCompany()->toArray(true);

        return ['data' => $data];
    }

}
