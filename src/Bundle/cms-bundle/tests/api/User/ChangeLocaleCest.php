<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\User;

use Test\CmsBundle\ApiTester;
use ExEss\Bundle\CmsBundle\Doctrine\Type\Locale;
use ExEss\Bundle\CmsBundle\Doctrine\Type\SecurityGroupType;
use ExEss\Bundle\CmsBundle\Entity\User;
use Test\CmsBundle\Api\CrudTestUser;

class ChangeLocaleCest
{
    private CrudTestUser $user;

    public function _before(ApiTester $I): void
    {
        $this->user = new CrudTestUser($I);
        $this->user->linkSecurity(
            $I->generateSecurityGroup('foo', ['main_groups_c' => SecurityGroupType::DASHBOARD])
        );
    }

    public function shouldChangeLocale(ApiTester $I): void
    {
        // Given
        $userId = $this->user->getId();
        $this->user->login();

        // When
        $I->sendPOST('/Api/user/change-locale/' . Locale::FR);

        // Then
        $I->seeResponseIsDwpResponse(200);
        $I->seeInRepository(User::class, ['id' => $userId, 'preferredLocale' => Locale::FR]);

        // When
        $I->sendPOST('/Api/user/change-locale/' . Locale::EN);

        // Then
        $I->seeResponseIsDwpResponse(200);
        $I->seeInRepository(User::class, ['id' => $userId, 'preferredLocale' => Locale::EN]);
    }

    public function shouldFailWhenWrongLocaleSend(ApiTester $I): void
    {
        // Given
        $this->user->login();

        // When
        $I->sendPOST('/Api/user/change-locale/something');

        // Then
        $I->seeResponseCodeIs(422);
    }
}
