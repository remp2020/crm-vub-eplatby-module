<?php

namespace Crm\VubEplatbyModule;

use Crm\ApplicationModule\CrmModule;
use Crm\ApplicationModule\SeederManager;
use Crm\VubEplatbyModule\Seeders\ConfigsSeeder;
use Crm\VubEplatbyModule\Seeders\PaymentGatewaysSeeder;

class VubEplatbyModule extends CrmModule
{
    public function registerSeeders(SeederManager $seederManager)
    {
        $seederManager->addSeeder($this->getInstance(ConfigsSeeder::class));
        $seederManager->addSeeder($this->getInstance(PaymentGatewaysSeeder::class));
    }
}
