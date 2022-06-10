<?php

namespace App\EventListener;

use App\Manager\CompanyManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use App\Entity\BackUser;
use App\Entity\Comapny;
use App\Entity\User;

/**
 * Caller Listener.
 */
class CallerListener
{

    /**
     * @var ExceptionManager
     */
    private $exceptionManager;

    /**
     * @var apiEntityManager
     */
    private $apiEntityManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    private $tokenStorage;

    /**
     *
     * @var type
     */
    private $unchekedRoutes = [
//        'front-list-countries-choices',
//        'front-validate-subscription',
//        'front-create-company',
        'jwt_token_authenticate',
        'jwt_loginpassword_authenticate',
        'home',
        'nelmio_api_doc.swagger_ui',
    ];

    /**
     * Constructor.
     *
     * @param ModelFactory $modelFactory
     */
    public function __construct(Registry $entityManager, ExceptionManager $exceptionManager, TokenStorageInterface $tokenStorage, CompanyManager  $companyManager)
    {
        $this->apiEntityManager = $entityManager;
        $this->exceptionManager = $exceptionManager;
        $this->tokenStorage = $tokenStorage;
        $this->companyManager = $companyManager;
    }

    /**
     * On kernet request call object manager.
     */
    public function onKernelRequest(ControllerEvent $event)
    {


        $request = $event->getRequest();
        $route = $request->get('_route');

        if (
                $this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                !in_array($route, $this->unchekedRoutes)
        ) {
            $wsUser = $this->tokenStorage->getToken()->getUser();

            if (is_object($wsUser)) {
                if (in_array('ROLE_BACK', $wsUser->getRoles())) {
                    $user = $this->apiEntityManager
                            ->getRepository(BackUser::class)
                            ->findOneByMail($wsUser->getUsername());
//                dd($user->getGroup());
                    if (!$user->getGroup()) {
                        $this->exceptionManager->throwAccessDeniedException();
                    }

                    $request->attributes->set('userCaller', $user);
                }

                if (in_array('ROLE_FRONT', $wsUser->getRoles())) {

                    if (is_a($wsUser, \SSH\MsJwtBundle\Entity\ApiUser::class) && $wsUser->getUsername()) {

                        $companyuser = $this->apiEntityManager
                                ->getRepository(User::class)
                                ->findOneByMail($wsUser->getUsername());

                        if (!$companyuser instanceof User) {
                            $this->exceptionManager->throwAccessDeniedException();
                        }

                        $request->attributes->set('companyUserCaller', $companyuser);

                       /* $informations = $this->companyManager
                            ->init(['code' => $wsUser->getCode()])
                            ->getInformations();

                        if (!$informations instanceof User) {
                            $this->exceptionManager->throwAccessDeniedException();
                        }

                        $request->attributes->set('companyUserCaller', $informations);*/

                    }
                }
            }
        }
    }

}
