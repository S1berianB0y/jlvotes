<?php
$db = & JFactory::getDBO();

echo "Plugin install...";
$plg_src_path = JPATH_SITE.DS."administrator".DS."components".DS."com_jlvotes".DS."plugins".DS;
$plg_dst_path = JPATH_SITE.DS."plugins".DS;
$copyresult = rename($plg_src_path."content".DS."jlvotes.php", $plg_dst_path."content".DS."jlvotes.php");
$copyresult = rename($plg_src_path."content".DS."jlvotes.xml", $plg_dst_path."content".DS."jlvotes.xml");

$copyresult = rename($plg_src_path."editors-xtd".DS."jlvotesonbtn.php", $plg_dst_path."editors-xtd".DS."jlvotesonbtn.php");
$copyresult = rename($plg_src_path."editors-xtd".DS."jlvotesonbtn.xml", $plg_dst_path."editors-xtd".DS."jlvotesonbtn.xml");

$copyresult = rename($plg_src_path."editors-xtd".DS."jlvotesoffbtn.php", $plg_dst_path."editors-xtd".DS."jlvotesoffbtn.php");
$copyresult = rename($plg_src_path."editors-xtd".DS."jlvotesoffbtn.xml", $plg_dst_path."editors-xtd".DS."jlvotesoffbtn.xml");

$db->setQuery("	DELETE FROM `#__plugins` WHERE element = 'jlvotes' OR element = 'jlvotesonbtn' OR element = 'jlvotesoffbtn'");
$db->query();

$db->setQuery("	INSERT INTO `#__plugins` 
					(name,element,folder,published,ordering) 
				VALUES 
					('JLvotes main plugin','jlvotes','content',1,0),
					('JLvotes editor button ON','jlvotesonbtn','editors-xtd',1,91),
					('JLvotes editor button OFF','jlvotesoffbtn','editors-xtd',1,92)
			");
$db->query();


rmdir($plg_src_path."content");
rmdir($plg_src_path."editors-xtd");
rmdir($plg_src_path);

echo "...install finished.";

?>
