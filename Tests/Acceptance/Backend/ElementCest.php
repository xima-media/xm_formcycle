<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Backend;

use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTreeHelper;

class ElementCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/typo3/');
        $I->waitForElementVisible('input[name="username"]');
        $I->waitForElementVisible('input[type="password"]');
        $I->fillField('input[name="username"]', 'admin');
        $I->fillField('input[type="password"]', 'changeme');
        $I->click('button[type="submit"]');
        $I->waitForElementNotVisible('form[name="loginform"]');
        $I->seeCookie('be_typo_user');
    }

    // tests
    public function tryToTest(AcceptanceTester $I, PageTreeHelper $pageTree)
    {
        $I->click('Page');

        $I->waitForElementVisible(PageTreeHelper::$pageTreeFrameSelector);

        $pageTree->clickElement('Main');

        $I->switchToContentFrame();

        $I->click('typo3-backend-new-content-element-wizard-button');

        $I->makeScreenshot();
    }
}
