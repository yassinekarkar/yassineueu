<?php

namespace App\ApiModel\Quote;


use App\Entity\QuoteProduct;
use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Quote extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $estimateNumber;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $status;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $preNote;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $postNote;


    /**
     * @var \DateTime
     * @Assert\NotBlank()
     */
    public $dateBegin;


    /**
     * @var \DateTime
     * @Assert\NotBlank()
     */
    public $dateEnd;



    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $client;


 /*   /**
     * @var string
     * @Assert\NotBlank()
     */
 /*   public $creator;
    */

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $head;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $discount;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $discount_on_total;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $discount_fixed_value;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $discount_base_ttc;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $discountTotal;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $language;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $currency;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $products;




















    /* /**
      * @var string
      * @Assert\NotBlank()
      */
   /*public $updator;*/

    /* /**
     * @var string
     * @Assert\NotBlank()
      */
     /*public $discount;*/





}