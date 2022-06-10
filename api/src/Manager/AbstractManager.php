<?php

namespace App\Manager;

use App\Entity\Client;
use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/* * `
 * Base class for most animation objects.
 */

abstract class AbstractManager //implements ModelInterface
{

    const CLIENT_TYPE = 'client_type';
    const EBILL_TYPE = 'ebill_type';

    /**
     * Date format
     */
    const DATE_FORMAT = 'Y-m-d';

    /**
     * Date format FR
     */
    const SQL_DATE_FORMAT_FR = 'DD-MM-YYYY';

    /**
     * Date format EN
     */
    const SQL_DATE_FORMAT_EN = 'YYYY-MM-DD';

    /**
     * list client type
     */
    const PROFESSIONAL_CLIENT_TYPE = 'PROFESSIONAL';
    const PARTICULAR_CLIENT_TYPE = 'PARTICULAR';

    /**
     * ROLES FRONT
     */
    const ROLE_SUPER_USER = 'ROLE_SUPER_USER';
    const ROLE_USER = 'ROLE_USER';

    /**
     * settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * @var Registry
     */
    protected $entityManager;

    /**
     * @var ExceptionManager
     */
    protected $exceptionManager;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var entityManager
     */
    protected $apiEntityManager;

    /**
     *  @var BackUser
     */
    protected $userCaller;

    /**
     *  @var \App\Entity\PartnerUser
     */
    protected $partnerUserCaller;

    /**
     *  @var \App\Entity\Candidate
     */
    protected $candidateCaller;

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $userData = [];

    /**
     * @var object
     */
    protected $object;



    const STATUS_PAIED = 'PAIED';

    const STATUS_DRAFT = 'DRAFT';



    /**
     * AbstractModel constructor.
     *
     * @param Registry $entityManager
     * @param ExceptionManager $exceptionManager
     * @param RequestStack $requestStack
     */
    public function __construct(Registry $entityManager, ExceptionManager $exceptionManager, RequestStack $requestStack = null)
    {
        $this->entityManager = $entityManager;
        $this->apiEntityManager = $entityManager->getManager();
        $this->exceptionManager = $exceptionManager;
        $this->requestStack = $requestStack;
        if ($requestStack instanceof RequestStack) {
            $this->request = $requestStack->getCurrentRequest();
        }
    }

    protected function getDataTypeValues($var, $translator = null)
    {
        $conn = $this->apiEntityManager
                ->getConnection();

        $stmt = $conn->prepare("SELECT unnest(enum_range(NULL::$var))");
        $stmt->execute();
        $values = [];

        while ($value = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $value = current($value);
            $values[] = [
                'code' => $value,
                'label' => is_object($translator) ? $translator->trans($value) : $value,
            ];
        }

        if (is_null($translator)) {
            $values = array_column($values, 'code');
        }

        return $values;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    protected function getExceptionManager()
    {
        return $this->exceptionManager;
    }

    protected function getRequestStack()
    {
        return $this->requestStack;
    }

    protected function getSqlDateFormat()
    {
        if ($this->request->getLocale() == 'en') {
            return self::SQL_DATE_FORMAT_EN;
        }
        return self::SQL_DATE_FORMAT_FR;
    }

    protected function setSettings($settings)
    {
        $this->settings = $settings;

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($this->settings as $property => $value) {
            try {
                $accessor->setValue($this, $property, $value);
            } catch (ExceptionInterface $e) {
                throw $e;
            }
        }
        return $this;
    } 

    protected function validateUnicity($class, $field, $options, $objectCompare = null, $method = 'and')
    {
        $query = 'SELECT o FROM ' . $class . ' o WHERE ';
        foreach ($options as $option => $value) {

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }
            if ($value instanceof \App\Entity\AbstractEntity) {
                $value = $value->getId();
            }

            $query .= ' o.' . $option . " = '" . $value . "' " . $method;
        }

        $query = substr($query, 0, -3);

        $objects = $this->apiEntityManager
                ->createQuery($query)
                ->getResult();

        if ($objectCompare && $this->inCollection($objectCompare, $objects)) {
            return true;
        }

        if (!empty($objects)) {
            $this->exceptionManager->throwFoundException(is_array($field) ? $field : [$field]);
        }
        return true;
    }

    public function inCollection($objectCompare, $objects)
    {
        if (count($objects) == 1) {
            return current($objects) === $objectCompare;
        }

//        foreach ($objects as $object) {
//            if ($object === $objectCompare) {
//                return true;
//            }
//        }
        return false;
    }

