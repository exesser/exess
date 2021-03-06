<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Login;

use Test\CmsBundle\ApiTester;
use ExEss\Bundle\CmsBundle\Entity\UserLogin;
use Test\CmsBundle\Api\CrudTestUser;

class SuccessCest
{
    private CrudTestUser $user;

    public function _before(ApiTester $I): void
    {
        $this->user = new CrudTestUser($I);
    }

    public function verifyIGetAnApiToken(ApiTester $I): void
    {
        // Given
        $I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');

        // When
        $token = $I->getAnApiTokenFor($this->user->getUserName(), $this->user->getPassword());

        // Then
        $I->flushToDatabase();
        $I->assertNotEmpty($token);

        $lastLogin = $I->grabFromRepository(UserLogin::class, 'lastLogin', ['id' => $this->user->getId()]);
        $I->assertEquals((new \DateTime())->format('Y-m-d'), (new \DateTime($lastLogin))->format('Y-m-d'));

        $jwt = $I->grabFromRepository(UserLogin::class, 'jwt', ['id' => $this->user->getId()]);
        $I->assertNotEmpty($jwt);
        //$I->assertEquals($token, $jwt);
    }
}
