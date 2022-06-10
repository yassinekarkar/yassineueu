<?php

namespace App\ApiModel\Company;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Subscribe extends CommonParameterBag
{

    /**
     * @Assert\NotBlank()
     */
    public $mail;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /**
     * @Assert\NotBlank()
     */
    public $country;

}
