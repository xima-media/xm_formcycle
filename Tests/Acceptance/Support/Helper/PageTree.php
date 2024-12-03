<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Helper;

use TYPO3\TestingFramework\Core\Acceptance\Helper\AbstractPageTree;
use Xima\XmFormcycle\Tests\Acceptance\Support\AcceptanceTester;

final class PageTree extends AbstractPageTree
{
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    public function clickElement(string $name): void
    {
        $context = $this->getPageTreeElement();
        $context->findElement(\Facebook\WebDriver\WebDriverBy::xpath('//div[contains(@title, ' . $name . ')]'))->click();
        $context->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector(self::$treeItemAnchorSelector))->click();
    }
}
