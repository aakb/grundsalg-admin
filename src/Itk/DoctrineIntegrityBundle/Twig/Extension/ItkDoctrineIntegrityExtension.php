<?php

namespace Itk\DoctrineIntegrityBundle\Twig\Extension;

use Itk\DoctrineIntegrityBundle\Service\IntegrityManager;


/**
 * Class TwigExtension.
 *
 * @package AdminBundle\Twig\Extension
 */
class ItkDoctrineIntegrityExtension extends \Twig_Extension
{

    /**
     * @var IntegrityManager
     */
    private $integrityManager;

    public function __construct(IntegrityManager $integrityManager)
    {
        $this->integrityManager = $integrityManager;
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return [
            new \Twig_Test('deleteable', [$this, 'canDelete']),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('get_not_deleteable_info', [$this, 'getCannotDeleteInfo'], ['is_safe' => ['all']]),
        ];
    }

    /**
     * @param $entity
     *
     * @return bool
     */
    public function canDelete($entity)
    {
        return $this->integrityManager->canDelete($entity) === true;
    }

    /**
     * @param $entity
     *
     * @return array|bool|null
     */
    public function getCannotDeleteInfo($entity)
    {
        $info = $this->integrityManager->canDelete($entity);

        return is_array($info) ? $info : null;
    }

}
