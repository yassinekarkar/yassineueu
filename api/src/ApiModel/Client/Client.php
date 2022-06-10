<?php

namespace App\ApiModel\Client;


use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Client extends CommonParameterBag
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $type;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $firstname;



    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $registryNumber;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $vatNumber;

    /**
     * @var string
     * @Assert\Regex("/^[a-zA-Z- _]+/")
     */
    public $address;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $zipcode;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $city;

    /**
     * @var string
     * @Assert\Regex("/^[a-zA-Z- _]+/")
     */
    public $mail;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $phone;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $country;

    /**
     * @var string
     */
    public $paymentCondition;




}