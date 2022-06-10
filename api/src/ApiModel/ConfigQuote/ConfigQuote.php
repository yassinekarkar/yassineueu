<?php

namespace App\ApiModel\ConfigQuote;

use App\Entity\Company;
use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigQuote extends CommonParameterBag
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $head;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $total_line;

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
     * @var string
     * @Assert\NotBlank()
     */
    public $discount_base_ttc;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $quote;

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


}