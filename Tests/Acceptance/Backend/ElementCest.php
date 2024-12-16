<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Backend;

use Codeception\Scenario;
use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Enums\Selectors;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTree;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ShadowDom;

class ElementCest
{
    public function _before(AcceptanceTester $I, Scenario $scenario): void
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

    public function createElementAndSave(
        AcceptanceTester $I,
        PageTree $pageTree,
        ShadowDom $domHelper
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTree::$treeSelector);
        $I->wait(2);
        $pageTree->openPath(['Main', 'Example']);

        // open wizard
        $I->switchToContentFrame();
        $I->waitForElementVisible(Selectors::CONTENT_WIZARD_BUTTON->value);
        $I->click(Selectors::CONTENT_WIZARD_BUTTON->value);

        // navigate to form tab
        $I->switchToIFrame();
        $I->waitForElementVisible(Selectors::CONTENT_WIZARD->value);
        $domHelper->clickShadowDomElement(Selectors::CONTENT_WIZARD->value, Selectors::CONTENT_WIZARD_FORM_TAB->value);

        // See and select element
        $I->see('Formcycle');
        $I->see('Include a formcycle form');
        $domHelper->clickShadowDomElement(Selectors::CONTENT_WIZARD->value, Selectors::CONTENT_WIZARD_FORMCYCLE->value);

        // Fill and save element
        $I->switchToContentFrame();
        $I->wait(1);
        $I->fillField('input[data-formengine-input-name*="[header]"]', 'Element1');

        $I->click('Formcycle');
        $I->dontSee('Configuration error');
        $I->waitForElementVisible('#xm-available-forms-wrapper', 120);
        $I->click('Save');
    }
}
