<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\WebDriver;

class ShadowDom extends \Codeception\Module
{
    /**
     * @throws ModuleException
     */
    public function clickShadowDomElement(string $hostSelector, string $innerSelector): void
    {
        /** @var WebDriver $webDriver */
        $webDriver = $this->getModule('WebDriver');
        $webDriver->executeJS('
            const host = document.querySelector(arguments[0]);
            const shadow = host.shadowRoot;
            const innerElement = shadow.querySelector(arguments[1]);
            innerElement.click();
        ', [$hostSelector, $innerSelector]);
    }

    public function seeShadowDomHasElement(string $hostSelector, string $innerSelector): bool
    {
        try {
            $I = $this;
            $I->seeElementInShadowDom($hostSelector, $innerSelector);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @throws ModuleException
     */
    public function seeElementInShadowDom(string $hostSelector, string $innerSelector): void
    {
        /** @var WebDriver $webDriver */
        $webDriver = $this->getModule('WebDriver');
        $result = $webDriver->executeJS('
            const host = document.querySelector(arguments[0]);
            const shadow = host.shadowRoot;
            const innerElement = shadow.querySelector(arguments[1]);
            return innerElement !== null;
        ', [$hostSelector, $innerSelector]);

        $this->assertTrue($result, 'Expected element was not found in Shadow DOM.');
    }
}
