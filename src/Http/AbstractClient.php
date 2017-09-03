<?php

declare(strict_types=1);

namespace Randock\Utils\Http;

use GuzzleHttp\Client as Http;
use Psr\Http\Message\ResponseInterface;
use Randock\Utils\Http\HMAC\HmacApiTrait;
use GuzzleHttp\Exception\RequestException;
use Randock\Utils\Http\Exception\HttpException;
use Randock\Utils\Http\Exception\FormErrorsException;
use Randock\Utils\Http\Definition\CredentialsProviderInterface;

/**
 * Class AbstractClient.
 */
abstract class AbstractClient
{
    use HmacApiTrait;

    /**
     * @var CredentialsProviderInterface
     */
    private $credentialsProvider = null;

    /**
     * @var Http
     */
    protected $http;

    /**
     * AbstractClient constructor.
     *
     * @param string $endpoint
     * @param array  $options
     */
    public function __construct(string $endpoint, array $options = [])
    {
        $optionsDefault = ['timeout' => 10];

        $options = array_merge($optionsDefault, $options);
        $options['base_uri'] = $endpoint;

        $this->http = new Http(
            $options
        );
    }

    /**
     * Convert res. string into assoc array.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function parseContentToArray(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function request(string $method, string $path, array $options = []): ResponseInterface
    {
        try {

            // check if we need to get credentials from a provider
            if (null !== $this->credentialsProvider) {
                $options['auth'] = $this->credentialsProvider->getCredentials();
            }

            $response = $this->http->request($method, $path, $options);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
                // decoded
                $responseBody = $response->getBody()->getContents();
                $json = json_decode($responseBody);

                // Forward account response
                throw new HttpException(
                    $response->getStatusCode(),
                    $response->getBody()->getContents(),
                    ($json !== null && isset($json->message)) ? $json->message : null
                );
            }
        } catch (RequestException $exception) {
            if ($exception->getResponse()) {
                throw new HttpException(
                    $exception->getResponse()->getStatusCode(),
                    $exception->getResponse()->getBody()->getContents(),
                    $exception->getMessage()
                );
            }

            throw new HttpException(
                500,
                $exception->getMessage()
            );
        }

        return $response;
    }

    /**
     * @param HttpException $exception
     *
     * @throws FormErrorsException
     */
    protected function throwFormErrorsException(HttpException $exception): void
    {
        // decode body
        $response = json_decode($exception->getBody());

        // check if it is a general error or forms
        if (isset($response->children)) {
            $errors = [];
            foreach ($response->children as $fieldName => $fieldData) {
                if (isset($fieldData->errors)) {
                    foreach ($fieldData->errors as $fieldError) {
                        $error = new \stdClass();
                        $error->field = $fieldName;
                        $error->message = $fieldError;

                        $errors[] = $error;
                    }
                }
            }

            // parse body
            throw new FormErrorsException($errors);
        } elseif (isset($response->message)) {
            $error = new \stdClass();
            $error->field = '';
            $error->message = $response->message;

            // an generic msg to be rendered on top of the form
            throw new FormErrorsException([
                $error,
            ]);
        }
    }

    /**
     * @param CredentialsProviderInterface $credentialsProvider
     *
     * @return AbstractClient
     */
    public function setCredentialsProvider(
        CredentialsProviderInterface $credentialsProvider
    ): AbstractClient {
        $this->credentialsProvider = $credentialsProvider;

        return $this;
    }

}
