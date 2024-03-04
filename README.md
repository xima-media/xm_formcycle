<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `xm_formcycle`

[![Supported TYPO3 versions](https://typo3-badges.dev/badge/xm_formcycle/typo3/shields.svg)](https://extensions.typo3.org/extension/xm_formcycle)

</div>

A TYPO3 extension that connects to [XIMA® Formcycle](https://www.formcycle.eu/). Select your created forms and embed
them into your TYPO3 site.

## Requirements

* Formcycle version 8 + installed plugin `Formularliste`
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

Set your Formcycle credentials in the extension configuration via TYPO3 backend or in your `config/system/settings.php`:

```php
'EXTENSIONS' => [
    'xm_formcycle' => [
        'formCycleUrl' => 'https://pro.formcloud.de/',
        'formCycleClientId' => '4231',
        'formCycleUser' => 'user@examle.com',
        'formCyclePass' => 'the-password',
        'formCycleFrontendUrl' => '', // optional
        'integrationMode' => '', // optional
    ],
]
```

### 2. TypoScript include

Include the static TypoScript template "Formcycle" or directly import it in your sitepackage:

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
