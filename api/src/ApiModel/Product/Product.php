<?php

namespace App\ApiModel\Product;

use App\Entity\Company;
use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

class Product extends CommonParameterBag
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
    public $description;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $unit_price;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $vat;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $unity;







}
