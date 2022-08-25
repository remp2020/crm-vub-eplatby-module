<?php

namespace Crm\VubEplatbyModule\DI;

use Contributte\Translation\DI\TranslationProviderInterface;
use Nette\DI\CompilerExtension;

final class VubEplatbyModuleExtension extends CompilerExtension implements TranslationProviderInterface
{
    private $defaults = [];

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        // set default values if user didn't define them
        $this->config = $this->validateConfig($this->defaults);

        // load services from config and register them to Nette\DI Container
        $this->compiler->loadDefinitionsFromConfig(
            $this->loadFromFile(__DIR__.'/../config/config.neon')['services']
        );
    }

    /**
     * Return array of directories, that contain resources for translator.
     * @return string[]
     */
    public function getTranslationResources(): array
    {
        return [__DIR__ . '/../lang/'];
    }
}
