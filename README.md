Formcycle
=========

Inhalt
------
- [What does it do?](#What-does-it-do)
- [Author](#Author)
- [Dependencies](#Dependencies)
- [Installation / Configuration](#Installation)
-- [TypoScript Setup](#TypoScript-Setup)
-- [TypoScript Constants](#TypoScript-Constants)
- [Plug-Ins](#Plug-ins)

## <a name="What-does-it-do"></a>What does it do?
Connects to your form created with XIMA® FormCycle (form management with professional form designer, process management, inbox and more features).

## <a name="Author"></a>Author
XIMA MEDIA GmbH ([Website](https://www.xima.de/))
 
## <a name="Dependencies"></a>Dependencies
- TYPO3-Extensions: see [ext_emconf.php](tree/Source/xm_slider/ext_emconf.php) (section "constraints")
 
## <a name="Installation"></a>Installation / Configuration
1. Installation via Extension Manager or by copying into typo3conf/ext/xm_formcycle.
2. Set Extension configuration via Extension Manager
2. Include static templates
3. Configure (Constants-Editor, Template-Settings, Plug-In-Settings)

### <a name="TypoScript-Setup"></a>TypoScript Setup
- **tx_xmformcycle_ajax**
    AJAX configuration with typeNum 1464705954.
### <a name="TypoScript-Constants"></a>TypoScript Constants
- **plugin.tx_xmformcycle.settings.enableJs**
    Including JavaScript is the default. But you can disable it (=0) for your own asset management.
 
## <a name="Plug-ins"></a>Plug-Ins
- FormCycle Integrator: Default Plug-In to show your form 
 
