<?php

namespace App\ApiModel\Company;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class User extends CommonParameterBag
{

    /**
     *
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     *
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @Assert\NotBlank()
     */
    public $mail;

    /**
     * @Assert\NotBlank()
     */
    public $phone;

}
