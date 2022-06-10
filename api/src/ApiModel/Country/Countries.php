<?php

namespace App\ApiModel\Country;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Currencies
 *
 * @author walidsaadaoui
 */
class Countries extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $shortname;

    /**
     * @var string
     *
     * @Assert\Regex("/^(name|shortname|created_at)/")
     */
    public $sort_column = 'created_at';

}
