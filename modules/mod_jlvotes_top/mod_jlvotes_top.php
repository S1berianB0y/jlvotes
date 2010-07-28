<?php
/**
* Module JLvotes Top Rated 
* 
* Displays top rated (with jlvotes) articles
* @author Anton Voynov (anton@joomline.ru)
* @copyright (C) 2010 by Anton Voynov(http://www.joomline.ru)
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
**/
// No direct access
defined('_JEXEC') or die('Restricted access');
function jlv_getVotes($params) {
	$db = & JFactory::getDBO();
	$db->setQuery("	SELECT count(*) as vc, count(*)*1.1 as k,  content_id as ci 
					FROM #__jlvotes_data 
					WHERE votetype = 0
					GROUP BY ci
					UNION
					SELECT count(*) as vc, -count(*) as k, content_id as ci 
					FROM #__jlvotes_data 
					WHERE votetype = 1
					GROUP BY ci
					");
	$rows = $db->loadObjectList();

	if (count($rows) > 0)
	foreach ($rows as $row) {
		$votes[$row->ci] 		+= $row->k;
		$totalvotes[$row->ci] 	+= $row->vc;
		if ($row->k < 0) 
			$voters[$row->ci]['minus'] = $row->vc;
		else 
			$voters[$row->ci]['plus']  = $row->vc;
	}
	arsort($votes);
	$votes 				= array_slice($votes,0,intval($params->get('max_items')),true);
	
	return array($votes, $totalvotes,$voters);
}

function jlv_getArticles($params,$votes) {
	$show_image 		= $params->get('show_image') 		== 1 ? true : false;
	$show_readmorebtn 	= $params->get('show_readmorebtn') 	== 1 ? true : false;
	
	$thumb_path = JPATH_BASE.DS.'images'.DS.'stories'.DS.'jlvotesmod_thumb';
	$thumb_path_site = JURI::base().'/images/stories/jlvotesmod_thumb';
	$db = & JFactory::getDBO();
	$db->setQuery('	SELECT c.id, c.title, c.introtext,
					CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as slug,
					CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(":", cat.id, cat.alias) ELSE cat.id END as catslug,
					c.sectionid
					FROM #__content c 
					LEFT JOIN #__categories cat ON c.catid = cat.id
					WHERE c.id IN ('.implode(", ", array_keys($votes)).')
					');
	$articles = $db->loadObjectList('id');
	$comp = & JComponentHelper::getComponent('com_content');
	$menus = JSite::getMenu();
	$items = $menus->getItems('componentid', $comp->id);
	$com_content_Itemid = (count($items)) ? $items[0]->id : 0;
	unset($items);

	if (count($articles) > 0)
	foreach ($articles as $k=>$v) {
		if ($show_image && preg_match("/<img.*?src=(?:\"|')(.*?)(?:\"|').*?>/ixm",$articles[$k]->introtext,$img) > 0) {
			$img = explode("/",$img[1]);
			
			if (!file_exists($thumb_path))
				mkdir($thumb_path);
				
			if ($img[0] == 'http:')	{
				$remote_addr = implode('/',$img);
				$filename = implode("_",array_slice($img,3));
				if (!file_exists($thumb_path.DS.'tmp'))
					mkdir($thumb_path.DS.'tmp');
				
				$orig_path = $thumb_path.DS.'tmp'.DS.$filename;
				copy($remote_addr, $orig_path);
				
				
			} else {
				$filename = implode("_",array_slice($img,1));
				$orig_path = JPATH_BASE.DS.implode(DS,$img);
				
				
			}
			
			if (!file_exists($thumb_path.DS.$filename)) {
				ljvm_img_resize($orig_path,
								$thumb_path,
								$filename,
								intval($params->get('thumb_w')),
								intval($params->get('thumb_h'))
								);
			}

			
			$articles[$k]->image = $thumb_path_site.'/'.$filename;
		} else {
			$articles[$k]->image = false;
		}
		$articles[$k]->introtext = preg_replace("|<.*?>|ixm","",$articles[$k]->introtext); 
		$articles[$k]->introtext = preg_replace("|{.*?}|ixm","",$articles[$k]->introtext); 
		$articles[$k]->introtext = preg_replace("|\\n|ixm","",$articles[$k]->introtext); 
		$words = explode(" ",$articles[$k]->introtext);
		$words_sliced = array_slice($words,0,intval($params->get('max_words')));
		$articles[$k]->introtext = implode(" ",$words_sliced); 
//		$articles[$k]->link = JRoute::_("index.php?option=com_content&view=article&id=$k:{$articles[$k]->alias}&catid={$articles[$k]->catid}:{$articles[$k]->cat_alias}&Itemid=".$com_content_Itemid);
		$articles[$k]->link = JRoute::_(ContentHelperRoute::getArticleRoute($articles[$k]->slug,$articles[$k]->catslug,$articles[$k]->sectionid));

		if (count($words_sliced) < count($words) && $show_readmorebtn) {
			$articles[$k]->readmore .= "<br clear='both'><a class=\"readon\" style='font-size:7pt' href=\"{$articles[$k]->link}\">".JText::_('READMORE')."</a><br/>";
		} else {
			$articles[$k]->readmore = false;
		}
	}
	
	return $articles;
}

$cache 		= & JFactory::getCache('mod_jlvotes_top');
list( $votes, $totalvotes, $voters ) = $cache->call( 'jlv_getVotes',$params);	

if (count($votes) > 0) {
	$articles = $cache->call( 'jlv_getArticles',$params, $votes);	
    
    $show_image 		= $params->get('show_image') 		== 1 ? true : false;
    $titleaslink 		= $params->get('titleaslink') == 1 ? true : false;
    $showvotescount 	= $params->get('showvotescount') == 1 ? true : false;
    $showlikecount 		= $params->get('showlikecount') == 1 ? true : false;
    $showdontlikecount 	= $params->get('showdontlikecount') == 1 ? true : false;
    $showtotalrating 	= $params->get('showtotalrating') == 1 ? true : false;
    $showintro 			= $params->get('showintro') == 1 ? true : false;
    $displaystyle 		= $params->get('displaystyle');
    
    if ($displaystyle == 0) {
		foreach ($votes as $ci=>$vc) { ?>
			<h4 style="margin: 0px;">
				<?php if ($titleaslink) : ?>
					<a href="<?=$articles[$ci]->link?>">
						<?=$articles[$ci]->title?>
					</a>
				<?php else :?>
					<?=$articles[$ci]->title?>
			    <?php endif; ?>
			    
			</h4>
			<?php if ($showvotescount) : ?>
				<span style="font-size: 7pt; color: gray;"><?=JText::sprintf('TOTALVOTED',$totalvotes[$ci])?></span>
			<?php endif; ?>
			<?php if ($showlikecount) : ?>
				<span style="font-size: 7pt; color: gray;"><?=JText::sprintf('LIKEDCNT',$voters[$ci]['plus'])?></span>
			<?php endif; ?>
			<?php if ($showdontlikecount) : ?>
				<span style="font-size: 7pt; color: gray;"><?=JText::sprintf('DNTLIKEDCNT',$voters[$ci]['minus'])?></span>
			<?php endif; ?>
			<?php if ($showtotalrating) : ?>
				<span style="font-size: 7pt; color: gray;"><?=JText::sprintf('TOTALRATING',$vc)?></span>
			<?php endif; ?>
			<br/>
			<?php if ($articles[$ci]->image) :?>
				<div style="width:<?=intval($params->get('thumb_w'))+4?>px;height: <?=intval($params->get('thumb_h'))+4?>px; float: left; margin-right:2px;">
					<img src="<?=$articles[$ci]->image?>" align="left" border="0" />
				</div>
				
			<?php endif; ?>

			<?php if ($showintro) :?>
				<?=$articles[$ci]->introtext?>
			<?php endif; ?>
			
			<?php if ($articles[$ci]->readmore) :?>
				<?=$articles[$ci]->readmore?>
			<?php endif; ?>
			
			<br clear="both"><hr>

		<?php }	
	} elseif($displaystyle == 1) { ?>
		<style type="text/css"> 
		table.jlvotestop {
			border:1px solid gray;
			border-collapse: collapse;
		}
		table.jlvotestop th, table.jlvotestop td {
			padding: 5px;
			border:1px solid gray		
		}
		table.jlvotestop th {
			text-align: center;
		}
		
		</style> 	
		<table cellpadding="0" cellspacing="0" border="0" class="jlvotestop">
			<thead>
				<tr>
					<th>№</th>
					<?php if ($show_image) : ?>
						<th>JLIMAGE</th>
					<?php endif; ?>
						<th>JLTITLE</th>
					<?php if ($showvotescount) : ?>
						<th>#</th>
					<?php endif; ?>
					<?php if ($showlikecount) : ?>
						<th>+</th>
					<?php endif; ?>
					<?php if ($showdontlikecount) : ?>
						<th>-</th>
					<?php endif; ?>
					<?php if ($showtotalrating) : ?>
						<th>=</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				
				<?php $i=0; foreach ($votes as $ci=>$vc) {  $i++; ?>
					<tr>
						<td><?=$i?></td>
					<?php if ($show_image) : ?>
						<td>
							<?php if ($articles[$ci]->image) :?>
								<div style="width:<?=intval($params->get('thumb_w'))+4?>px;height: <?=intval($params->get('thumb_h'))+4?>px; float: left; margin-right:2px;">
									<img src="<?=$articles[$ci]->image?>" align="left" border="0" />
								</div>
								
							<?php endif; ?>
						</td>
					<?php endif; ?>
						<td>
							<?php if ($titleaslink) : ?>
								<a href="<?=$articles[$ci]->link?>">
									<?=$articles[$ci]->title?>
								</a>
							<?php else :?>
								<?=$articles[$ci]->title?>
							<?php endif; ?>
						</td>
					<?php if ($showvotescount) : ?>
						<td><?=$totalvotes[$ci]?></td>
					<?php endif; ?>
				
				
					<?php if ($showlikecount) : ?>
						<td><?=$voters[$ci]['plus']?></td>
					<?php endif; ?>
				
				
					<?php if ($showdontlikecount) : ?>
						<td><?=$voters[$ci]['minus']?></td>
					<?php endif; ?>
				
				
					<?php if ($showtotalrating) : ?>
						<td><?=$vc?></td>
					<?php endif; ?>
						
	                </tr>
				<?php }	?>
				<tr></tr>
			</tbody>
		</table>
		
		
	<?php }
}

function ljvm_img_resize( $tmpname, $save_dir, $save_name, $maxwidth = 80, $maxheight = 60 ) {

	$save_dir	.= ( substr($save_dir,-1) != DS) ? DS : "";
	
	if (list($width, $height, $type, $attr)=getimagesize($tmpname)) {
		$ht=$height;
		$wd=$width;
		if($width>$maxwidth){
			$diff = $width-$maxwidth;
			$percnt_reduced = (($diff/$width)*100);
			$ht = $height-(($percnt_reduced*$height)/100);
			$wd= $width-$diff;
		}
		
		if($ht>$maxheight){
			$diff = $height-$maxheight;
			$percnt_reduced = (($diff/$height)*100);
			$wd = $width-(($percnt_reduced*$width)/100);
			$ht= $height-$diff;
		} 
		
		
		switch($type) {
			case "1": $imorig = imagecreatefromgif($tmpname); break;
			case "2": $imorig = imagecreatefromjpeg($tmpname);break;
			case "3": $imorig = imagecreatefrompng($tmpname); break;
			default:  $imorig = imagecreatefromjpeg($tmpname);
		}
		
		$im = imagecreatetruecolor($wd,$ht);
		if (imagecopyresampled($im,$imorig , 0,0,0,0,$wd,$ht,$width, $height)) {
			if (imagejpeg($im, $save_dir.$save_name)) {return true;}
			else {return false;}
		} else {
			return false;
		}			
	} else {
		return false;
	}

}

?>
<div style="text-align: center;">
	<em>
		<span style="color: #c0c0c0; font-family: arial,helvetica,sans-serif; font-size: 5pt;">
			<a target="_blank" href="http://joomline.ru/">JoomLine</a>
		</span>
	</em>
</div>