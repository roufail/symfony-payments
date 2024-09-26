<?php


namespace App\Gateways;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use date;

/**
 * author osama saed
 * Shift4Gateway this class will handle all Shift4Gateway methods its extends HttpClient and implements GatewayInterface
 */

class Shift4Gateway extends HttpClient implements GatewayInterface
{

    // gateWayUrl string
    protected string $gateWayUrl;
    // headers array
    protected array $headers;
    // unwantedFields array this used to unset keys that will prevent us from create charge
    private array $unwantedFields = [
        'cardNumber',
        'cardExpiryMonth',
        'cardExpiryYear',
        'cvv',
        'cvc',
        'entityId',
        'paymentBrand',
        'paymentType',
        'cardHolder',
        'entityId',
    ];
    // construct  this used to set gateway url and headers
    public function __construct(string $shift4Url) {
        parent::__construct();
        $this->gateWayUrl = $shift4Url;
        $this->headers = [
                'content-type' => 'application/json',
                'auth' => ['sk_test_4r3kbIGTI2yc2JxHqLzb2TBP',null],
        ];
    }

    // fetch()  this used to set fetch charges
    public function fetch() {
        $url = $this->gateWayUrl.'/charges';
        $response = $this->client->get($url,$this->headers);
        $data = $this->getData($response);
        return ['content' => $data, 'status' => $response->getStatusCode()];
    }



    // create  this used to set create new charges
	public function create(array $data) {
        // set data
        $data['card'] = [
            'number'   => $data['cardNumber'],
            'expMonth' => $data['cardExpiryMonth'],
            'expYear'  => $data['cardExpiryYear'],
            'cvc'      => isset($data['cvv']) ? $data['cvv'] : $data['cvc'],
        ];

        // unset unwanted code
        foreach ($this->unwantedFields as $field) {
           unset($data[$field]);
        }

        $url = $this->gateWayUrl.'/charges';
        // add data
        $this->headers['json'] = $data;
        // create request
        $response = $this->client->post($url,$this->headers);
        $data = [];
        if($response) {
             // get response
            $data = $this->getData($response);
        }
        // get response
        return ['content' => $data, 'status' => $response->getStatusCode()];
    }

	public function getName() {
        return "Shift4Gateway";
    }
    // format response
    public function getData($response) {
        $data =  json_decode($response->getBody()->getContents());
        // date_default_timezone_set("Africa/Cairo");
        
        if(!empty($data) && property_exists($data, 'list') && is_array($data->list)) {
            $response = array();
            foreach ($data->list as $value) {
                $response[] = [
                    'transaction_id' => $value->id,
                    'date_of_creating' => date('d-m-Y H:i',$value->created),
                    'amount' => $value->amount,
                    'currency' => $value->currency,
                    'card_bin' => $value->card->last4
                ];            
            }

            return $response;
        }else {
            return [
                'transaction_id' => $data->id,
                'date_of_creating' => date('d-m-Y H:i',$data->created),
                'amount' => $data->amount,
                'currency' => $data->currency,
                'card_bin' => $data->card->last4
            ];
        }

    }
}