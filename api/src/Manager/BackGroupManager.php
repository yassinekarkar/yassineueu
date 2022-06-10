<?php

namespace App\Manager;

use App\Entity\BackUser;
use App\Entity\BackGroup;
use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;

class BackGroupManager extends AbstractManager
{

    /** @var BackGroup */
    private $backgroup;

    /**
     *
     * @var string
     */
    private $code;

    public function __construct(
            Registry $entityManager,
            ExceptionManager $exceptionManager,
            RequestStack $requestStack
    )
    {
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init($settings = [])
    {
        parent::setSettings($settings);

        if ($this->getCode()) {
            // find existing Group
            $this->backgroup = $this->apiEntityManager
                    ->getRepository(BackGroup::class)
                    ->findOneByCode($this->getCode());

            if (!$this->backgroup instanceof BackGroup) {
                $this->exceptionManager->throwNotFoundException('UNKNOWN_BackGroup');
            }
        }

        return $this;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return BackGroup
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

    public function getGroup()
    {
        return $this->backgroup;
    }

    public function getBackGroup($array = false)
    {
        if ($array) {
            return ['data' => $this->backgroup->toArray()];
        }

        return $this->backgroup;
    }

    public function getAll()
    {
        $filters = (array) $this->request->get('groups');
        $filters['index'] = MyTools::getOption($filters, 'index', 1);
        $filters['size'] = MyTools::getOption($filters, 'size', 10);

        $groups = $this->apiEntityManager
                ->getRepository(BackGroup::class)
                ->getByFilters($filters);

        return MyTools::jtablePaginator($groups, $filters['index'], $filters['size']);
    }

    public function listChoices()
    {
        $filters = (array) $this->request->get('groups');
        $groups = $this->apiEntityManager
                ->getRepository(BackGroup::class)
                ->getByFiltersChoices($filters);

        return MyTools::jtablePaginator($groups, $filters['index'], $filters['size']);
    }

    /**
     * Creates a backgroup object
     *
     * @return array
     * @throws \Exception
     */
    public function create()
    {
        $groupData = (array) $this->request->get('group');

        $backGroup = $this->insertObject($groupData, BackGroup::class, 'code', ['code' => $groupData['code']]);

        return ['data' => [
                'messages' => 'create_success',
                'code' => $backGroup->getCode(),
        ]];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function edit()
    {
        $groupData = (array) $this->request->get('group');

        //        $this->validateUnicity(BackGroup::class, 'code', ['code' => $groupData['code']], $this->backgroup);
        unset($groupData['code']);
        $return = $this->updateObject($this->backgroup, $groupData);
        $return['data']['object'] = $this->backgroup->getCode();

        return $return;
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->backgroup);
    }

}
