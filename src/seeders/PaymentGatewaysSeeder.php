<?php

namespace Crm\VubEplatbyModule\Seeders;

use Crm\ApplicationModule\Seeders\ISeeder;
use Crm\PaymentsModule\Repository\PaymentGatewaysRepository;
use Crm\VubEplatbyModule\Gateways\VubEplatby;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentGatewaysSeeder implements ISeeder
{
    private $paymentGatewaysRepository;
    
    public function __construct(PaymentGatewaysRepository $paymentGatewaysRepository)
    {
        $this->paymentGatewaysRepository = $paymentGatewaysRepository;
    }

    public function seed(OutputInterface $output)
    {
        $code = VubEplatby::GATEWAY_CODE;
        if (!$this->paymentGatewaysRepository->exists($code)) {
            $this->paymentGatewaysRepository->add(
                'VÚB ePlatby',
                $code,
                100,
                true
            );
            $output->writeln("  <comment>* payment type <info>{$code}</info> created</comment>");
        } else {
            $output->writeln("  * payment type <info>{$code}</info> exists");
        }
    }
}
