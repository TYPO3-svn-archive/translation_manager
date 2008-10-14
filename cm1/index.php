<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 TYPO3 UG Bremen <dev@typo3bremen.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

unset($MCONF);
require_once('conf.php');
require_once($BACK_PATH.'init.php');
require_once($BACK_PATH.'template.php');
require_once(PATH_t3lib.'class.t3lib_scbase.php');

/**
 * translation_manager module cm1
 *
 * @author	TYPO3 UG Bremen <dev@typo3bremen.org>
 * @package	TYPO3
 * @subpackage	tx_translationmanager
 */

class tx_translationmanager_cm1 extends t3lib_SCbase {
			

				/**
				 * Main function of the module. Only redirects to the main module.
				 *
				 *
				 */
				function main()	{
						Header('Location: ' . t3lib_div::locationHeaderUrl(t3lib_div::getIndpEnv(TYPO3_REQUEST_DIR) . '../mod1/index.php?&id=' . $this->id . '&SET[function]=1'));
				}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/translation_manager/cm1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/translation_manager/cm1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_translationmanager_cm1');
$SOBE->init();
$SOBE->main();

?>