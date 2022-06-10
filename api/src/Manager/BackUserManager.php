<?php

namespace App\Manager;

use App\Entity\Agency;
use App\Entity\BackUser;
use App\Entity\BackGroup;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Manager\BackGroupManager;
use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\MailManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BackUserManager
 *
 * backUser Action
 * @package App\Manager
 */
class BackUserManager extends AbstractManager
{

    /**
     * @var code
     */
    private $code;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $backHost;

    /**
     * @var BackUser
     */
    private $backuser;

    /**
     * @var MailManager
     */
    private $mailManager;

    public function __construct(
            Registry $entityManager,
            ExceptionManager $exceptionManager,
            RequestStack $requestStack,
            MailManager $mailManager,
            $backHost
    )
    {
        $this->backHost = $backHost;
        $this->mailManager = $mailManager;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init($settings = [])
    {
        parent::setSettings($settings);

        $this->backuser = null;

        $this->backuserCaller = $this->request->get('userCaller');

        if ($this->getCode()) {
// find existing user
            $this->backuser = $this->apiEntityManager
                    ->getRepository(BackUser::class)
                    ->findOneBy(['code' => $this->getCode()]);

            if (!($this->backuser instanceof BackUser)) {
                $this->exceptionManager->throwNotFoundException('no_user_found');
            }
        }

        if (!$this->backuser && $this->getLogin()) {
// find existing user
            $this->backuser = $this->apiEntityManager
                    ->getRepository(BackUser::class)
                    ->findOneBy(['login' => $this->getLogin()]);
        }

        return $this;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return BackUser
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Setter login.
     *
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Getter login.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /* public function getUser()
      {
      return $this->backuser;
      } */

    public function getUser($array = false)
    {
        if ($array) {
            return ['data' => $this->backuser->toArray()];
        }

        return $this->backuser;
    }

    public function getBackuser($array = false)
    {
        if ($array) {
            return ['data' => $this->backuser->toArray()];
        }

        return $this->backuser;
    }

    /**
     * List back users for back office
     *
     * @param Request $request
     * @return type
     */
    public function paginatedlist()
    {
        $filters = (array) $this->request->get('Users');

        $backusers = $this->apiEntityManager
                ->getRepository(BackUser::class)
                ->getByFilters($filters);

        return MyTools::jtablePaginator($backusers, $filters['index'], $filters['size']);
    }

    /**
     * Create back user
     *
     * @param Request $request
     * @return array
     */
    public function create()
    {
        $data = (array) $this->request->get('User');

        $this->validateUnicity(BackUser::class, 'mail', ['mail' => $data['mail']]);

        $password = MyTools::getPassword();

        $data['password'] = hash('sha512', $password);

        $this->findObjects($data, ['group']);

        $this->backuser = $this->insertObject($data, BackUser::class);
        if ($this->backuser->getMail()) {
            try {
                $mailData = [
                    'mail' => $this->backuser->getMail(),
                    'password' => $password,
                    'host' => $this->backHost,
                    'need_approve' => false
                ];

                $sended = $this->mailManager->sendMail($this->backuser->getMail(), 'new_teamuser_subject', $mailData, 'Mail/parametreConnexion.html.twig');
            } catch (\Exception $ex) {
//                dd($ex->getMessage());
//                $this->exceptionManager->throwOtherException('mail_not_sended');
            }
        }
        return ['data' => [
                'messages' => 'create_success',
                'code' => $this->backuser->getCode(),
        ]];
    }


    /**
     * Update
     *
     *
     * @return array
     */
    public function set()
    {
        $data = (array) $this->request->get('User');

        $this->validateUnicity(BackUser::class, 'mail', ['mail' => $data['mail']], $this->backuser);

        $this->findObjects($data , ['group']);

        $this->updateObject($this->backuser, $data);

        return ['data' => [
                'messages' => 'update_success',
                'code' => $this->backuser->getCode(),
        ]];
    }

    /**
     * Delete user
     *
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->backuser);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @return array
     *
     */
    public function resetPassword()
    {
        $password = MyTools::getPassword();

        $this->updateObject($this->backuser, ['password' => hash('sha512', $password)]);

        if ($this->backuser->getMail()) {
            try {
                $mailData = [
                    'mail' => $this->backuser->getMail(),
                    'password' => $password,
                    'host' => $this->backHost,
                ];

                $sended = $this->mailManager->sendMail($this->backuser->getMail(), 'new_password', $mailData, 'Mail/parametreConnexion.html.twig');
            } catch (\Exception $ex) {
                $this->exceptionManager->throwOtherException('mail_not_sended');
            }
        }

        return ['data' => [
                'messages' => 'update_success',
                'code' => $this->backuser->getCode(),
        ]];
    }

    /**
     * setState
     *
     *
     * @return array
     */
    public function setState()
    {
        return $this->setObjectState($this->backuser);
    }

}
