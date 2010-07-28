<?php
/**
 * JLVotes
 *
 * @version 1.3
 * @package com_jlvotes
 * @author Anton Voynov (anton@joomline.ru)
 * @copyright (C) 2010 by Anton Voynov(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgContentJlvotes extends JPlugin
{

	function plgContentJlvotes( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	function onPrepareContent( &$row, &$params, $limitstart ) {
		global $jlvotescount,$mainframe,$jlvotes_settings;

		JPlugin::loadLanguage( 'plg_content_jlvotes' );
		if ($row->id > 0) {
			$isdisabled = strpos($row->text, "{jlvotes off}");

			if ($isdisabled === false) {
				ob_start();
				$user =& JFactory::getUser();

				if (!isset($jlvotescount)) {

					$htmlscript = <<<HTML
					<script type="text/javascript">

					function getXHR() {
						var xhr = null;

						if (window.XMLHttpRequest) {
							xhr = new XMLHttpRequest();
						} else if (window.createRequest) {
							xhr = window.createRequest();
						} else if (window.ActiveXObject) {
							try {
								xhr = new ActiveXObject('Msxml2.XMLHTTP');
							} catch (e) {
								try {
									xhr = new ActiveXObject('Microsoft.XMLHTTP');
								} catch (e) {}
							}
						}

						return xhr;
					}

					function ajxjlVote(cid, vtype){

						cid = parseInt(cid);
						vtype = parseInt(vtype);
						if ( cid > 0 && vtype >= 0) {
							var xhr = getXHR();
							document.getElementById('loadimg_'+cid).style.display = 'inline';
							document.getElementById('voteButtons_'+cid).style.display = 'none';

							xhr.onreadystatechange = function(){
								if(xhr.readyState == 4 && xhr.status == 200) {
									var response = eval('(' + xhr.responseText + ')');
									document.getElementById('loadimg_'+cid).style.display = 'none';
									document.getElementById('voteButtons_'+cid).innerHTML = response.text+'<br />';
									document.getElementById('jllikecnt_'+cid).innerHTML = response.plus;
									document.getElementById('jlnotlikecnt_'+cid).innerHTML = response.minus;
									document.getElementById('voteButtons_'+cid).style.display = 'inline';
								}
							}
							xhr.open("POST","{uribase}components/com_jlvotes/ajax.php",true);
							xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
							xhr.send("cid="+cid+"&vtype="+vtype);
						}
					}
					</script>
					<link rel="stylesheet" href="{uribase}components/com_jlvotes/templates/jlvotes.css" type="text/css" />
HTML;
					$htmlscript = str_replace("{uribase}", JURI::base(),$htmlscript);
					$row->text = $htmlscript.$row->text;
					$db =& JFactory::getDBO();
					$db->setQuery("SELECT * FROM #__jlvotes_settings");
					$jlvotes_settings = $db->loadAssocList('name');

					if ($user->guest) {
						$hash = md5($_SERVER['REMOTE_ADDR']);
					} else {
						$hash = md5($user->id."|".$user->name);
					}

					$db->setQuery("SELECT COUNT(*) as votescount, content_id FROM #__jlvotes_data WHERE votetype = 0 GROUP BY content_id");
					$jlvotescount['plus'] = $db->loadAssocList('content_id');

					$db->setQuery("SELECT COUNT(*) as votescount, content_id FROM #__jlvotes_data WHERE votetype = 1 GROUP BY content_id");
					$jlvotescount['minus'] = $db->loadAssocList('content_id');

					$db->setQuery("SELECT content_id, votetype FROM #__jlvotes_data WHERE user = '$hash'");
					$jlvotescount['votes'] = $db->loadAssocList('content_id');

				}
				$uri = & JFactory::getURI();

				$id 	= $row->id;
				$votecountplus  = isset($jlvotescount['plus'][$id])  ? intval($jlvotescount['plus'][$id]['votescount']) : 0;
				$votecountminus = isset($jlvotescount['minus'][$id]) ? intval($jlvotescount['minus'][$id]['votescount']) : 0;
				$likeBtn = $notlikeBtn = '';

				if       (isset($jlvotescount['votes'][$id]) && $jlvotescount['votes'][$id]['votetype'] == 0) {
					$likeBtn = 'disabled';
				} elseif (isset($jlvotescount['votes'][$id]) && $jlvotescount['votes'][$id]['votetype'] == 1) {
					$notlikeBtn = 'disabled';
				}

				include(JPATH_BASE.DS.'components'.DS.'com_jlvotes'.DS.'templates'.DS.'plugin.tpl');

				$html = ob_get_clean();

				if ($jlvotes_settings['add2all']['value'] != 1) {
					$row->text = str_replace("{jlvotes}",$html,$row->text);
				} else {
					$row->text = str_replace("{jlvotes}","",$row->text);
					$row->text = $html.$row->text;

				}



			} else {
				$row->text = str_replace("{jlvotes}","",$row->text);
				$row->text = str_replace("{jlvotes off}","",$row->text);
			}



		}

	}


}
