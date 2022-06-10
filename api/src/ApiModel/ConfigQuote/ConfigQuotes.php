<?php

namespace App\ApiModel\ConfigQuote;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of ConfigQuotes
 *
 * @author walidsaadaoui
 */
class ConfigQuotes extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

    /**
     *
     * @var string
     */
    public $search;

    /**
     * @var string
     *
     * @Assert\Regex("/^(head|total_ligne|created_at)/")
     */
    public $sort_column = 'created_at';

}
