<?php

namespace App\Manager;

use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Currency;

/**
 * Description of CurrencyManager
 *
 * @author walidsaadaoui
 */
class CurrencyManager extends AbstractManager
{

    /**
     *  @var string
     */
    private $code;

    /**
     *
     * @var Currency
     */
    private $currency;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
            Registry $entityManager,
            ExceptionManager $exceptionManager,
            RequestStack $requestStack
    )
    {
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    /**
     * AbstractManager initializer.
     */
    public function init($settings = [])
    {
        parent::setSettings($settings);

        $this->currency = null;
        $this->userCaller = $this->request->get('userCaller');

        if ($this->getCode()) {
            // find existing currency
            $this->currency = $this->apiEntityManager
                    ->getRepository(Currency::class)
                    ->findOneByCode($this->getCode());

            if (!($this->currency instanceof Currency)) {
                $this->exceptionManager->throwNotFoundException('no_currency_found');
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
     * Get currency
     *
     * @return Currency
     */
    public function getCurrency($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->currency->toArray($toSnake);
        }

        return $this->currency;
    }

    public function currencies()
    {
        $filters = (array) $this->request->get('Currencies');

        $currencies = $this->apiEntityManager
                ->getRepository(Currency::Class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($currencies, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($currencies, $filters['index'], $filters['size'], $total)];
    }

    public function listChoices()
    {
        $currencies = $this->apiEntityManager
                ->getRepository(Currency::class)
                ->getByFilters(['index' => -1, 'search' => $this->request->get('search')]);

        return ['data' => array_values(MyTools::getArrayFromResultSet($currencies, 'code', ['code', 'longname']))];
    }

    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $currency = (array) $this->request->get('Currency');

        $this->validateUnicity(Currency::class, 'name', ['name' => $currency['name']], $this->currency);
        $this->validateUnicity(Currency::class, 'shortname', ['shortname' => $currency['shortname']], $this->currency);

        if (is_a($this->currency, Currency::class)) {
            return $this->updateObject($this->currency, $currency);
        }

        $this->currency = $this->insertObject($currency, Currency::class);

        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->currency->getCode(),
        ]];
    }

}
