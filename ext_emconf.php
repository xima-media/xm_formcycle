<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "xm_formcycle".
 *
 * Auto generated 03-12-2015 14:26
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'FormCycle',
	'description' => 'form management with professional form designer, process management, inbox and more features.',
	'category' => 'plugin',
	'author' => 'Mr. Weber',
	'author_email' => 'kontakt@xima.de',
	'author_company' => 'xima media GmbH',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.0',
	'constraints' => 
	array (
		'depends' => 
		array (
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '4.5.0-6.2.0',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:24:{s:26:"class.fc_actionbuttons.php";s:4:"93d0";s:28:"class.fc_actionnewbutton.php";s:4:"6ded";s:25:"class.fc_actionopenfc.php";s:4:"b49b";s:21:"class.fc_openhelp.php";s:4:"a29c";s:33:"class.load_formcycle_projects.php";s:4:"041d";s:21:"ext_conf_template.txt";s:4:"e059";s:12:"ext_icon.gif";s:4:"8667";s:17:"ext_localconf.php";s:4:"1efc";s:14:"ext_tables.php";s:4:"e645";s:42:"Classes/Controller/FormcycleController.php";s:4:"828c";s:34:"Classes/Domain/Model/Formcycle.php";s:4:"3c59";s:27:"Classes/Helper/Fchelper.php";s:4:"2a8c";s:41:"Configuration/FlexForms/flexform_list.xml";s:4:"711b";s:31:"Configuration/TCA/Formcycle.php";s:4:"5c42";s:38:"Configuration/TypoScript/constants.txt";s:4:"89a9";s:34:"Configuration/TypoScript/setup.txt";s:4:"51da";s:40:"Resources/Private/Language/locallang.xml";s:4:"a6ed";s:78:"Resources/Private/Language/locallang_csh_tx_xmformcycle_domain_model_dummy.xml";s:4:"0012";s:38:"Resources/Private/Layouts/Default.html";s:4:"231a";s:47:"Resources/Private/Templates/Formcycle/List.html";s:4:"f375";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:60:"Resources/Public/Icons/tx_xmformcycle_domain_model_dummy.gif";s:4:"1103";s:11:"doc/de.html";s:4:"5893";s:11:"doc/en.html";s:4:"ea49";}',
	'clearcacheonload' => false,
);

