<?php

namespace App\Tests\controller;


use App\Entity\Vat;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/front")
 * @Security("is_granted('ROLE_FRONT')")
 */
class VatControllerTest extends TestCase
{

    public function testUri()
    {
        $Vat = new Vat();
        $value="0.000" ;
        $is_default = false ;
        //$createdAt ="2022-03-03 09:13:55" ;
        $Vat->setValue($value);
        $Vat->setIsDefault($is_default);
        //$Vat->setCreatedAt($createdAt);



        return $Vat;

    }

}