<?php

namespace App\ApiModel\Company;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Company extends CommonParameterBag
{

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     *
     * @Assert\NotBlank()
     */
    public $registry_number;

    /**
     *
     * @Assert\NotBlank()
     */
    public $vat_number;

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
    public $mail;

    /**
     * @Assert\NotBlank()
     */
    public $password;

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

    /**
     * @Assert\NotBlank()
     */
    public $country;

}
