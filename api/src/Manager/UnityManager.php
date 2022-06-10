<?php

namespace App\Manager;

use App\Entity\Company;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Unity;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of UnityManager
 *
 * @author mariaDebawi
 */
class UnityManager extends AbstractManager
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
     * @var Unity
     */
    private $unity;

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
        $this->unity = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->unity = $this->apiEntityManager
                    ->getRepository(Unity::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->unity instanceof Unity)) {
                $this->exceptionManager->throwNotFoundException('no_unity_found');
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
     * Get getUnity
     *
     * @return Unity
     */
    public function getUnity($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->unity->toArray($toSnake);
        }

        return $this->unity;
    }

    /**
     * list
     *
     * @return array
     */
    public function unities()
    {

        $filters = (array) $this->request->get('Unities');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $unities = $this->apiEntityManager
                ->getRepository(Unity::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($unities, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($unities, $filters['index'], $filters['size'], $total)];
    }

    public function unitiesChoice()
    {
        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $unities = $this->apiEntityManager
                ->getRepository(Unity::class)
                ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($unities, 'code', ['code', 'name']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $unity = (array) $this->request->get('Unity');

        $this->validateUnicity(Unity::class, 'name', ['name' => $unity['name']], $this->unity);

        $unity['company'] = $this->company;

        if (is_a($this->unity, Unity::class)) {
            return $this->updateObject($this->unity, $unity);
        }

        $this->unity = $this->insertObject($unity, Unity::class);
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->unity->getCode(),
        ]];
    }

}
