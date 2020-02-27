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
 * @abstract
 *
 * @author Joanis ROUANET
 */
class WebTestCase extends BaseWebTestCase
{
    use AssertionsTrait;
    use EncodingTrait;
    use ReflectionTrait;
    use RefreshDatabaseTrait;
    use ValidationTrait;

    /**
     * Doctrine registry.
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Doctrine default entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected static $credentials = [
        'admin' => 'azertyuiop',
        'user' => 'qsdfghjklm',
    ];

    public function setUp(): void
    {
        // Démarrage de base
        parent::setUp();

        // Démarrage du kernel
        self::bootKernel();

        // Si on a un container
        if (null !== ($container = self::$kernel->getContainer())) {
            // Récupération du registre Doctrine
            $doctrine = $container->get('doctrine');

            if (!($doctrine instanceof Registry)) {
                throw new RuntimeException('Doctrine service not found');
            }

            // Hydratation de Doctrine
            $this->doctrine = $doctrine;

            /**
             * @var EntityManagerInterface
             */
            $entityManager = $this->doctrine->getManager();

            // Hydratation
            $this->entityManager = $entityManager;
        }
    }

    public function tearDown(): void
    {
    }

    /**
     * @throws InvalidArgumentException when a parameter or options are not valid
     */
    public function request(string $method, string $path, array $data = [], array $options = []): Client
    {
        // Récupération des options du client
        list($clientOptions, $clientServer) = [
            !empty($options['client_options']) ? (array) $options['client_options'] : [],
            !empty($options['client_server']) ? (array) $options['client_server'] : [],
        ];

        // Création du client
        $client = !empty($options['auth']) ?
            $this->createClientFor((string) $options['auth'], $clientOptions, $clientServer)
            : $this->createClient($clientOptions, $clientServer)
        ;

        // Si la méthode est GET
        if ('GET' === $method) {
            // Si on a des données
            if ($data) {
                // Mise-à-jour du chemin avec les paramètres
                $path = sprintf('%s?%s', $path, http_build_query($data));

                // Réinitialisation des données
                $data = [];
            }
        }

        // Si on a des données
        if (null !== $data) {
            // Encodage des données
            $data = json_encode($data);

            if (false === $data || $error = json_last_error()) {
                throw new InvalidArgumentException('Failed to encode request data');
            }
        }

        // On effectue la requête via le client
        $client->request(
            $method,
            $path,
            !empty($options['parameters']) ? (array) $options['parameters'] : [],
            !empty($options['files']) ? (array) $options['files'] : [],
            !empty($options['server']) ? (array) $options['server'] : [],
            $data
        );

        // Retour de la réponse
        return $client;
    }

    public function decodeJsonResponseAsArray(Response $response): array
    {
        return (array) $this->decodeJsonResponse($response, true);
    }

    /**
     * @return mixed
     */
    public function decodeJsonResponse(Response $response, bool $asArray = false)
    {
        return $this->decodeJsonString($response->getContent() ?: '{}', $asArray);
    }

    public function createAdminClient(array $options = [], array $server = []): Client
    {
        return $this->createAuthenticatedClient('admin', self::$credentials['admin'], $options, $server);
    }

    public function createUserClient(array $options = [], array $server = []): Client
    {
        return $this->createAuthenticatedClient('user', self::$credentials['admin'], $options, $server);
    }

    /**
     * @throws LogicException when no credentials found for the given username
     */
    public function createClientFor(string $username, array $options = [], array $server = []): Client
    {
        if (!$this->hasCredentialsFor($username)) {
            throw new LogicException(sprintf('No credentials found for user "%s"', $username));
        }

        return $this->createAuthenticatedClient($username, self::$credentials[$username], $options, $server);
    }

    public function hasCredentialsFor(string $username): bool
    {
        return array_key_exists($username, self::$credentials);
    }

    public static function createAuthenticatedClient(string $username, string $apiKey, array $options = [], array $server = []): Client
    {
        return static::createClient($options, array_merge([
            'HTTP_Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $username, $apiKey))),
        ], $server));
    }

    public static function createClient(array $options = [], array $server = []): Client
    {
        $client = parent::createClient($options, array_merge([
            'HTTP_Content-Type' => 'application/json',
        ], $server));

        $client->followRedirects(true);

        return $client;
    }
}
