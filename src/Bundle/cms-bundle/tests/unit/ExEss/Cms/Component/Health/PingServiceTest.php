<?php declare(strict_types=1);

namespace Test\CmsBundle\Unit\ExEss\Cms\Component\Health;

use ExEss\Bundle\CmsBundle\Component\Health\PingService;
use Test\CmsBundle\Helper\Testcase\UnitTestCase;

class PingServiceTest extends UnitTestCase
{
    public function testResult(): void
    {
        // Given
        $service = new PingService();

        // When
        $result = $service->getResult();

        // Then
        $this->tester->assertEquals(['result' => true], $result);
    }
}
