<?php

namespace App\Manager;

use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Country;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of CountryManager
 *
 * @author 
 */
class CountryManager extends AbstractManager
{

    /**
     *  @var string
     */
    private $code;

    /**
     *
     * @var Jobtype
     */
    private $country;

    /**
     * @var CountryManager
     */
    private $currencyManager;

    public function __construct(
            Registry $entityManager,
            ExceptionManager $exceptionManager,
            RequestStack $requestStack,
            CurrencyManager $currencyManager
    )
    {
        $this->currencyManager = $currencyManager;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    /**
     * AbstractManager initializer.
     */
    public function init($settings = [])
    {
        parent::setSettings($settings);

        $this->country = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->getCode()) {
            // find existing job_type
            $this->country = $this->apiEntityManager
                    ->getRepository(Country::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->country instanceof Country)) {
                $this->exceptionManager->throwNotFoundException('no_country_found');
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
    public function countries()
    {

        $filters = (array) $this->request->get('Countries');

        $countries = $this->apiEntityManager
                ->getRepository(Country::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($countries, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($countries, $filters['index'], $filters['size'], $total)];
    }

    /**
     * list Choice
     *
     * @return array
     */
    public function countriesChoice()
    {
        $data = $this->apiEntityManager
                ->getRepository(Country::class)
                ->getByFilters(['page' => -1, 'active' => true]);

        return ['data' => array_values(MyTools::getArrayFromResultSet($data, 'code', ['code', 'longname', 'flag']))];
    }

    /**
     *  Getter.
     *
     * @return string
     */

    /**
     * Get country
     *
     * @return array|Country
     */
    public function getCountry($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            $data = $this->country->toArray($toSnake);
            return $data;
        }

        return $this->country;
    }

    /**
     * Create
     *
     * @return array
     */
    public function set()
    {
        $country = (array) $this->request->get('Country');

        $this->validateUnicity(Country::class, 'name', ['name' => $country['name']], $this->country);
        $this->validateUnicity(Country::class, 'shortname', ['shortname' => $country['shortname']], $this->country);
        $country['currency'] = $this->apiEntityManager
                ->getRepository(\App\Entity\Currency::class)
                ->findOneBy(['code' => $country['currency']]);

        if (!( $country['currency'] instanceof \App\Entity\Currency)) {
            $this->exceptionManager->throwNotFoundException('no_currency_found', ['currency']);
        }
        if (is_a($this->country, Country::class)) {
            return $this->updateObject($this->country, $country);
        }

        $this->country = $this->insertObject($country, Country::class);
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->country->getCode(),
        ]];
    }

}

