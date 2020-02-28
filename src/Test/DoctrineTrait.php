<?php

namespace Ang3\Bundle\TestBundle\Test;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use LogicException;
use RuntimeException;

/**
 * @author Joanis ROUANET
 */
trait DoctrineTrait
{
    use ContainerTrait;
    use RefreshDatabaseTrait;

    /**
     * Doctrine registry.
     *
     * @var Registry|null
     */
    protected $doctrine;

    public function getEntityManager($managerName): void
    {
        /**
         * @var EntityManagerInterface
         */
        $entityManager = $this
            ->getDoctrine()
            ->getManager($managerName)
        ;

        // Hydratation
        $this->entityManager = $entityManager;
    }

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        if (null === $this->doctrine) {
            if (!class_exists(Registry::class)) {
                throw new LogicException(sprintf('The class "%s" was not found - Did you forget to install the package "doctrine/orm"?', $registryClass));
            }

            $doctrine = $this->getContainerService('doctrine');

            if (!($doctrine instanceof Registry)) {
                throw new RuntimeException('Doctrine service not found in the container');
            }

            // Hydratation de Doctrine
            $this->doctrine = $doctrine;
        }

        return $this->doctrine;
    }
}
