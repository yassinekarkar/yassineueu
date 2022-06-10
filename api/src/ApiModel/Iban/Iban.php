<?php

namespace App\ApiModel\Iban;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Iban extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $iban;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $bankName;

}
