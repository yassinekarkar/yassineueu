<?php

namespace App\Manager;

use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\PaymentCondition;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of PaymentConditionManager
 *
 * @author mariaDebawi
 */
class PaymentConditionManager extends AbstractManager
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
     * @var PaymentCondition
     */
    private $paymentCondition;

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
        $this->paymentCondition = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');
        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->paymentCondition = $this->apiEntityManager
                    ->getRepository(PaymentCondition::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->paymentCondition instanceof PaymentCondition)) {
                $this->exceptionManager->throwNotFoundException('no_payment_condition_found');
            }
           // $this->company = $this->vat->getCompany();
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
    public function paymentConditions()
    {

        $filters = (array) $this->request->get('PaymentConditions');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $paymentConditions = $this->apiEntityManager
                ->getRepository(PaymentCondition::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($paymentConditions, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($paymentConditions, $filters['index'], $filters['size'], $total)];
    }

    /**
     * Get getPaymentCondition
     *
     * @return PaymentCondition
     */
    public function getPaymentCondition($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->paymentCondition->toArray($toSnake);
        }

        return $this->paymentCondition;
    }

    public function paymentConditionsChoice()
    {
        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }
        $paymentCondition = $this->apiEntityManager
                ->getRepository(PaymentCondition::class)
                ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($paymentCondition, 'code', ['code', 'longname']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $paymentCondition = (array) $this->request->get('PaymentCondition');

        $this->validateUnicity(PaymentCondition::class, 'value', ['value' => $paymentCondition['value']], $this->paymentCondition);

        $paymentCondition['company'] = $this->company;

        if (is_a($this->paymentCondition, PaymentCondition::class)) {
            return $this->updateObject($this->paymentCondition, $paymentCondition);
        }

        $this->paymentCondition = $this->insertObject($paymentCondition, PaymentCondition::class);
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->paymentCondition->getCode(),
        ]];
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->paymentCondition);
    }

}
