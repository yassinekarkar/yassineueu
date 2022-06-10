<?php

namespace App\ApiModel\BackUser;

use Symfony\Component\Validator\Constraints as Assert;
use SSH\MsJwtBundle\Model\Traits\ApiList;
use SSH\MsJwtBundle\Request\CommonParameterBag;

class Users extends CommonParameterBag
{

    use ApiList;

    /**
     * @var string
     *
     */
    public $firstname;

    /**
     * @var string
     *
     */
    public $lastname;

    /**
     * @var string
     *
     */
    public $mail;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $agency;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     *
     * @Assert\Regex("/^(code|firstname|lastname|login|phone|created_at)$/")
     */
    public $sort_column = 'created_at';

    /**
     * @var string
     *
     *
     */
    public $active;

}
