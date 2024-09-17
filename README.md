<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `xm_formcycle`

![Latest version](https://typo3-badges.dev/badge/xm_formcycle/version/shields.svg)
[![Supported TYPO3 versions](https://typo3-badges.dev/badge/xm_formcycle/typo3/shields.svg)](https://extensions.typo3.org/extension/ximaxm_formcycle)
![Total downloads](https://typo3-badges.dev/badge/xm_formcycle/downloads/shields.svg)
[![TYPO3 extension](https://typo3-badges.dev/badge/xm_formcycle/extension/shields.svg)](https://extensions.typo3.org/extension/xm_formcycle)
[![Tests](https://github.com/xima-media/xm_formcycle/actions/workflows/tests.yml/badge.svg)](https://github.com/xima-media/xm_formcycle/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/xima-media/xm_formcycle/graph/badge.svg?token=VUMQ5EUG02)](https://codecov.io/gh/xima-media/xm_formcycle)
[![Composer](https://typo3-badges.dev/badge/xm_formcycle/composer/shields.svg)](https://packagist.org/packages/xima/xima-typo3-formcycle)

</div>

A TYPO3 extension that connects to [formcycle](https://www.formcycle.eu/). Select your created forms and embed
them into your TYPO3 site.

## Requirements

* formcycle version 8 + installed plugin `Formularliste`
* PHP 8.1+

## Installation

### Composer

```bash
composer require xima/xima-typo3-formcycle
```

### TER

[![TER version](https://typo3-badges.dev/badge/xm_formcycle/version/shields.svg)](https://extensions.typo3.org/extension/xm_formcycle)

Download the zip file from
[TYPO3 extension repository (TER)](https://extensions.typo3.org/extension/xm_formcycle).

## Configuration

After installation, enter your login data via extension configuration and include the TypoScript template for the
frontend rendering.

### 1. Extension configuration

Set your formcycle credentials in the extension configuration via TYPO3 backend or in your `config/system/settings.php`:

```php
'EXTENSIONS' => [
    'xm_formcycle' => [
        'formcycleUrl' => 'https://pro.formcloud.de/formcycle/',
        'formcycleClientId' => '4231',
    ],
]
```

### 2. TypoScript include

Include the static TypoScript template "formcycle" or directly import it in your sitepackage:

```typo3_typoscript
@import 'EXT:xm_formcycle/Configuration/TypoScript/setup.typoscript'
```

## Developer

If you want to modify the [fluid template](Resources/Private/Templates/Formcycle.html), add template paths via
TypoScript constants:

```typo3_typoscript
plugin.tx_xmformcycle {
    view {
        templateRootPath = EXT:your_ext/Resources/Private/Templates
        partialRootPath = EXT:your_ext/Resources/Private/Partials
        layoutRootPath = EXT:your_ext/Resources/Private/Layouts
    }
}
```

Copy and modify the `Formcycle.html` to the Templates directory.

## License

This project is licensed under [GNU General Public License 2.0 (or later)](LICENSE.md).
