<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 *使用微博API获取用户互粉
 *用户使用前需要去微博开放平台申请，然后自行授权获取$access_token手动填入下方
 *$uid为需要爬取的用户微博ID
 *
 */
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


$access_token="XXXXXXXXXX";
$c =new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token , $refresh_token = NULL);

//连接数据库
$my_connect = mysql_connect('127.0.0.1','yourusername','yourpassword');
$dbc = mysql_select_db('yourdbname',$my_connect);

//根据用户关注粉丝量设定循环次数
$page = 1;
for($i=0;$i<3;$i++)
{
	$res = $c->bilateral_ids( $uid = 'XXXXXXXX', $page , $count = 50, $sort = 0);
	print_r($res);
	
	$page = $page+1;
	
	for($j=0;$j<50;$j++)
	{
		
		if($res['ids'][$j]!=NULL)
		{
			$sql = "INSERT INTO `yourdbname`.`user` (`id`, `uid`, `status`, `time`) VALUES (NULL, '".$res['ids'][$j]."', '0', '0');";
			mysql_query($sql,$my_connect);
			
		}
	}
	ob_flush() ;
	flush() ;

}