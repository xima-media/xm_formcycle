 <?php
 
 include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));
 
 
 class tx_sampleflex_addFieldsToFlexForm {
  function addFields ($config) {
  	
	//error_reporting(E_ALL);
	//ini_set('display_errors',1);
	
	$fch = new FcHelper();

	$sessId = $fch->getSessionId();
	$json = file_get_contents($fch->getProjectListUrl());
	$user_lang = $GLOBALS['BE_USER']->uc['lang'];
	$json_data = json_decode($json, true);
	$projekteArray = $json_data['result'];


    $optionList = array();
	
	$lang = "en";
	
	if($user_lang == 'de') $lang="de";
	
	$zahl = count($projekteArray);
	for($count = 0; $count < $zahl; $count++)
	{
		if($projekteArray[$count]['aktiv'] == 'true') {
			$formVersion =$projekteArray[$count]['activeVersion']['versionsNummer'];
			$projektId = $projekteArray[$count]['id'];
			$projektName = $projekteArray[$count]['name'];
  			$optionList[$count] = array(0 => $projektName, 1 => '/fd2/xfd2.jsp?vn='.$formVersion.'&lang='.$lang.'&pid='. $projektId);
		}
	}
	
    $config['items'] = array_merge($config['items'],$optionList);
    return $config;
  }
 }
 ?>