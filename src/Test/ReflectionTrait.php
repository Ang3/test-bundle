<?php

namespace Ang3\Bundle\TestBundle\Test;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @author Joanis ROUANET
 */
trait ReflectionTrait
{
    /**
     * @throws InvalidArgumentException when the class was not found
     */
    public function newInstanceWithoutConstructor(string $class): object
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('The class "%s" was not found', $class));
        }

        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * @param mixed $value
     *
     * @throws ReflectionException On reflection failure
     */
    public function setObjectProperty(object $object, string $propertyName, $value): void
    {
        $property = $this->getReflectionProperty($object, $propertyName, true);

        $property->isStatic() ? $property->setValue($value) : $property->setValue($object, $value);
    }

    /**
     * @throws ReflectionException On reflection failure
     *
     * @return mixed
     */
    public function getObjectProperty(object $object, string $propertyName)
    {
        $property = $this->getReflectionProperty($object, $propertyName, true);

        return $property->isStatic() ? $property->getValue() : $property->getValue($object);
    }

    /**
     * @throws ReflectionException On reflection failure
     *
     * @return mixed
     */
    public function invokeMethod(object $object, string $method, array $args = [])
    {
        return $this
            ->getReflectionMethod($object, $method, true)
            ->invokeArgs($object, $args)
        ;
    }

    /**
     * @param mixed $class
     *
     * @throws ReflectionException On reflection failure
     */
    public function getReflectionProperty($class, string $propertyName, bool $accessible = true): ReflectionProperty
    {
        $reflectionProperty = new ReflectionProperty($class, $propertyName);

        if (true === $accessible) {
            $reflectionProperty->setAccessible(true);
        }

        return $reflectionProperty;
    }

    /**
     * @param mixed $class
     *
     * @throws ReflectionException On reflection failure
     */
    public function getReflectionMethod($class, string $method, bool $accessible = true): ReflectionMethod
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        if (true === $accessible) {
            $reflectionMethod->setAccessible(true);
        }

        return $reflectionMethod;
    }
}
