<?php

namespace App\ApiModel\Iban;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Ibans
 *
 * @author maria
 */
class Ibans extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

    /**
     *
     * @var string
     */
    public $iban;

    /**
     *
     * @var string
     */
    public $bankName;




    /**
     * @var string
     *
     * @Assert\Regex("/^(iban|bankName|created_at)/")
     */
    public $sort_column = 'created_at';

}
