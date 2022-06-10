<?php

namespace App\ApiModel\Company;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyEdit extends CommonParameterBag
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
     */
    public $address;

    /**
     */
    public $zipcode;

    /**
     */
    public $city;

    /**
     * @Assert\NotBlank()
     */
    public $phone;

    /**
     */
    public $paypal;

    /**
     *
     */
    public $website;

}
