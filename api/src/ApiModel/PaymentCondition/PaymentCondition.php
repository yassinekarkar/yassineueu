<?php

namespace App\ApiModel\PaymentCondition;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentCondition extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $value;

    /**
     *
     */
    public $is_default;

}
