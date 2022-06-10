<?php

namespace App\ApiModel\Product;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of product
 *
 * @author yassine
 */
class Products extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;



    /**
     *
     * @var string
     */
    public $search;

    /**
     * @var string
     *
     * @Assert\Regex("/^(name|company|created_at)/")
     */
    public $sort_column = 'created_at'; // tri

}
