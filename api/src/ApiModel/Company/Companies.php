<?php

namespace App\ApiModel\Company;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Companies
 *
 * @author mariaDebawi
 */
class Companies extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

     /**
      *  
     */
    public $name;

      /**
     *
     */
    public $registry_number;

    /**
     *
     */
    public $vat_number;
  
    /**
     * @var string
     */
    public $firstname;

    /**
     *  @var string
     */
    public $lastname;

    /**
     *  @var string
     */
    public $address;

    /**
     *  @var string
     */
    public $zipcode;

    /**
     *  @var string
     */
    public $city;

    /**
     *  @var string
     */
    public $mail;

    /**
     *  @var string
     */
    public $phone;

    /**
     *  @var string
     */
    public $paypal;

    /**
     * @var string
     */
    public $website;

  
   

    /**
     * @var string
     *
     * @Assert\Regex("/^(name|registry_number|firstname|lastname|phone|website|created_at)/")
     */
    public $sort_column = 'created_at';

}
