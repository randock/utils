<?php

declare(strict_types=1);

namespace Randock\Utils\Http;

use GuzzleHttp\Client as Http;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Randock\Utils\Http\HMAC\HmacApiTrait;
use Randock\Utils\Http\Exception\HttpException;

/**
 * Class AbstractClient.
 */
abstract class AbstractClient
{
    use HmacApiTrait;

    /**
     * @var Http
     */
    protected $http;

    /**
     * AbstractClient constructor.
     *
     * @param string $endPoint
     * @param array  $options
     */
    public function __construct(string $endPoint, array $options = [])
    {
        $optionsDefault = ['timeout' => 10];

        $options = array_merge($optionsDefault, $options);
        $options['base_uri'] = $endPoint;

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
}
