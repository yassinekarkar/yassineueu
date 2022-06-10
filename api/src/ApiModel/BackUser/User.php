<?php

namespace App\ApiModel\BackUser;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class User extends CommonParameterBag
{

    /**
     * @var string
     *
     * @Assert\Regex("/^[a-zA-Z- _]+/")
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     * @var string
     *
     * @Assert\Regex("/^[a-zA-Z- _]+/")
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @var string
     *
     * @Assert\Regex("/^\S+@\S+\.\S+$/")
     * @Assert\NotBlank()
     */
    public $mail;

    /**
     * @var string
     *
     * @Assert\Regex("/[0-9-+]*$/")
     */
    public $phone;

    /**
     * @var string
     *
     * @Assert\Regex("/^[0-9a-zA-Z-_]+$/")
     * @Assert\NotBlank()
     */
    public $group;



}
