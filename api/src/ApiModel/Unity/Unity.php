<?php

namespace App\ApiModel\Unity;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Unity extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

}
