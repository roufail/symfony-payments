<?php


namespace App\Gateways;
use Exception;

class GatewayFactory
{

    /**
     * author osama saed
     * GatewayFactory will create gateway instance upon gateway
     */
    protected GatewayInterface $gateway;
    protected String $shift4Url, $aciUrl;

    /*
    * Construct will create gateway instance upon gateway
    * parameter $shift4Url $aciUrl injected in config/services.yaml 
    */
    public function __construct(string $shift4Url,string $aciUrl) {
        $this->shift4Url = $shift4Url;
        $this->aciUrl = $aciUrl;
    }


    /*
    * getGateWay will generate gateway instance upon gateway
    */
    public function setGateway(string $gateway) {
        match ($gateway) {
            'shift4' => $this->gateway = new Shift4Gateway($this->shift4Url),
            'aci' => $this->gateway = new AciGateway($this->aciUrl),
            default => throw new Exception("Supported Gateways currently is shift4 and aci"),
        };
    }

    /*
    * getGateWay will retrive gateway instance 
    */
    public function getGateWay(): GateWayInterface
    {
        return  $this->gateway;
    }
    
}
