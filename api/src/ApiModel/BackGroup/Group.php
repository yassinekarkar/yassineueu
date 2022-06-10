<?php

namespace App\ApiModel\BackGroup;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Group extends CommonParameterBag
{

    /**
     * @var string
     *
     * @Assert\Regex("/^[0-9a-zA-Z-_]+/")
     * @Assert\NotBlank()
     */
    public $code;

    /**
     * @var string
     *
     * @Assert\Regex("/^[0-9a-zA-Z- _]+/")
     * @Assert\NotBlank()
     */
    public $label;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $roles;

}
