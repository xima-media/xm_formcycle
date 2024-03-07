<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Backend;

use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTreeHelper;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ShadowDomHelper;

class ElementCest
{
    public const CONTENT_WIZARD = 'typo3-backend-new-content-element-wizard';

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
    public function createElementAndSave(
        AcceptanceTester $I,
        PageTreeHelper $pageTree,
        ShadowDomHelper $domHelper
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTreeHelper::$pageTreeFrameSelector);
        $pageTree->clickElement('Main');

        // open wizard
        $I->switchToContentFrame();
        $I->click('typo3-backend-new-content-element-wizard-button');
        $I->switchToIFrame();
        $I->waitForElementVisible('typo3-backend-new-content-element-wizard');

        $domHelper->clickShadowDomElement(self::CONTENT_WIZARD, 'button.navigation-toggle');
        $domHelper->clickShadowDomElement(self::CONTENT_WIZARD, 'button.navigation-item:nth-child(3)');

        // See and select element
        $I->see('Formcycle');
        $I->see('Include a XIMA® FormCycle form');
        $domHelper->clickShadowDomElement(self::CONTENT_WIZARD, 'button[data-identifier="forms_formcycle"]');

        // Fill and save element
        $I->switchToContentFrame();
        $I->fillField('input[data-formengine-input-name*="[header]"]', 'Element1');
        $I->click('Save');
    }
}
