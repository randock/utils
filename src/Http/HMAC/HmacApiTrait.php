<?php

declare(strict_types=1);

namespace Randock\Utils\Http\HMAC;

use UMA\Psr7Hmac\Signer;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait HmacApiTrait
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $sharedSecret;

    /**
     * @param $method
     * @param $path
     * @param null  $body
     * @param array $headers
     *
     * @return ResponseInterface
     */
    public function sendAuthenticatedRequest($method, $path, $body = null, $headers = [])
    {
        $uri = sprintf('%s%s', $this->http->getConfig('base_uri'), $path);

        return $this->doPsr7Request($method, $uri, $body, $headers);
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getSharedSecret(): string
    {
        return $this->sharedSecret;
    }

    /**
     * @param string $sharedSecret
     */
    public function setSharedSecret(string $sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
    }

    /**
     * @param $method
     * @param $path
     * @param null  $body
     * @param array $headers
     *
     * @return ResponseInterface
     */
    private function doPsr7Request($method, $path, $body = null, array $headers = [])
    {
        $headers['Api-Key'] = $this->apiKey;
        $headers = array_merge($this->http->getConfig('headers'), $headers);

        if (in_array($method, [SymfonyRequest::METHOD_PATCH, SymfonyRequest::METHOD_POST])) {
            $body = json_encode($body);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException('Bad POST params');
            }
        }

        /** @var MessageInterface $signedRequest */
        $signer = new Signer($this->sharedSecret);
        $signedRequest = $signer->sign(
            new Request($method, $path, $headers, $body)
        );

        // cast to make phpstan work
        // @todo: make it a real RequestInterface

        /** @var RequestInterface $signedRequest */
        $signedRequest = (function ($cast): RequestInterface {
            return $cast;
        })($signedRequest);

        return $this->http->send($signedRequest);
    }
}
