<?php

namespace App\ApiModel\Unity;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of unity
 *
 * @author maria
 */
class Unities extends CommonParameterBag
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
     * @Assert\Regex("/^(name|company|created_at)/")
     */
    public $sort_column = 'created_at';

}
 