<?php

namespace App\ApiModel\BackGroup;

use Symfony\Component\Validator\Constraints as Assert;
use SSH\MsJwtBundle\Model\Traits\ApiList;
use SSH\MsJwtBundle\Request\CommonParameterBag;

class Groups extends CommonParameterBag
{

    use ApiList;

    /**
     * @var string
     *
     */
    public $label;

    /**
     * @var string
     *
     */
    public $code;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     *
     * @Assert\Regex("/^(code|label|roles|created_at)$/")
     */
    public $sort_column = 'created_at';

}
