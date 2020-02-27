<?php

namespace Ang3\Bundle\TestBundle\Test;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Joanis ROUANET
 */
trait AssertionsTrait
{
    /**
     * @param array|scalar $methods
     *
     * @throws InvalidArgumentException When a method is not a string
     */
    public function assertHttpBadMethodCalls(Client $client, string $path, $methods): Client
    {
        $methods = array_filter((array) $methods);

        foreach ($methods as $method) {
            if (!is_string($method)) {
                throw new InvalidArgumentException(sprintf('Expected method name of type "string", "%s" given', gettype($method)));
            }

            $client->request($method, $path);

            $this->assertNotAllowedResponse($client->getResponse());
        }

        return $client;
    }

    public function assertResponseOk(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_OK, $response);
    }

    public function assertResponseCreated(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_CREATED, $response);
    }

    public function assertResponseAccepted(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_ACCEPTED, $response);
    }

    public function assertEmptyResponse(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $response);
        $this->assertEquals(null, $response->getContent());
    }

    public function assertUnauthorizedResponse(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED, $response);
    }

    public function assertPaymentRequired(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_PAYMENT_REQUIRED, $response);
    }

    public function assertForbiddenResponse(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_FORBIDDEN, $response);
    }

    public function assertNotAllowedResponse(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_METHOD_NOT_ALLOWED, $response);
    }

    public function assertBadRequestResponse(Response $response): void
    {
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $response);
    }

    public function assertRedirectResponse(Response $response): void
    {
        $this->assertTrue($response->isRedirect(), sprintf('Expected HTTP redirect response, HTTP code "%d" received', $response->getStatusCode()));
    }

    public function assertStatusCode(int $statusCode, Response $response): void
    {
        $this->assertEquals($statusCode, $response->getStatusCode());

        if ($statusCode !== $response->getStatusCode()) {
            $this->fail(sprintf('Expected response status code %d, %d received', $statusCode, $response->getStatusCode()));
        }
    }

    public function assertArrayHasNotKey(string $key, array $data = []): void
    {
        if (array_key_exists($key, $data)) {
            $this->fail(sprintf('Failed to assert that key "%s" is NOT in %s', $key, var_export($data, true)));
        }

        $this->assertTrue(true);
    }

    public function assertIsDateAsString(string $value, string $timezone = null, array $context = []): void
    {
        // Création de la date depuis la valeur
        $date = date_create($value);

        // Définition du chemin éventuelle de la donnée testée
        $fieldPathMessage = !empty($context['path']) ? sprintf(' for field "%s"', $context['path']) : '';

        // Assertion: la date est valide
        $this->assertNotFalse($date, sprintf('The value "%s" is not a valid date%s.', $value, $fieldPathMessage));

        // Si on a une date
        if ($date) {
            // Si on a un timezone spécifique à tester
            if (null !== $timezone) {
                // On met la date sur le fuseau horaire à tester
                $dateInTimezone = (new DateTime())->setTimezone(new DateTimeZone($timezone));

                // Si le fuseau horaire ne correspond pas
                $this->assertTrue($timezone !== $date->getTimezone()->getName() && $dateInTimezone->format('P') !== $date->format('P'),
                    sprintf('Expected timezone "%s" or "%s", "%s" given%s.',
                        $timezone, $dateInTimezone->format('P'),
                        $date->getTimezone()->getName(),
                        $fieldPathMessage
                    )
                );
            }
        }
    }
}
