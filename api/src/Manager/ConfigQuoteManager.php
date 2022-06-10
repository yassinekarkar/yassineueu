<?php

namespace App\Manager;

use App\Entity\Company;
use App\Entity\Language;
use App\Entity\QuoteConfig;
use App\Entity\QuoteProduct;
use App\Entity\Unity;
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
 * Description of ConfigQuoteManager
 *
 * @author yassinekarkar
 */

class ConfigQuoteManager extends AbstractManager
{
    /**
     *  @var string
     */
    private $code;

    /**
     *
     * @var QuoteConfig
     */
    private $configQuote;

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
        $this->configQuote = null;


        if ($this->getCode()) {
            // find existing job_type
            $this->configQuote = $this->apiEntityManager
                ->getRepository(QuoteConfig::class)
                ->findOneBy(['code' => $this->getCode()]);

            if (!($this->configQuote instanceof QuoteConfig)) {
                $this->exceptionManager->throwNotFoundException('no_quote_config_found');
            }
            //  $this->company = $this->vat->getCompany();
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
     * list products of quotes
     *
     * @return array
     */
    public function configQuotes()
    {


        $filters = (array) $this->request->get('ConfigQuotes');


        $configQuotes = $this->apiEntityManager
            ->getRepository(QuoteConfig::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($configQuotes, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($configQuotes, $filters['index'], $filters['size'], $total)];
    }


    /**
     * Get config devis
     *
     * @return QuoteConfig
     */

    public function getQuoteConfig($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->configQuote->toArray($toSnake);
        }

        return $this->configQuote;
    }


   /* /**
     * Get getQuote
     *
     * @return QuoteConfig
     */
  /*  public function getQuote()
    {
        $data = $this->configQuote->toArray(true);


        $data['language'] = [];
        $data['currency'] = [];


        $language = $this->apiEntityManager
            ->getRepository(Language::class)
            ->findOneBy(['quote' => $this->configQuote]);
//dd($config);



        return ['quote' => $data];

    }
*/

    /**
     * Creates a quoteConfig object
     *
     * @return array
     * @throws \Exception
     */
    public function create()
    {
        $configQuote = (array) $this->request->get('ConfigQuote');
        $this->findObjects($configQuote ,  ['currency' , 'language', 'quote']);

        $this->configQuote = $this->insertObject($configQuote, QuoteConfig::class);
        return ['data' => [
            'messages' => 'create_success',
            'code' => $this->configQuote->getCode(),
        ]];


    }

    /**
     * @return array
     * @throws \Exception
     */
    public function edit()
    {
        $configQuote = (array) $this->request->get('ConfigQuote');
        $this->findObjects($configQuote ,  ['currency' , 'language', 'quote']);
        //        $this->validateUnicity(BackGroup::class, 'code', ['code' => $groupData['code']], $this->backgroup);
        unset($configQuote['code']);
        $return = $this->updateObject($this->configQuote, $configQuote);
        $return['data']['object'] = $this->configQuote->getCode();

        return $return;
    }


}