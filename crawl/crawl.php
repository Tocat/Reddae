<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 *ʹ��΢��API��ȡ�û�����
 *�û�ʹ��ǰ��Ҫȥ΢������ƽ̨���룬Ȼ��������Ȩ��ȡ$access_token�ֶ������·�
 *$uidΪ��Ҫ��ȡ���û�΢��ID
 *
 */
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


$access_token="XXXXXXXXXX";
$c =new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token , $refresh_token = NULL);

//�������ݿ�
$my_connect = mysql_connect('127.0.0.1','yourusername','yourpassword');
$dbc = mysql_select_db('yourdbname',$my_connect);

//�����û���ע��˿���趨ѭ������
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