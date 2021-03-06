<?php declare(strict_types=1);

namespace Test\CmsBundle\Api\Log;

use Test\CmsBundle\ApiTester;
use ExEss\Bundle\CmsBundle\Helper\DataCleaner;
use ExEss\Bundle\CmsBundle\Http\SuccessResponse;

class ErrorCest
{
    // @codingStandardsIgnoreStart
    private $body = <<<JSON
        {  
           "url":"http://localhost:9005/#/sales-marketing/dashboard/accounts_list/",
           "state":{  
              "mainMenuKey":"sales-marketing",
              "dashboardId":"accounts_list",
              "recordId":""
           },
           "name":"HTTP error: 500"
        }
JSON;
    // @codingStandardsIgnoreEnd

    public function shouldReturn(ApiTester $I): void
    {
        // Given
        $I->getAnApiTokenFor('adminUser');

        // When
        $I->sendPOST('/Api/log/error', DataCleaner::jsonDecode($this->body));

        // Then
        $I->seeResponseIsDwpResponse(200);
        $I->seeAssertPathsInJson([
            '$.message' => SuccessResponse::MESSAGE_SUCCESS,
        ]);
    }
}
