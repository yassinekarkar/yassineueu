<?php

namespace App\Manager;

use App\Entity\Company;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Iban;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of IbanManager
 *
 * @author mariaDebawi
 */
class IbanManager extends AbstractManager
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
     * @var Iban
     */
    private $iban;

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
        $this->iban = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->iban = $this->apiEntityManager
                    ->getRepository(Iban::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->iban instanceof Iban)) {
                $this->exceptionManager->throwNotFoundException('no_iban_found');
            }
        //    $this->company = $this->vat->getCompany();
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
    public function ibans()
    {

        $filters = (array) $this->request->get('Ibans');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $ibans = $this->apiEntityManager
                ->getRepository(Iban::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($ibans, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($ibans, $filters['index'], $filters['size'], $total)];
    }

    /**
     * Get getiban
     *
     * @return iban
     */
    public function getiban($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->iban->toArray($toSnake);
        }

        return $this->iban;
    }

    public function ibansChoice()
    {
        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $ibans = $this->apiEntityManager
                ->getRepository(Iban::class)
                ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($ibans, 'code', ['code', 'iban']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $iban = (array) $this->request->get('Iban');

        $this->validateUnicity(Iban::class, 'iban', ['iban' => $iban['iban']], $this->iban);
        $iban['company'] = $this->company;

        if (is_a($this->iban, Iban::class)) {
            return $this->updateObject($this->iban, $iban);
        }

        $this->iban = $this->insertObject($iban, Iban::class);
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->iban->getCode(),
        ]];
    }

}
