<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 *ʹ��΢��API��ȡ֮ǰ��ȡ��ĳ�û������û���Ĺ�ϵ��Ϊ����ľ���������Ż�����׼��
 *�˴�ʹ�õ���Ĺ�ע��ϵ������ʾA��B������ϵ
 *���ݻ����û����ϵ���Գ����ж�A��B�Ĺ�ϵ����R���Ի��ֳ�����ͼ�к����ԵĿ�������ͬ������
 *�˳���ֻ�����ͨ�û�
 */
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


$access_token="XXXXXXXXXX";
$c =new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token , $refresh_token = NULL);

//�������ݿ�
$my_connect = mysql_connect('127.0.0.1','yourusername','yourpassword');
$dbc = mysql_select_db('yourdbname',$my_connect);

/*���ݻ������趨ѭ������
 *ע����ȡ�Ĺ�ϵ������ظ�BUG����ʱδ����ԭ�򣬽��������ݿ�������Ψһ
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