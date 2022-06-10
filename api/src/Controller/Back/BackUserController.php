<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Operation;
use SSH\MsJwtBundle\Annotations\Mapping;
use Swagger\Annotations as SWG;
use App\Entity\BackUser;
use App\Entity\Depository;
use App\Entity\BackGroup;
use App\Entity\BackUserGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use App\Manager\BackUserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/back")
 * @Security("is_granted('ROLE_BACK')")
 */
class BackUserController extends Controller
{

    private $manager = null;

    /**
     * ProfilController constructor.
     */
    public function __construct(BackUserManager $backUserManager)
    {
        $this->manager = $backUserManager;
    }

    /**
     * @Route("/backusers", name="ws-backbackuser-list", methods={"GET"})
     * @Mapping(object="App\ApiModel\BackUser\Users", as="Users")
     *
     */
    public function listAction()
    {
        return $this->manager
                        ->paginatedlist();
    }

    /**
     * @Route("/backuser", name="ws-backuser-create", methods={"GET"}, methods={"POST"})
     * @Mapping(object="App\ApiModel\BackUser\User", as="User")
     *
     * @param Request $request
     * @param STRING $firstname
     */
    public function createAction(Request $request)
    {
        return $this->manager
                        ->create($request);
    }

    /**
     * @Route("/backuser/{code}", name="ws-backuser-get", methods={"GET"})
     *
     * @param Request $request
     * @param STRING $label
     */
    public function getAction($code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->getUser(true);
    }

    /**
     * @Route("/backuser/{code}", name="ws-backuser-set-password", methods={"PATCH"})
     *
     * @param Request $request
     * @param STRING $code
     */
    public function resetPasswordAction(Request $request, $code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->resetPassword($request);
    }

    /**
     * @Route("/backuser/{code}", name="ws-backuser-set", methods={"PUT"})
     * @Mapping(object="App\ApiModel\BackUser\User", as="User")
     *
     * @param Request $request
     * @param STRING $code
     */
    public function setAction(Request $request, $code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->set($request);
    }

    /**
     * @Route("/backuser/{code}/state", name="ws-backbackuser-delete", methods={"PATCH"})
     *
     * @param Request $request
     * @param STRING $type
     */
    public function setStateAction($code)
    {
        return $this->manager
                        ->init(['code' => $code])
                        ->setState();
    }

//
//    /**
//     * @Route("/backuser/{code}", name="ws-backuser-delete", methods={"DELETE"})
//     *
//     * @param STRING $code
//     */
//    public function deleteAction($code)
//    {
//        return $this->manager
//                        ->init(['code' => $code])
//                        ->delete();
//    }
}
