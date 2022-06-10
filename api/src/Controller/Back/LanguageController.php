<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/back")
 */
class LanguageController extends AbstractController
{

    /**
     *
     * @var \App\Manager\LanguageManager
     */
    private $manager;

    public function __construct(\App\Manager\LanguageManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/languages", name="back-list-languges", methods={"GET"})
     * @Mapping(object="App\ApiModel\Language\Languages", as="Languages")
     */
    public function languages(): Array
    {
        return $this->manager->languages();
    }

    /**
     * @Route("/languges/choices", name="back-list-languges-choices", methods={"GET"})
     */
    public function langugesChoice(): Array
    {
        return $this->manager->listLanguges();
    }

    /**
     * @Route("/language/{code}", name="back-get-languge", methods={"GET"})
     */
    public function getOne($code): Array
    {
        return ['data' => $this->manager
            ->init(['code' => $code])
            ->getLanguage(true)
        ];
    }

    /**
     * @Route("/language", name="back-create-language", methods={"POST"})
     * @Mapping(object="App\ApiModel\Language\Language", as="Language")
     * @Security("is_granted('ROLE_BACK')")
     */
    public function create(): Array
    {
        return $this->manager
            ->create();
    }

    /**
     * @Route("/language/{code}", name="back-set-language", methods={"PUT"})
     * @Mapping(object="App\ApiModel\Language\Language", as="Language")
     * @Security("is_granted('ROLE_BACK')")
     */
    public function set($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->edit();
    }


    /**
     * @Route("/language/{code}", name="front-delete-language", methods={"DELETE"})
     * @Mapping(object="App\ApiModel\Language\Language", as="Language")
     */
    public function delete($code): Array
    {
        return $this->manager
            ->init(['code' => $code])
            ->delete();
    }

}
