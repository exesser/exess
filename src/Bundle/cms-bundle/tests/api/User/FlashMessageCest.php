<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\User;

use ExEss\Bundle\CmsBundle\Api\V8_Custom\Service\FlashMessages\FlashMessage;
use ExEss\Bundle\CmsBundle\Api\V8_Custom\Service\FlashMessages\FlashMessageContainer;
use Test\CmsBundle\ApiTester;

class FlashMessageCest
{
    public function testIfMessageIsReturned(ApiTester $I): void
    {
        // Given
        $I->getAnApiTokenFor('adminUser');

        $message = "I have a message for you!";

        /** @var FlashMessageContainer $flashMessageContainer */
        $flashMessageContainer = $I->grabService(FlashMessageContainer::class);
        $flashMessageContainer->addFlashMessage(new FlashMessage($message));

        // When
        $I->sendGet('/Api/user/preferences');

        // Then
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('flashMessages.0.text', $message);
    }
}
