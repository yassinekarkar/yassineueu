<?php

namespace App\ApiModel\Currency;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Currency extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z-_]+/")
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/[A-Z]{2,5}$/",
     *     match=true,
     *     message="shortname cannot contain a number"
     * )
     */
    public $shortname;

}
