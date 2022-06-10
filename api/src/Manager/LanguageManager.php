<?php

namespace App\Manager;

use SSH\MsJwtBundle\Utils\MyTools;
use SSH\MsJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Language;

/**
 * Description of LanguageManager
 *
 * @author yassinekarkar
 */
class LanguageManager extends AbstractManager
{

    /**
     *  @var string
     */
    private $code;

    /**
     *
     * @var Language
     */
    private $language;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        Registry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack
    )
    {
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    /**
     * AbstractManager initializer.
     */
    public function init($settings = [])
    {
        parent::setSettings($settings);

        $this->language = null;
        $this->userCaller = $this->request->get('userCaller');

        if ($this->getCode()) {
            // find existing currency
            $this->language = $this->apiEntityManager
                ->getRepository(Language::class)
                ->findOneByCode($this->getCode());

            if (!($this->language instanceof Language)) {
                $this->exceptionManager->throwNotFoundException('no_language_found');
            }
        }

        return $this;
    }

    /**
     * Setter  code.
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Getter code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage($toArray = false, $toSnake = true)
    {
        if ($toArray) {
            return $this->language->toArray($toSnake);
        }

        return $this->language;
    }


    /**
     * list
     *
     * @return array
     */
    public function languages()
    {

        $filters = (array) $this->request->get('Languages');



        $languages = $this->apiEntityManager
            ->getRepository(Language::class)
            ->getByFilters($filters);

        $total = MyTools::getValueFromResultSet($languages, 'tolal');

        return ['data' => MyTools::jtablePaginatorRows($languages, $filters['index'], $filters['size'], $total)];
    }



    public function listChoices()
    {
        $languages = $this->apiEntityManager
            ->getRepository(Language::class)
            ->getByFilters(['index' => -1, 'search' => $this->request->get('search')]);

        return ['data' => array_values(MyTools::getArrayFromResultSet($languages, 'code', ['code']))];
    }

    /**
     * Create a language object
     *
     * @return array
     * @throws \Exception
     */
    public function create()
    {
        $language = (array) $this->request->get('Language');
        $this->validateUnicity(Language::class, 'name', ['name' => $language['name']], $this->language);
        $this->language = $this->insertObject($language, Language::class);
        return ['data' => [
            'messages' => 'create_success',
            'code' => $this->language->getCode(),
        ]];


    }


    /**
     * @return array
     * @throws \Exception
     */
    public function edit()
    {
        $language = (array) $this->request->get('Language');

        //$this->validateUnicity(BackGroup::class, 'code', ['code' => $groupData['code']], $this->backgroup);
        unset($language['code']);
        $return = $this->updateObject($this->language, $language);
        $return['data']['object'] = $this->language->getCode();

        return $return;
    }


    /**
     * @return array
     */
    public function delete()
    {
        return $this->deleteObject($this->language);
    }
}
