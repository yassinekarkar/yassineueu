<?php

namespace App\ApiModel\QuoteProduct;


use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class QuoteProduct extends CommonParameterBag
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $porder;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $unit_price;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $amount;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $discount;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $vat;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $unity;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    public $discount_fixed_value;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $quote;
}