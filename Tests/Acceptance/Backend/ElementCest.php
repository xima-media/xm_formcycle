<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Backend;

use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTreeHelper;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ShadowDomHelper;

class ElementCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/typo3/');
        $I->waitForElementVisible('input[name="username"]');
        $I->waitForElementVisible('input[type="password"]');
        $I->fillField('input[name="username"]', 'admin');
        $I->fillField('input[type="password"]', 'Passw0rd!');
        $I->click('button[type="submit"]');
        $I->waitForElementNotVisible('form[name="loginform"]');
        $I->seeCookie('be_typo_user');
    }

    // tests
    public function elementIsInWizard(AcceptanceTester $I, PageTreeHelper $pageTree, ShadowDomHelper $domHelper): void
    {
        $I->click('Page');
        $I->waitForElementVisible(PageTreeHelper::$pageTreeFrameSelector);
        $pageTree->clickElement('Main');

        // open wizard
        $I->switchToContentFrame();
        $I->click('typo3-backend-new-content-element-wizard-button');
        $I->switchToIFrame();
        $I->waitForElementVisible('typo3-backend-new-content-element-wizard');

        $domHelper->clickShadowDomElement('typo3-backend-new-content-element-wizard', 'button.navigation-toggle');
        $domHelper->clickShadowDomElement('typo3-backend-new-content-element-wizard', 'button.navigation-item:nth-child(3)');

        $I->see('Formcycle');
        $I->see('Include a XIMA® FormCycle form');

        $I->makeScreenshot();
    }
}
