<?php

namespace App\ApiModel\PaymentCondition;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of paymentConditions
 *
 * @author maria
 */
class PaymentConditions extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

    /**
     *
     * @var string
     */
    public $value;

    /**
     * @var string
     *
     * @Assert\Regex("/^(value|company|created_at)/")
     */
    public $sort_column = 'created_at';

}
