<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Login;

use Test\CmsBundle\ApiTester;

class RequiredCest
{
    public function anAuthenticationErrorShouldBeRaised(ApiTester $I): void
    {
        // When
        $I->sendGet('/Api/user/preferences');

        // Then
        $I->seeResponseIsDwpResponse(401);
        $I->seeResponseEquals('{"status":401,"data":{"message":"Unauthorized"},"message":"ERROR"}');
    }
}
