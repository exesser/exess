<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Check;

use Test\CmsBundle\ApiTester;
use ExEss\Bundle\CmsBundle\Component\Health\Model\HealthCheckResult;
use Test\CmsBundle\Api\CrudTestUser;

class HealthCest
{
    private CrudTestUser $user;

    public function _before(ApiTester $I): void
    {
        $this->user = new CrudTestUser($I);
        $this->user->linkSecurity($I->generateSecurityGroup('third party'));
    }

    public function healthCheckSucceeds(ApiTester $I): void
    {
        // Given
        $this->user->login();

        // When
        $I->sendGet('/Api/check/health');

        // Then
        $I->seeResponseIsDwpResponse(200);
        $I->seeAssertPathsInJson([
            '$.data.results.database.result' => true,
            '$.data.results.database.message' => HealthCheckResult::OK,
        ]);
    }
}
