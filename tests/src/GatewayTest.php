<?php

namespace ByTIC\Omnipay\Librapay\Tests;

use ByTIC\Omnipay\Librapay\Gateway;
use Guzzle\Http\Client;

/**
 * Class GatewayTest
 * @package ByTIC\Omnipay\Librapay\Tests
 */
class GatewayTest extends AbstractTest
{
    public function testPurchase()
    {
        $httpClient = new Client();
        $gateway = new Gateway($httpClient);
        $gateway->initialize(require TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR. 'enviromentParams.php');

        $request = $gateway->purchase(require TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR. 'simpleOrderParams.php');
        $response = $request->send();

        $requestGateway = $httpClient->createRequest(
            $response->getRedirectMethod(),
            $response->getRedirectUrl(),
            null,
            $response->getRedirectData()
        );
        $responseGateway = $httpClient->send($requestGateway);
        $htmlGateway = $responseGateway->getBody(true);

        self::assertContains('Date comanda',$htmlGateway);
        self::assertContains('Suma de plata:',$htmlGateway);
        self::assertContains('3.49',$htmlGateway);
    }
}