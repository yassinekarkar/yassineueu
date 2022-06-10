<?php

namespace App\Manager;

use App\Entity\Company;
use SSH\MsJwtBundle\Utils\MyTools;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of ProductManager
 *
 * @author yassineKarkar
 */
class ProductManager extends AbstractManager
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
     * @var Product
     */
    private $product;

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
        $this->product = null;
        $this->userCaller = $this->request->get('userCaller');
        $this->companyUserCaller = $this->request->get('companyUserCaller');

        if ($this->companyUserCaller instanceof User) {
            $this->company = $this->companyUserCaller->getCompany();
        }

        if ($this->getCode()) {
            // find existing job_type
            $this->product = $this->apiEntityManager
                    ->getRepository(Product::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->product instanceof Product)) {
                $this->exceptionManager->throwNotFoundException('no_product_found');
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
     * Get getProduct
     *
     * @return Product
     */
    public function getProduct($toArray = false, $toSnake = true)
    {
        $poucentage = '%';
        $data = $this->product->toArray(true);
        $data['unityName'] = $this->product->getUnity()->getName();
       $data['vatValue'] = $this->product->getVat()->getValue().' '. $poucentage;



        return ['product' => $data];
    }

    /**
     * list
     *
     * @return array
     */
    public function products()
    {

        $filters = (array) $this->request->get('Products');

        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $products = $this->apiEntityManager
                ->getRepository(Product::class)
                ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($products, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($products, $filters['index'], $filters['size'], $total)];
    }

    public function productsChoice()
    {
        $filters = ['index' => -1, 'search' => $this->request->get('search')];
        if ($this->company instanceof Company) {
            $filters['company'] = $this->company->getId();
        }

        $products = $this->apiEntityManager
                ->getRepository(Product::class)
                ->getByFilters($filters);

        return ['data' => array_values(MyTools::getArrayFromResultSet($products, 'code', ['code', 'name']))];
    }

     /**
      * Update
     *
     *
     * @return array
      */
    public function set()
     {
         $product = (array) $this->request->get('Product');

         $this->validateUnicity(Product::class, 'name', ['name' => $product['name']], $this->product);
         $this->findObjects($product ,  ['vat' , 'unity']);
         $product['company'] = $this->company;
//dd($this->company);
         if (is_a($this->product, Product::class)) {
            return $this->updateObject($this->product, $product);
         }

         $this->product = $this->insertObject($product, Product::class);
         return ['data' => [
                'messages' => 'create_success',
                 'code' => $this->product->getCode(),
         ]];
     }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->product);
    }


}
