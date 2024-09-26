<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Gateways\GatewayFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\Console\Attribute\AsCommand;


/**
 * uthor osama saed
 * GatewaysCommand this class will handle all gateways command 
 * gateway shift4 or aci
 * currancy
 * amount
 * you can test it like this example [php bin/console Gateways shift4 USD 1000]
 */

#[AsCommand(
    name: 'Gateways',
    description: 'Creates a new payment.',
)]
class GatewaysCommand extends Command
{
    private GatewayFactory $gatewayFactory;

    public function __construct(
        GatewayFactory $gatewayFactory,
    ) {
        parent::__construct();
        $this->gatewayFactory = $gatewayFactory;
    }


    // ...
    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument('gateway', InputArgument::REQUIRED, 'shift4 or aci')
            ->addArgument('currency', InputArgument::REQUIRED, 'currancy')
            ->addArgument('amount', InputArgument::REQUIRED, 'amount');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('gateway:'.$input->getArgument('gateway'));
        if(in_array($input->getArgument('gateway'), ['shift4','aci'])) {
            $this->payment = $this->gatewayFactory->setGateWay($input->getArgument('gateway'));
            $this->payment = $this->gatewayFactory->getGateWay();


            $data = [];
            // if we have more than 2 gateways we can handle that
            //set data
            if($input->getArgument('gateway') == "shift4") {
                $data = [
                    'currency'          => $input->getArgument('currency'),
                    'amount'            => $input->getArgument('amount'),
                    'cardNumber'        => '5105105105105100',
                    'cardExpiryMonth'   => "12",
                    'cardExpiryYear'    => "27",
                    'cvc'               => "123"
                ];
             } else {
                $data = [
                    'entityId'          => '8a8294174b7ecb28014b9699220015ca',
                    'currency'          => $input->getArgument('currency'),
                    'amount'            => $input->getArgument('amount'),
                    'paymentBrand'      => 'VISA',
                    'paymentType'       => 'PA',
                    'cardNumber'        => '4200000000000000',
                    'cardExpiryMonth'   => "05",
                    'cardExpiryYear'    => "2034",
                    'cvv'               => "123",
                    'cardHolder'        => "Jane Jones"
                ];
             }


            $response = $this->payment->create($data);

            if($response['status'] == 200) {
                $output->writeln('charge created successfully');
                foreach($response['content'] as $key => $value) {
                    $output->writeln($key.' '.$value);
                    $output->writeln('-------------------------------------------');
                }
                return Command::SUCCESS;
            }
            $output->writeln('somthing went wrong '.$response['status']['message']);
            if(!empty($response['status']['errors'])) {
                foreach($response['status']['errors'] as $key) {
                    $output->writeln($key->name.' '.$key->message);
                    $output->writeln('-------------------------------------------');
                }
            }
            return Command::FAILURE;
        }

        $output->writeln('Available gatewats is shift4, aci');
        return Command::FAILURE;

    }
}
