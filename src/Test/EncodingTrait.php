<?php

namespace Ang3\Bundle\TestBundle\Test;

use RuntimeException;

/**
 * @author Joanis ROUANET
 */
trait EncodingTrait
{
	private static $serializer;

    /**
     * @param mixed $data
     * 
     * @return scalar
     */
    public function encode($data, string $format, array $context = [])
    {
        try {
        	return $this
        		->getSerializer()
        		->encode($data, $format, $context)
        	;
        } catch(Throwable $e) {
        	throw new RuntimeException(sprintf('Failed to encode content with format "%s" - %s', $format, $e->getMessage()), 0, $e);
        }
    }

    /**
     * @param mixed $data
     * 
     * @return scalar
     */
    public function decode($data, string $format, array $context = [])
    {
        try {
        	return $this
        		->getSerializer()
        		->decode($data, $format, $context)
        	;
        } catch(Throwable $e) {
        	throw new RuntimeException(sprintf('Failed to decode content with format "%s" - %s', $format, $e->getMessage()), 0, $e);
        }
    }

    public function getSerializer(): Serializer
    {
        if (null === self::$serializer) {
            self::$serializer = new Serializer();
        }

        return self::$serializer;
    }
}
