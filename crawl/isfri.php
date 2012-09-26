<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 *使用微博API获取之前爬取的某用户互粉用户间的关系，为后面的聚类分析社团划分做准备
 *此处使用单向的关注关系，即表示A与B间有联系
 *根据互粉用户间关系可以初步判读A与B的关系，在R语言划分出来的图中很明显的看出来不同的社团
 *此程序只针对普通用户
 */
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


$access_token="XXXXXXXXXX";
$c =new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token , $refresh_token = NULL);

//连接数据库
$my_connect = mysql_connect('127.0.0.1','yourusername','yourpassword');
$dbc = mysql_select_db('yourdbname',$my_connect);

/*根据互粉数设定循环次数
 *注：获取的关系会出现重复BUG，暂时未发现原因，建议在数据库中设置唯一
 */

for($id = 1;$id<50;$id++)
{
$sql = "SELECT `uid` FROM `user` WHERE `id` = ".$id." LIMIT 0, 60 ";
$done = mysql_query($sql,$my_connect);
while($detail = mysql_fetch_row($done))
{
	echo $detail[0];
	$uid = $detail[0];	
	ob_flush() ;
	flush() ;
}	

echo "<br>";

$res = $c->friends_ids_by_id( $uid, $cursor = 0, $count = 2000 );
print_r($res);


for($j=0;;$j++)
{
	if($res['ids'][$j]==null)break;
	
	$sql1 = "SELECT `id` FROM `user` WHERE `uid` = ".$res['ids'][$j]." LIMIT 0, 60 ";
	$done = mysql_query($sql1,$my_connect);
	while($detail = mysql_fetch_row($done))
	{
		echo $detail[0];
		$id2 = $detail[0];	
		ob_flush() ;
		flush() ;
	}

	if($id2!=null)
	{
		$sql2 = "INSERT INTO `yourdbname`.`rela` (`id`, `uid1`, `uid2`) VALUES (NULL, '".$id."', '".$id2."');";
		mysql_query($sql2,$my_connect);
		echo $id2,"<br>";
	}
	$id2=null;
}
}