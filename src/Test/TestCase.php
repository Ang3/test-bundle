<?php

namespace Ang3\Bundle\TestBundle\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @abstract
 *
 * @author Joanis ROUANET
 */
abstract class TestCase extends BaseTestCase
{
    use TestCaseTrait;
}
