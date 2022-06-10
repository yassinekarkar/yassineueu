<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use SSH\MsJwtBundle\Utils\MyTools;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use App\Entity\BackGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use App\Manager\BackGroupManager;
use SSH\MsJwtBundle\Annotations\Mapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/back")
 * @Security("is_granted('ROLE_BACK')")
 */
class BackGroupController extends Controller
{

    private $manager = null;

    /**
     * backGroupController constructor.
     */
    public function __construct(BackGroupManager $backGroupmanager)
    {
        $this->manager = $backGroupmanager;
    }

    /**
     * @Route("/backGroups/choices", name="ws-group-list-choices", methods={"GET"})
     * @Mapping(object="App\ApiModel\BackGroup\Groups", as="groups")
     */
    public function listChoiceAction()
    {
        return $this->manager->listChoices();
    }

    /**
     * @Route("/backGroups", name="ws-group-list",methods={"GET"})
     * @Mapping(object="App\ApiModel\BackGroup\Groups", as="groups")
     */
    public function listAction()
    {
        return $this->manager->getAll();
    }

    /**
     * @Route("/backGroup", name="ws-group-create",methods={"POST"})
     * @Mapping(object="App\ApiModel\BackGroup\Group", as="group")
     *
     * @param Request $request
     * @param STRING $exceptionManager
     */
    public function createAction()
    {
        return $this->manager->create();
    }

    /**
     * @Route("/backGroup/{code}", name="ws-group-get",methods={"GET"})
     *
     * @param Request $request
     * @param STRING $code
     */
    public function getAction($code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->getBackGroup(true);
    }

    /**
     * @Route("/backGroup/{code}", name="ws-group-set",methods={"PUT"})
     * @Mapping(object="App\ApiModel\BackGroup\Group", as="group")
     *
     * @param Request $request
     * @param STRING $code
     */
    public function setAction($code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->edit();
    }

    /**
     * @Route("/backGroup/{code}", name="ws-group-delete",methods={"DELETE"})
     *
     * @param STRING $code
     */
    public function deleteAction($code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->delete();
    }

}
