<?php

namespace App\Manager;

use App\Entity\Company;
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
 * Description of quoteProductManager
 *
 * @author yassinekarkar
 */
class QuoteProductManager extends AbstractManager
{
    /**
     *  @var string
     */
    private $code;



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
     *
     * @var QuoteProduct
     */
    private $quoteProduct;



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
        $this->quoteProduct = null;

        if ($this->getCode()) {
            // find existing job_type
            $this->quoteProduct = $this->apiEntityManager
                ->getRepository(QuoteProduct::class)
                ->findOneBy(['code' => $this->getCode()]);

            if (!($this->quoteProduct instanceof QuoteProduct)) {
                $this->exceptionManager->throwNotFoundException('no_quote_product_found');
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
    public function quoteProducts()
    {


        $filters = (array) $this->request->get('QuoteProducts');


        $quoteProducts = $this->apiEntityManager
            ->getRepository(QuoteProduct::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($quoteProducts, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($quoteProducts, $filters['index'], $filters['size'], $total)];
    }

    /**
     * Get product devis
     *
     * @return QuoteProduct
     */
    public function getQuoteProduct($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->quoteProduct->toArray($toSnake);
        }

        return $this->quoteProduct;
    }

    /**
     * Creates a quoteProduct object
     *
     * @return array
     * @throws \Exception
     */
    public function create()
    {
        $quoteProduct = (array) $this->request->get('QuoteProduct');
        //$this->formatDatetime($quoteProduct);
        $this->findObjects($quoteProduct ,  ['vat' , 'unity', 'quote']);


        $this->vat = $quoteProduct['vat'];
        $quoteProduct['vatValue'] = $this->vat->getValue();

        $this->unity = $quoteProduct['unity'];
        $quoteProduct['unityValue'] = $this->unity->getName();


        $this->quoteProduct = $this->insertObject($quoteProduct, QuoteProduct::class);
        return ['data' => [
            'messages' => 'create_success',
            'code' => $this->quoteProduct->getCode(),
        ]];


    }

    /**
     * @return array
     * @throws \Exception
     */
    public function edit()
    {
        $quoteProduct = (array) $this->request->get('QuoteProduct');
        $this->findObjects($quoteProduct ,  ['vat' , 'unity', 'quote']);
        $this->formatDatetime($quoteProduct);
        //        $this->validateUnicity(BackGroup::class, 'code', ['code' => $groupData['code']], $this->backgroup);
        unset($quoteProduct['code']);
        $return = $this->updateObject($this->quoteProduct, $quoteProduct);
        $return['data']['object'] = $this->quoteProduct->getCode();

        return $return;
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->quoteProduct);
    }


}