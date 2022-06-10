<?php

namespace App\ApiModel\Language;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Language extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

}
