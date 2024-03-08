<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Backend;

use Codeception\Attribute\Depends;
use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ExtensionConfiguration;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTree;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ShadowDom;

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

    #[Depends('createElementAndSave')]
    public function seeExtensionConfigurationError(
        AcceptanceTester $I,
        PageTree $pageTree
    ): void {
        $this->navigateToElementTab($I, $pageTree);
        $I->see('Configuration error');
        $I->seeElement('.callout-danger');
    }

    #[Depends('createElementAndSave')]
    public function seeFormSelection(
        AcceptanceTester $I,
        PageTree $pageTree,
        ExtensionConfiguration $extensionConfiguration
    ): void {
        $extensionConfiguration->write('formcycleUrl', 'https://pro.form.cloud/formcycle');
        $extensionConfiguration->write('formcycleClientId', '2252');
        $this->navigateToElementTab($I, $pageTree);
        $I->dontSee('Configuration error');
    }

    private function navigateToElementTab(
        AcceptanceTester $I,
        PageTree $pageTree,
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTree::$pageTreeFrameSelector);
        $pageTree->clickElement('Main');
        $I->switchToContentFrame();
        $I->click('Element1');
        $I->click('Formcycle');
    }

    public function createElementAndSave(
        AcceptanceTester $I,
        PageTree $pageTree,
        ShadowDom $domHelper
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTree::$pageTreeFrameSelector);
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
