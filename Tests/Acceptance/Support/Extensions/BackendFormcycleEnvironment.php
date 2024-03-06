<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Extensions;

use TYPO3\TestingFramework\Core\Acceptance\Extension\BackendEnvironment;

class BackendFormcycleEnvironment extends BackendEnvironment
{
    protected $localConfig = [
        'coreExtensionsToLoad' => [
            'core',
            'extbase',
            'fluid',
            'backend',
            'install',
            'frontend',
        ],
        'testExtensionsToLoad' => [
            'typo3conf/ext/xm_formcycle',
        ],
    ];
}
