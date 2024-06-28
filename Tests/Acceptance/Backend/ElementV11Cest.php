<?php

namespace Backend;

use Codeception\Attribute\Depends;
use Codeception\Scenario;
use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;
use Xima\XmFormcycle\Tests\Acceptance\Support\Enums\SelectorsV11;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\ExtensionConfiguration;
use Xima\XmFormcycle\Tests\Acceptance\Support\Helper\PageTree;

class ElementV11Cest
{
    private bool $isV11 = false;

    public function __construct()
    {
        if (file_exists(__DIR__ . '/../../../vendor/bin/typo3cms')) {
            $this->isV11 = true;
        }
    }

    public function _before(AcceptanceTester $I, Scenario $scenario): void
    {
        if (!$this->isV11) {
            $scenario->skip();
        }
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

    private function navigateToElementTab(
        AcceptanceTester $I,
        PageTree $pageTree,
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTree::$pageTreeFrameSelector);
        $pageTree->clickElement('Main');
        $I->switchToContentFrame();
        $I->waitForText('Element1');
        $I->click('Element1');
        $I->waitForText('Formcycle');
        $I->click('Formcycle');
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
        $I->wait(5);
        $I->dontSee('Configuration error');
        $I->seeElement('#xm-formcycle-forms');
        $I->waitForElementVisible('#xm-available-forms-wrapper', 120);
    }

    public function createElementAndSave(
        AcceptanceTester $I,
        PageTree $pageTree
    ): void {
        $I->click('Page');
        $I->waitForElementVisible(PageTree::$pageTreeFrameSelector);
        $pageTree->clickElement('Main');

        // open wizard
        $I->switchToContentFrame();
        $I->waitForText(SelectorsV11::CONTENT_WIZARD_BUTTON->value);
        $I->click(SelectorsV11::CONTENT_WIZARD_BUTTON->value);

        $I->switchToMainFrame();
        $I->waitForElementVisible(SelectorsV11::CONTENT_WIZARD->value);
        $I->click(SelectorsV11::CONTENT_WIZARD_FORM_TAB->value);

        // See and select element
        $I->see('Formcycle');
        $I->see('Include a XIMA® FormCycle form');
        $I->click('Formcycle');

        // Fill and save element
        $I->switchToContentFrame();
        $I->waitForText('General');
        $I->click('General');

        $I->fillField('input[data-formengine-input-name*="[header]"]', 'Element1');
        $I->click('Save');
    }
}
