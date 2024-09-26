<?php


namespace App\Gateways;

/**
 * author osama saed
 * AciGateway this class will handle all Shift4Gateway methods its extends HttpClient and implements GatewayInterface
 */
class AciGateway extends HttpClient  implements GatewayInterface
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
        'description',
        'cardHolder'
    ];

    // construct  this used to set gateway url and headers
    public function __construct(string $aciUrl) {
        parent::__construct();
        $this->gateWayUrl = $aciUrl;
        $this->headers = [ 
            'headers' => 
            [
                'content-type'  => 'application/json',
                'Authorization' => 'Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg',
            ]
        ];
    }

    // create  this used to set create new charges
	public function create(array $data) {
        // set data
        $data['card.number']      = $data['cardNumber'];
        $data['card.expiryMonth'] = $data['cardExpiryMonth'];
        $data['card.expiryYear']  = $data['cardExpiryYear'];
        $data['card.cvv']         = $data['cvv'];
        $data['card.holder']      = $data['cardHolder'];

        // unset unwanted code
        foreach ($this->unwantedFields as $field) {
            unset($data[$field]);
        }


        // add data unfortunately this not worked for me with guzzle 
        $data = http_build_query($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->gateWayUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=')
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);


        $data = [];
        $response =  json_decode($response);
        if($response->result->code == "000.100.110") {
            $data = $this->getData($response);
        }else {
            if(isset($response->result->parameterErrors)) {
                return ['content' => [], 'status' => ['code' => $response->result->code, 'message' => $response->result->description, 'errors' => $response->result->parameterErrors]];
            } else {
                return ['content' => [], 'status' => ['code' => $response->result->code, 'message' => $response->result->description]];
            }
        }
        return ['content' => $data, 'status' => ['code' => 200, 'message' => $response->result->description]];
    }

	public function getName() {
        return "AciGateway";
    }
    // format response
    public function getData($response) {
        date_default_timezone_set("Africa/Cairo");
        return [
            'transaction_id' => $response->id,
            'date_of_creating' => date('d-m-Y H:i',\strtotime($response->timestamp)),
            'amount' => $response->amount,
            'currency' => $response->currency,
            'card_bin' => $response->card->last4Digits
        ];
    }

    // there is no fetch to get created data
    public function fetch() {
    }
}