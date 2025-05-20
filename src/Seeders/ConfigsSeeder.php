<?php

namespace Crm\VubEplatbyModule\Seeders;

use Crm\ApplicationModule\Builder\ConfigBuilder;
use Crm\ApplicationModule\Models\Config\ApplicationConfig;
use Crm\ApplicationModule\Repositories\ConfigCategoriesRepository;
use Crm\ApplicationModule\Repositories\ConfigsRepository;
use Crm\ApplicationModule\Seeders\ConfigsTrait;
use Crm\ApplicationModule\Seeders\ISeeder;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigsSeeder implements ISeeder
{
    use ConfigsTrait;

    private $configCategoriesRepository;

    private $configsRepository;

    private $configBuilder;

    public function __construct(
        ConfigCategoriesRepository $configCategoriesRepository,
        ConfigsRepository $configsRepository,
        ConfigBuilder $configBuilder,
    ) {
        $this->configCategoriesRepository = $configCategoriesRepository;
        $this->configsRepository = $configsRepository;
        $this->configBuilder = $configBuilder;
    }

    public function seed(OutputInterface $output)
    {
        $category = $this->configCategoriesRepository->findBy('name', 'payments.config.category');
        $sorting = 1400;

        $this->addConfig(
            $output,
            $category,
            'vub_eplatby_sharedsecret',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.sharedsecret.name',
            'vub_eplatby.config.sharedsecret.description',
            '1111111111111111111111111111111111111111111111111111111111111111',
            $sorting++,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_eplatby_mid',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.mid.name',
            'vub_eplatby.config.mid.description',
            '1111',
            $sorting++,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_eplatby_constant_symbol',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.constant_symbol.name',
            'vub_eplatby.config.constant_symbol.description',
            '0308',
            $sorting++,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_eplatby_mode',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.mode.name',
            'vub_eplatby.config.mode.description',
            'test',
            $sorting++,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_eplatby_rem',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.rem.name',
            'vub_eplatby.config.rem.description',
            '',
            $sorting++,
        );

        $category = $this->configCategoriesRepository->loadByName('payments.config.category_confirmation');
        if (!$category) {
            $category = $this->configCategoriesRepository->add('payments.config.category_confirmation', 'fa fa-check-double', 1600);
            $output->writeln('  <comment>* config category <info>Potvrdzovacie e-maily</info> created</comment>');
        } else {
            $output->writeln('  * config category <info>Potvrdzovacie e-maily</info> exists');
        }

        $this->addConfig(
            $output,
            $category,
            'vub_confirmation_host',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_confirmation_host.name',
            'vub_eplatby.config.vub_confirmation_host.description',
            '',
            1001,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_confirmation_port',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_confirmation_port.name',
            'vub_eplatby.config.vub_confirmation_port.description',
            '',
            1002,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_confirmation_username',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_confirmation_username.name',
            'vub_eplatby.config.vub_confirmation_username.description',
            '',
            1003,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_confirmation_password',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_confirmation_password.name',
            'vub_eplatby.config.vub_confirmation_password.description',
            '',
            1004,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_confirmation_processed_folder',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_confirmation_processed_folder.name',
            'vub_eplatby.config.vub_confirmation_processed_folder.description',
            '',
            1005,
        );

        $this->addConfig(
            $output,
            $category,
            'vub_zip_password',
            ApplicationConfig::TYPE_STRING,
            'vub_eplatby.config.vub_zip_password.name',
            'vub_eplatby.config.vub_zip_password.description',
            '',
            1006,
        );

        // moving configs to different category if they already existed
        $config = $this->configsRepository->loadByName('vub_zip_password');
        $this->configsRepository->update($config, [
            'display_name' => 'vub_eplatby.config.vub_zip_password.name',
            'description' => 'vub_eplatby.config.vub_zip_password.description',
            'sorting' => 1006,
        ]);
    }
}
