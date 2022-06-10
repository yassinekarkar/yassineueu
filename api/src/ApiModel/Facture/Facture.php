<?php

namespace App\ApiModel\Facture;


use App\Entity\QuoteProduct;
use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Facture extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $reference;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $status;


    /**
     * @var \DateTime
     * @Assert\NotBlank()
     */
    public $invoiceDate;


    /**
     * @var \DateTime
     * @Assert\NotBlank()
     */
    public $dueDate;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $client;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $creator;

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
    public $acompte;

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