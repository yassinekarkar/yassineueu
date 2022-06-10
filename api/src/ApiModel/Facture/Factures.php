<?php

namespace App\ApiModel\Facture;

use SSH\MsJwtBundle\Request\CommonParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of unity
 *
 * @author yassine
 */
class Factures extends CommonParameterBag
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
     * @Assert\Regex("/^(name|company|created_at)/")
     */
    public $sort_column = 'created_at'; // tri

}
