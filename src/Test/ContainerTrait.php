<?php

namespace Ang3\Bundle\TestBundle\Test;

use LogicException;

/**
 * @author Joanis ROUANET
 */
trait ContainerTrait
{
    public function getContainerService(string $name)
    {
        return $this
            ->getContainer()
            ->get($name)
        ;
    }

    public function getContainer(): ContainerInterface
    {
        // Si on a pas de container
        if (null === ($container = self::$kernel->getContainer())) {
            throw new LogicException('You must boot the kernel to create the container by calling method "self::bootKernel()"');
        }

        return $container;
    }
}
