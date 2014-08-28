<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>php feed reader demo</title>
<style type="text/css">
*{
font-family:Arial, Helvetica, sans-serif;
}
h1{margin:0;}
b{color:#333333;font-size:.8em}
form {
width:500px;
margin:auto;
background:#FFCC66;
padding:10px;
text-align:center;
}
div {
margin:auto;
padding:4px;
background:#EAEAEA;
width:600px;
border-bottom:solid thick #FFFFFF;
}
span{
display:block;
text-align:center;
}
</style>
</head>
<body>
<form action="<?=$_SERVER['PHP_SELF']; ?>" method="post">
<b>Enter Feed Url</b><br />
<input name="url" type="text" value="<?=$_POST['url']; ?>" style="width:400px;"><input name="Submit" type="submit" value="Get Feed">
</form>
<?
if(isset($_POST['url'])&&$_POST['url']!="")
{
$url=$_POST['url'];
include_once('Simple/autoloader.php');
$feed = new SimplePie();
$feed->set_feed_url($url);
$feed->enable_cache(false);
$feed->set_output_encoding('Windows-1252');
$feed->init();
echo "<span><h1>".$feed->get_title()."</h1>";
echo "<b>".$feed->get_description()."</b></span><hr />";
$itemCount=$feed->get_item_quantity();
$items=$feed->get_items();
foreach($items as $item)
{
?>
<div><a href="<?=$item->get_permalink(); ?>"><?=$item->get_title(); ?></a><br />
<em style="font-size:.7em;color:#666666"><?=$item->get_date(); ?></em>
<? 
if ($category = $item->get_category())
echo "Category: ".$category->get_label();
?>
<br />
<?=$item->get_description(); ?></div>
<?
}
}
?>
</body>
</html>
