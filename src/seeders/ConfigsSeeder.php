<?php

namespace Crm\VubEplatbyModule\Seeders;

use Crm\ApplicationModule\Builder\ConfigBuilder;
use Crm\ApplicationModule\Config\ApplicationConfig;
use Crm\ApplicationModule\Config\Repository\ConfigCategoriesRepository;
use Crm\ApplicationModule\Config\Repository\ConfigsRepository;
use Crm\ApplicationModule\Seeders\ISeeder;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigsSeeder implements ISeeder
{
    private $configCategoriesRepository;

    private $configsRepository;

    private $configBuilder;

    public function __construct(
        ConfigCategoriesRepository $configCategoriesRepository,
        ConfigsRepository $configsRepository,
        ConfigBuilder $configBuilder
    ) {
        $this->configCategoriesRepository = $configCategoriesRepository;
        $this->configsRepository = $configsRepository;
        $this->configBuilder = $configBuilder;
    }

    public function seed(OutputInterface $output)
    {
        $category = $this->configCategoriesRepository->findBy('name', 'payments.config.category');
        $sorting = 1400;

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_eplatby_sharedsecret',
            'vub_eplatby.config.sharedsecret.name',
            '1111111111111111111111111111111111111111111111111111111111111111',
            $sorting++,
            'vub_eplatby.config.sharedsecret.description'
        );
        $this->addPaymentConfig(
            $output,
            $category,
            'vub_eplatby_mid',
            'vub_eplatby.config.mid.name',
            '1111',
            $sorting++,
            'vub_eplatby.config.mid.description'
        );
        $this->addPaymentConfig(
            $output,
            $category,
            'vub_eplatby_mode',
            'vub_eplatby.config.mode.name',
            'test',
            $sorting++,
            'vub_eplatby.config.mode.description'
        );

        $categoryName = 'payments.config.category_confirmation';
        $category = $category = $this->configCategoriesRepository->loadByName($categoryName);
        if (!$category) {
            $category = $category = $this->configCategoriesRepository->add($categoryName, 'fa fa-check-double', 1600);
            $output->writeln('  <comment>* config category <info>Potvrdzovacie e-maily</info> created</comment>');
        } else {
            $output->writeln('  * config category <info>Potvrdzovacie e-maily</info> exists');
        }

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_confirmation_host',
            'vub_eplatby.config.vub_confirmation_host.name',
            '',
            200,
            'vub_eplatby.config.vub_confirmation_host.description'
        );

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_confirmation_port',
            'vub_eplatby.config.vub_confirmation_port.name',
            '',
            201,
            'vub_eplatby.config.vub_confirmation_port.description'
        );

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_confirmation_username',
            'vub_eplatby.config.vub_confirmation_username.name',
            '',
            202,
            'vub_eplatby.config.vub_confirmation_username.description'
        );

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_confirmation_password',
            'vub_eplatby.config.vub_confirmation_password.name',
            '',
            203,
            'vub_eplatby.config.vub_confirmation_password.description'
        );

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_confirmation_processed_folder',
            'vub_eplatby.config.vub_confirmation_processed_folder.name',
            '',
            204,
            'vub_eplatby.config.vub_confirmation_processed_folder.description'
        );

        $this->addPaymentConfig(
            $output,
            $category,
            'vub_zip_password',
            'payments.config.vub_zip_password.name',
            '',
            205,
            'payments.config.vub_zip_password.description'
        );
    }

    private function addPaymentConfig(OutputInterface $output, $category, $name, $displayName, $value, $sorting, $description = null)
    {
        $config = $this->configsRepository->loadByName($name);
        if (!$config) {
            $this->configBuilder->createNew()
                ->setName($name)
                ->setDisplayName($displayName)
                ->setDescription($description)
                ->setValue($value)
                ->setType(ApplicationConfig::TYPE_STRING)
                ->setAutoload(true)
                ->setConfigCategory($category)
                ->setSorting($sorting)
                ->save();
            $output->writeln("  <comment>* config item <info>$name</info> created</comment>");
        } else {
            $output->writeln("  * config item <info>$name</info> exists");

            if ($config->has_default_value && $config->value !== $value) {
                $this->configsRepository->update($config, ['value' => $value, 'has_default_value' => true]);
                $output->writeln("  <comment>* config item <info>$name</info> updated</comment>");
            }

            if ($config->category->name != $category->name) {
                $this->configsRepository->update($config, [
                    'config_category_id' => $category->id
                ]);
                $output->writeln("  <comment>* config item <info>$name</info> updated</comment>");
            }
        }
    }
}
