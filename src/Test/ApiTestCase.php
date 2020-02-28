<?php

namespace Ang3\Bundle\TestBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @abstract
 *
 * @author Joanis ROUANET
 */
abstract class ApiTestCase extends WebTestCase
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

    public static function createJsonClient(array $headers = [], array $options = [], array $server = []): Client
    {
        return self::createClient(array_merge($headers, [
            'Content-Type' => 'application/json',
        ]), $options, $server);
    }

    public static function createXmlClient(array $headers = [], array $options = [], array $server = []): Client
    {
        return self::createClient(array_merge($headers, [
            'Content-Type' => 'application/xml',
        ]), $options, $server);
    }

    public static function createClient(array $headers = [], array $options = [], array $server = []): Client
    {
        foreach ($headers as $name => $value) {
            if (!preg_match('#^HTTP_#', $name)) {
                $headers[$name] = sprintf('HTTP_%s', $value);
            }
        }

        return parent::createClient($options, array_merge($headers, $server));
    }
}
