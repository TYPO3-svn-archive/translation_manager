<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE == 'BE')	{
		
	t3lib_extMgm::addModule('web','txtranslationmanagerM1','',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
}


if (TYPO3_MODE=="BE")	{
	$GLOBALS["TBE_MODULES_EXT"]["xMOD_alt_clickmenu"]["extendCMclasses"][]=array(
		"name" => "tx_translationmanager_cm1",
		"path" => t3lib_extMgm::extPath($_EXTKEY)."class.tx_translationmanager_cm1.php"
	);
}

t3lib_extMgm::addStaticFile($_EXTKEY,'static/Translation_Manager/', 'Translation Manager');
?>