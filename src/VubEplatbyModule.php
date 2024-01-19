<?php

namespace Crm\VubEplatbyModule;

use Crm\ApplicationModule\Application\CommandsContainerInterface;
use Crm\ApplicationModule\Application\Managers\SeederManager;
use Crm\ApplicationModule\CrmModule;
use Crm\VubEplatbyModule\Commands\VubMailConfirmationCommand;
use Crm\VubEplatbyModule\Seeders\ConfigsSeeder;
use Crm\VubEplatbyModule\Seeders\PaymentGatewaysSeeder;

class VubEplatbyModule extends CrmModule
{
    public function registerSeeders(SeederManager $seederManager)
    {
        $seederManager->addSeeder($this->getInstance(ConfigsSeeder::class));
        $seederManager->addSeeder($this->getInstance(PaymentGatewaysSeeder::class));
    }

    public function registerCommands(CommandsContainerInterface $commandsContainer)
    {
        $commandsContainer->registerCommand($this->getInstance(VubMailConfirmationCommand::class));
    }
}
