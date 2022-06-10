<?php

namespace App\ApiModel\Language;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Languages
 *
 * @author walidsaadaoui
 */
class Languages extends CommonParameterBag
{

    use \SSH\MsJwtBundle\Model\Traits\ApiList;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     *
     * @Assert\Regex("/^(name|created_at)/")
     */
    public $sort_column = 'created_at';

}
