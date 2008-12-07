<?php
/**
 * class to represent the submodule configuration
 * 
 * @author Patrick Rodacker <patrick.rodacker@the-reflection.de>
 */

 
class translation_manager_configuration extends tx_translationmanager_module1 {


	/**
	 * init function which inits the submodule
	 */
	function init() {
		parent::init();
	}

	/**
	 * main function which returns the content
	 */
	
	function main() {
		
		// $this->doc->issueCommand("&data[sys_language][".$row['uid']."][hidden]=" . ($row['hidden'] ? 0 : 1))
		
		// check if there is a root template on the current page
		if ($this->pageHasRootTemplate()) {
			// get function
			$content = $this->getPageConfiguration();	
		} else {
			$content = $this->getRootTemplatePages();
		}
		return $content;
	}
	
	/**
	 * returns all pages with a root template on it
	 */
	function getRootTemplatePages() {
		
		// fetch pages with root templates
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'pages',
			'pages.uid IN (SELECT pid from sys_template WHERE root=1) AND hidden=0 AND deleted=0'
		);
		
		// 
		$content = $GLOBALS['LANG']->getLL('choose_config_page');
		$content .= '<br /><br />';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$content .= t3lib_iconWorks::getIconImage('pages', t3lib_BEfunc::getRecordWSOL('pages', $$row['uid']), $GLOBALS['BACK_PATH'], ' align="top" title="ID: '. $row['uid'] .'"');
			$content .= '<a href="' . t3lib_div::linkThisScript(array('id' => $row['uid'])) . '">' . $row['title'] . '</a><br />';
		}
		
		return $content;
	}
	
	/**
	 * returns the content for the configuration settings
	 */
	function getPageConfiguration() {
		global $BACK_PATH;
		
		// get all configured languages
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,title,flag',
			'sys_language',
			'hidden=0',
			'',
			'title'
		);
		
		// TODO: use lang file
		$content =  $GLOBALS['LANG']->getLL('choose_languages');
        $content .= '<br /><br />';
		
		// set css styles
		$this->doc->inDocStylesArray[] = '
				table#config tr.head td {padding: 2px 4px; background-color: #A2AAB8; font-weight:bold; color: #fff;}
				table#config tr td {padding: 0 4px;}
				table td { width: 350px; }
				#config .language { width: 350px;}
				#config .default { width: 350px;}						
			';
		
		// set table header
		$content .= '<table id="config">
                                <tr class="head">
                                        <td colspan="3" class="language">' . $GLOBALS['LANG']->getLL('available_languages') . '</td>
                                        <td class="default">' . $GLOBALS['LANG']->getLL('default_language') . '</td>
                                </tr>';
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$content .= '<tr>';
			$content .= '<td><input type="checkbox" name="language[' . $row['uid'] . ']" /></td>';
			$content .= '<td>' . $row['title'] . '</td>';
			$content .= '<td><img src="' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/flags/' . $row['flag'], '', 1) . '" alt="' . $row['flag'] . '"/></td>';
			$content .= '<td><input type="radio" name="default" /></td>';
			$content .= '</tr>';
		}
		$content .= '</table>';
		
		$content .= '<input type="submit" name="submit" value="' . $GLOBALS['LANG']->getLL('submit_generate_config') . '" />';
		
		return $content;
	}
	
	/**
	 * check if the current page has a root template
	 */
	function pageHasRootTemplate() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'sys_template',
			'pid=' . $this->id . 
				' AND root=1'
		);
		return $GLOBALS['TYPO3_DB']->sql_affected_rows();
	}
	
}



// make instance
$configuration = t3lib_div::makeInstance('translation_manager_configuration');
$configuration->init();
$content = $configuration->main();
?>