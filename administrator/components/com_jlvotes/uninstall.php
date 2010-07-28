<?php
$db = & JFactory::getDBO();

echo "Plugin uninstall...";

$plg_dst_path = JPATH_SITE.DS."plugins".DS;
unlink($plg_dst_path."content".DS."jlvotes.php");
unlink($plg_dst_path."content".DS."jlvotes.xml");

unlink($plg_dst_path."editors-xtd".DS."jlvotesonbtn.php");
unlink($plg_dst_path."editors-xtd".DS."jlvotesonbtn.xml");

unlink($plg_dst_path."editors-xtd".DS."jlvotesoffbtn.php");
unlink($plg_dst_path."editors-xtd".DS."jlvotesoffbtn.xml");


$db->setQuery("	DELETE FROM `#__plugins` WHERE element = 'jlvotes' OR element = 'jlvotesonbtn' OR element = 'jlvotesoffbtn'");
$db->query();

echo "...uninstall finished.";

?>
