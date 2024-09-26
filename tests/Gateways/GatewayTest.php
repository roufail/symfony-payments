<?php
namespace App\Tests\Gateways;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GatewayTest extends WebTestCase
{

 
    public function testFetchShift4(): void
    {
        $client = static::createClient();
        // Request a specific page
        $crawler = $client->request('GET', '/api/payments/shift4');
        // Request a specific page
        $response = $client->getResponse();
        // Test if response is OK
        $this->assertSame(200, $response->getStatusCode());
        // Test if Content-Type is valid application/json
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        // Test that response is not empty
        $this->assertNotEmpty($response->getContent());
    }


    public function testCreateShift4(): void
    {   
        $client = static::createClient();
 
        $data = [
            'amount'          => '1000',
            'currency'        => 'USD',
            'description'     => 'description',
            'cardNumber'      => '5105105105105100', 
            'cardExpiryMonth' => '12', 
            'cardExpiryYear'  => '27', 
            'cvv'             => '123', 
        ];
    
        $crawler  =  $client->request('POST', '/api/payments/shift4/create', $data, []);
        $response =  $client->getResponse();

        // Test if response is OK
        $this->assertSame(200, $response->getStatusCode());
        // Test if Content-Type is valid application/json
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        // Test that response is not empty
        $this->assertNotEmpty($response->getContent());
    }



    public function testCreateAci(): void
    {   
        $client = static::createClient();
        $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
        "&amount=92.00" .
        "&currency=EUR" .
        "&paymentBrand=VISA" .
        "&paymentType=DB" .
        "&card.number=4200000000000000" .
        "&card.holder=Jane Jones" .
        "&card.expiryMonth=05" .
        "&card.expiryYear=2034" .
        "&card.cvv=123";
        parse_str($data, $data);
        $crawler  =  $client->request('POST', '/api/payments/aci/create', $data, []);
        $response =  $client->getResponse();
        // Test if response is OK
        $this->assertSame(200, $response->getStatusCode());
        // Test if Content-Type is valid application/json
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        // Test that response is not empty
        $this->assertNotEmpty($response->getContent());
    }
}
