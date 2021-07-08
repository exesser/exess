<?php declare(strict_types=1);

namespace Test\CmsBundle\Helper\Module;

use Codeception\Module\Symfony;
use ExEss\Bundle\CmsBundle\DependencyInjection\Container;

class SymfonyHelper extends \Codeception\Module
{
    private function getSymfony(): Symfony
    {
        return $this->getModule('Symfony');
    }

    /**
     * @param mixed $mockedService
     */
    public function mockService(string $name, $mockedService): void
    {
        /** @var Container $container */
        $container = $this->getSymfony()->grabService('service_container');
        $container->mockSymfonyService($name, $mockedService);
    }

    /**
     * @return array|string[]
     */
    public function getContainerKeys(): array
    {
        return $this->getSymfony()->grabService('service_container')->getServiceIds();
    }
}
