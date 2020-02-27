<?php

namespace Ang3\Bundle\TestBundle\Test;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Joanis ROUANET
 */
class WebTestCase extends BaseWebTestCase
{
	use TestCaseTrait;
}
