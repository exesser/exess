<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Logout;

use Test\CmsBundle\ApiTester;

class LogoutCest
{
    public function shouldReturn(ApiTester $I): void
    {
        // Given
        $I->getAnApiTokenFor('adminUser');

        // When
        $I->sendGet('/Api/logout');

        // Then
        $I->seeResponseIsDwpResponse(200);
    }
}
