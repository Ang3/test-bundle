<?php

namespace Ang3\Bundle\TestBundle\Test;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Joanis ROUANET
 */
class ApiTestCase extends WebTestCase
{
    /**
     * @param mixed $data
     */
    public function encodeHttpQuery(string $path, $data): string
    {
        return sprintf('%s?%s', $path, http_build_query($data));
    }

    public function encodeBasicAuth(string $username, string $password = ''): string
    {
        return sprintf('Basic %s', base64_encode(sprintf('%s:%s', $username, $password)));
    }

    public function createJsonClient(array $headers = [], array $options = [], array $server = []): Client
    {
        return parent::createClient(array_merge($headers, [
            'Content-Type' => 'application/json',
        ]), $options, $server);
    }

    public function createXmlClient(array $headers = [], array $options = [], array $server = []): Client
    {
        return parent::createClient(array_merge($headers, [
            'Content-Type' => 'application/xml',
        ]), $options, $server);
    }

    public function createClient(array $headers = [], array $options = [], array $server = []): Client
    {
        foreach($headers as $name => $value) {
            if(!preg_match('#^HTTP_#', $name)) {
                $headers[$name] = sprintf('HTTP_%s', $value);
            }
        }

        return parent::createClient($options, array_merge($headers, $server));
    }
}
