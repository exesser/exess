<?php declare(strict_types=1);

namespace Test\CmsBundle;

use Codeception\Util\Fixtures;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */
    public function getAnApiTokenFor(string $userFixture, ?string $password = null): string
    {
        if ($password !== null) {
            // username/password was passed
            $username = $userFixture;
        } else {
            $username = Fixtures::get($userFixture)['username'];
            $password = Fixtures::get($userFixture)['password'];
        }

        $this->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
        $this->sendPOST('/Api/login', \json_encode([
            'username' => $username,
            'password' => $password,
        ]));

        // assertions
        $this->seeResponseCodeIs(200);
        $this->seeResponseIsJson();

        $token = $this->grabDataFromResponseByJsonPath('$.data.token')[0];

        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->haveHttpHeader('Accept', 'application/json');
        $this->haveHttpHeader('Authorization', 'Bearer ' . $token);

        return $token;
    }

    public function seeAssertPathsInJson(array $assertPaths): void
    {
        foreach ($assertPaths as $path => $expectedResult) {
            $this->seeResponseJsonMatchesJsonPath($path);
            $results = $this->grabDataFromResponseByJsonPath($path);
            if (\count($results) > 1) {
                // we got multiple results so let's see if our expected one is in it
                $this->assertContains($expectedResult, $results);
            } else {
                $this->assertSame($expectedResult, $results[0]);
            }
        }
    }

    public function seeResponseIsDwpResponse(int $httpCode = 201, ?string $expectFlashMessage = null): void
    {
        $this->seeResponseIsJson();

        $flashMessage = $this->grabDataFromResponseByJsonPath('flashMessages[0]')[0] ?? null;
        if (!empty($flashMessage)) {
            if ($expectFlashMessage) {
                $this->assertEquals($expectFlashMessage, $flashMessage['text']);
            } else {
                if ($flashMessage['type'] === 'ERROR') {
                    $this->fail('Flash Message: ' . $flashMessage['text']);
                }
            }
        }

        $status = $this->grabDataFromResponseByJsonPath('$status')[0] ?? $httpCode;

        if ($status >= 300 && $httpCode < 300) {
            $message = $this->grabDataFromResponseByJsonPath('$data.message')[0]
                ?? $this->grabDataFromResponseByJsonPath('$message')[0]
                ?? null;
            if (!empty($message)) {
                $this->fail('Error message: ' . $message);
            }
        }

        $this->dontSeeResponseJsonMatchesJsonPath('errors');
        $this->seeResponseCodeIs($httpCode);
    }
}
