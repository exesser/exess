<?php declare(strict_types=1);

namespace Test\CmsBundle\Helper\Testcase;

if (!\class_exists('\Test\CmsBundle\UnitTester')) {
    throw new \Exception('The UnitTester class should exist (check the tests/_support folder)');
}

use Codeception\TestCase\Test;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class UnitTestCase extends Test
{
    use MockeryPHPUnitIntegration;

    protected \Test\CmsBundle\UnitTester $tester;
}
