<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Sidebar;

use Test\CmsBundle\ApiTester;
use ExEss\Bundle\CmsBundle\Entity\User;

class WithBaseObjectCest
{
    public function createBlueSidebar(ApiTester $I): void
    {
        // Given
        $userId = $I->generateUser('foo');
        $I->getAnApiTokenFor('adminUser');

        // When
        $I->sendGet("/Api/sidebar/" . \urlencode(User::class) . "/$userId");

        // Then
        $I->seeResponseIsDwpResponse(200);
        $I->seeAssertPathsInJson([
            '$.data.some' => 'data',
        ]);
    }
}
