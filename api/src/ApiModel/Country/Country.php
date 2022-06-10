<?php

namespace App\ApiModel\Country;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Country extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $shortname;

    /**
     */
    public $flag;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $currency;

}