    public function import($class, $field = null, $options = [], $errors = [])
    {
        $importData = $this->request->get('data');

        //        if (empty($importData)) {
        ////            $this->exceptionManager->throwPreconditionFailedException('empty_data');
        //        }

        $data = [
            'messages' => 'import_fail',
            'errors' => [],
            'values' => [],
        ];
        $identifiers = [];

        if (!empty($importData)) {
            foreach ($importData as $index => $modelData) {

                try {
                    $optionsTmp = $options;
                    if (!empty($options)) {
                        foreach ($options as $optionKey => $option) {
                            $optionsTmp[$optionKey] = $modelData[$option];
                        }
                    }

                    $identifiers[$index] = $this->insertObject($modelData, $class, $field, $optionsTmp)->getCode();
                } catch (\Exception $ex) {
                    $errors[$index] = $ex->getMessage();
                }
            }
            $data = ['messages' => 'import_success', 'values' => $identifiers];
        }

        if (!empty($errors)) {

            $data = [
                'messages' => !count($importData) || count($importData) == count($errors) ? 'import_fail' : 'import_success_partially',
                'errors' => $errors,
                'values' => $identifiers,
            ];
        }

        return ['data' => $data];
    }

    public function insertObject($data, $class, $field = null, $options = array())
    {
        if ($field && count($options)) {
            $this->validateUnicity($class, $field, $options);
        }

        $object = new $class($data);

        $this->apiEntityManager->persist($object);
        $this->apiEntityManager->flush();
        $this->apiEntityManager->refresh($object);

        return $object;
    }

    /**
     * @return array
     */    
    protected function updateObject($object, $data)
    {
        if ($object instanceof \SSH\MsJwtBundle\Entity\AbstractEntity) {

            $object->fromArray($data);

            if (method_exists($object, 'setUpdatedAt')) {
                $object->setUpdatedAt(new \DateTime());
            }

            $this->apiEntityManager->persist($object);
            $this->apiEntityManager->flush();

            return ['data' => [
                    'code' => $object->getCode(),
                    'messages' => 'update_success',
            ]];
        }

        return ['data' => [
                'messages' => 'update_fail',
        ]];
    }

    /**
     * @return array
     */
    protected function deleteObject($object)
    {
        if ($object instanceof \SSH\MsJwtBundle\Entity\AbstractEntity) {

            $this->apiEntityManager->remove($object);
            $this->apiEntityManager->flush();

            return ['data' => [
                    'code' => $object->getCode(),
                    'messages' => 'delete_success',
            ]];
        }

        return ['data' => [
                'messages' => 'delete_fail',
        ]];
    }

    /**
     * @return array
     */
    protected function setObjectState($object)
    {
        if (method_exists($object, 'setActive')) {
            $object->setActive(!$object->getActive());
        }

        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime());
        }

        $this->apiEntityManager->persist($object);
        $this->apiEntityManager->flush();

        return ['data' => [
                'messages' => 'update_success',
                'object' => $object->getCode(),
        ]];
    }

    protected function formatDatetime(&$data)
    {

        foreach ($data as $column => &$value) {
            if (in_array($column, ['dateBegin', 'dateEnd', 'begin_at', 'end_at', 'created_at', 'updated_at','invoiceDate','dueDate'])) {
                $value = new \DateTime($value);

            }
        }
    }

    public function getUserCaller()
    {
        return $this->request->get('userCaller', null);

    }

    public function checkRight($role)
    {
        if ($role != $this->request->get('userCallerRoles')) {
            $this->exceptionManager->throwAccessDeniedException('bad credential', ['usercode']);
        }
        return true;
    }

    /**
     * Check access user
     *
     * @param array $userRoles
     * @param array $roles
     * @return boolean
     */
    public function checkAccess(array $userRoles, array $roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!array_intersect($roles, $userRoles)) {
            $this->exceptionManager->throwAccessDeniedException('bad_credentials');
        }

        return true;
    }


    public function findObjects(&$data ,  $classes)
    {
        foreach ( $classes as $index) {

            $class = 'App\Entity\\' . ucfirst($index);
            if ($index == 'group') {
                $class = 'App\Entity\Back' . ucfirst($index);
            }

            $values = $data[$index];

            try {
                $data[$index] = $this->apiEntityManager
                    ->getRepository($class)
                    ->findOneBy(['code' => $values]);
            } catch (\Exception $ex) {
                $data[$index] = null;
            }

            if (!($data[$index] instanceof $class)) {
                $this->exceptionManager->throwNotFoundException('no_' . $index . '_found', [$index]);
            }
        }
    }








}
