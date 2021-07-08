<?php declare(strict_types=1);

namespace Test\CmsBundle;

use ExEss\Bundle\CmsBundle\Entity\User;

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
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */
    public function createUserAndLogin(): string
    {
        $user = new User();
        $user->setCreatedBy('1');

        $this->grabService('doctrine.orm.entity_manager')->persist($user);

        $this->loginAsUser($user);

        return $user->getId();
    }
}
