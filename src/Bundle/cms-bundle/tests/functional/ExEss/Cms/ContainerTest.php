<?php declare(strict_types=1);

namespace Test\CmsBundle\Functional\ExEss\Cms;

use Test\CmsBundle\Helper\Testcase\FunctionalTestCase;

class ContainerTest extends FunctionalTestCase
{
    public function testGrabAllServices(): void
    {
        foreach ($this->tester->getContainerKeys() as $serviceName) {
            try {
                $this->tester->grabService($serviceName);
                $this->tester->assertTrue(true);
            } catch (\Throwable $e) {
                $this->tester->assertTrue(false, "Grabbing service $serviceName failed: " . $e->getMessage());
            }
        }
    }
}
