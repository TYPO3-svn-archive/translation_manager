<?php

class translation_manager_management extends tx_translationmanager_module1 {

	/**
	 * init function which inits the submodule
     *
     */
	function init() {
		parent::init();
	}

	/**
	 * main function which returns the content
     *
     */
	 
	function main() {
					global $BACK_PATH;
                    $this->doc = t3lib_div::makeInstance('mediumDoc');
                    $this->doc->backPath = $BACK_PATH;

					$content = '';


					// BEGIN SUBMIT ACTION 'add_language':
					// installs the selected language
					if(t3lib_div::_GP('add_language') != '') {
						$lang_ok = true;
						$lang_label = trim(t3lib_div::_GP('language_label'));
						
					  // Get info from static tables for choosen language
					  $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
									'uid',
									'static_languages',
									'uid = '.(int)t3lib_div::_GP('new_lang')
  					);
  					
  					// Language unknown or label empty?!
						$lang_ok = ($lang_label != '') && ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res));
						
						if($lang_ok) {
							// Get flag for language from locale
							$flag_file = strtolower($row['lg_iso_2']).'.gif';
	            if (!file_exists($BACK_PATH.'gfx/flags/'.$flag_file)) {
	              $flag_file = "unknown.gif"; 
	            }
							
							// Insert/ install  language
	            $GLOBALS['TYPO3_DB']->exec_INSERTquery(
	                  'sys_language',
	                  Array('pid' => 0, 
	                        'tstamp' => mktime(), 
	                        'title' => $lang_label, 
	                        'flag' => $flag_file,
	                        'static_lang_isocode' => $row['uid']
	                  )
						   );
						} else {
							// an error occured
							t3lib_div::debug("Error!");
						}	
					}
					// END SUBMIT ACTION
					
					
					
					// Get all installed shown and hidden languages
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
									'uid, hidden, flag, title',
									'sys_language',
									'1 =1'
					);
					$count = $GLOBALS['TYPO3_DB']->sql_affected_rows();
					
					$subcontent = '<table>';

					// Print all installed shown and hidden languages
					while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						$state = ($row['hidden'] ? 'unhide' : 'hide');
						
						//add image for hide and unhide the language
						$enable_img = sprintf('
								<a href="%s"><img src="%s" " title="%s" alt="%s" /></a>
							',
							$this->doc->issueCommand("&data[sys_language][".$row['uid']."][hidden]=" . ($row['hidden'] ? 0 : 1)),	
							t3lib_iconWorks::skinImg($BACK_PATH, "sysext/t3skin/icons/gfx/button_" . $state . ".gif", "", 1),
							$GLOBALS['LANG']->getLL($state),
							$GLOBALS['LANG']->getLL($state)
						);


            // delete language
            $delete_img = sprintf('
								<a href="%s" %s><img src="%s" " title="%s" alt="%s" /></a>
							',
							$this->doc->issueCommand("&cmd[sys_language][".$row['uid']."][delete]=1"),
              //'onclick = "return confirm(unescape(\''.rawurlencode('Are you sure you want to delete this element?').'\'));"',
              'onclick = "return confirm(\''.sprintf($GLOBALS['LANG']->getLL('delete_language_alert'),$row['title']).'\');"', 	
							t3lib_iconWorks::skinImg($BACK_PATH, "sysext/t3skin/icons/gfx/garbage.gif", "", 1),
							$GLOBALS['LANG']->getLL('delete'),
							$GLOBALS['LANG']->getLL('delete')
						);

						
						//add flag for the actual language
						$flag_img = '<img src="'.t3lib_iconWorks::skinImg($BACK_PATH, "gfx/flags/".$row['flag'], "", 1).'" alt="'.$row['title'].' flag" />';
						
						$subcontent .= sprintf('
								<tr>
									<td>%s</td><td>%s</td><td>%s</td><td>%s</td>
								</tr>
							',
							$row['title'],
							$flag_img,
							$enable_img,
							$delete_img
						);
				}
				$subcontent .= '</table>';
				
				
				$content .= $this->doc->section($GLOBALS['LANG']->getLL('section_language_list'),$subcontent);
        $content .= $this->doc->spacer(15);
        //$content .= $this->doc->divider(5);        				
        				
				// Get all languages in static_info_tables
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'uid, lg_name_en',
								'static_languages',
								'uid NOT IN (SELECT static_lang_isocode FROM sys_language)',
								'',
								'lg_name_en'
				);
				
				$subcontent = '<table>';
				$subcontent .= '<tr><td>'.$GLOBALS['LANG']->getLL('section_language_add_name').'</td>';
				$subcontent .= '<td><select name="new_lang" onchange="document.getElementById(\'language_label\').value=document.getElementById(\'option_\'+this.value).text;">';
				
        $input_label = false;
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$subcontent .= sprintf(
						'<option value="%d" id="option_%d">%s</option>',
						$row['uid'],
						$row['uid'],
						$row['lg_name_en']
					);
					if ($input_label === false) {
            $input_label = $row['lg_name_en'];
          }
				}
				$subcontent .= '</select></td>';
        $subcontent .= '<tr><td>'.$GLOBALS['LANG']->getLL('section_language_add_label').'</td>';
        $subcontent .= '<td><input type="text" id="language_label" name="language_label" value="'.$input_label.'" /></td></tr>';					
				$subcontent .= '<tr><td><input type="submit" name="add_language" value="' . $GLOBALS['LANG']->getLL('submit_add_language') . '" /></td><td>&nbsp;</td></tr>';
        $subcontent .= '</table>';
        $content .= $this->doc->section($GLOBALS['LANG']->getLL('section_language_add'),$subcontent);
        return $content;
    }
}


// make instance
$management = t3lib_div::makeInstance(translation_manager_management);
$management->init();
$content = $management->main();

?>