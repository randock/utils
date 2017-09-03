<?php

declare(strict_types=1);

namespace Tests\Randock\Utils\Http;

use Symfony\Component\HttpFoundation\Response;
use Randock\Utils\Http\AbstractClient;

class AbstractClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRequest()
    {
        $client = self::newClient();

        $response = $client->getGoogleMapsEndPoint();
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());

        $response = $client->parseContentToArray($response);
        self::assertNull($response);
    }

    public static function newClient()
    {
        return new BaseClient('https://maps.googleapis.com');
    }
}

class BaseClient extends AbstractClient
{
    public function __construct($endpoint, array $options = [])
    {
        parent::__construct($endpoint, $options);
    }

    public function getGoogleMapsEndPoint()
    {
        return $this->request('GET', '/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap
            &markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
            &markers=color:red%7Clabel:C%7C40.718217,-73.998284&key=AIzaSyAXzS7jOvzTl9NZsJMMWNuQ6K1CesU6msE'
        );
    }
}
