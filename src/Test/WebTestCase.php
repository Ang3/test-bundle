<?php

namespace Ang3\Bundle\TestBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @abstract
 *
 * @author Joanis ROUANET
 */
abstract class WebTestCase extends BaseWebTestCase
{
    use TestCaseTrait;
}
