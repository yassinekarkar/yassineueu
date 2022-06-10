<?php

namespace App\ApiModel\Vat;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Vat extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $value;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $isDefault;
}
