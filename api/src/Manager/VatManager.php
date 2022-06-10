<?php

namespace App\Manager;

use App\Entity\Company;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Vat;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of AvtManager
 *
 * @author mariaDebawi
 */
class VatManager extends AbstractManager
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
     * @var Company
     */
    private $company;

    /**
     *
     * @var vat
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
        $this->vat = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }


        if ($this->getCode()) {
            // find existing job_type
            $this->vat = $this->apiEntityManager
                    ->getRepository(Vat::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->vat instanceof Vat)) {
                $this->exceptionManager->throwNotFoundException('no_payment_condition_found');
            }
            $this->company = $this->vat->getCompany();
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
     * list
     *
     * @return array
     */
    public function vats()
    {


        $filters = (array) $this->request->get('Vats');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $vats = $this->apiEntityManager
                ->getRepository(Vat::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($vats, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($vats, $filters['index'], $filters['size'], $total)];
    }

    /**
     * Get vat
     *
     * @return Vat
     */
    public function getVat($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->vat->toArray($toSnake);
        }

        return $this->vat;
    }

    public function vatsChoice()
    {

        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $vat = $this->apiEntityManager
                ->getRepository(Vat::class)
                ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($vat, 'code', ['code', 'longname']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $vat = (array) $this->request->get('Vat');

        $this->validateUnicity(Vat::class, 'value', ['value' => $vat['value']], $this->vat);

        $vat['company'] = $this->company;

        if (is_a($this->vat, Vat::class)) {
            return $this->updateObject($this->vat, $vat);
        }

        $this->vat = $this->insertObject($vat, Vat::class);
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->vat->getCode(),
        ]];
    }

}
