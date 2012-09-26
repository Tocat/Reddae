<?php

/*
 *Tocat
 *2012/8/30
 *爬取用户关注和粉丝
 代码只实现了基本功能大家可以自行补充

使用方法：
1.先手动打开weibo.cn网站，注意是.cn！手机微博网站。
2.登录微博，然后获取地址栏参数gsid
3.将参数复制到源码的gsid处
4.把服务器的超时时间设长一点，让PHP有足够时间运行
5.将需要爬取的用户ID替换代码中的百度的ID
6.运行

补充：
1.现在只实现了输出主页地址和用户名，没有保存到数据库中，大家可以自行添加 
2.只需要修改下URL就可以爬取用户粉丝列表
3.PHP效率较低，大量爬取可以用FACEBOOK的Hiphop将其转为c++，@https://github.com/facebook/hiphop-php
4.交流请联系:tocat(在)outlook.com
 */

header("Content-Type: text/html; charset=utf-8");      //将字符集定义为UTF-8

/*
 *定义爬虫类
 *
 */
class Spiderlb
{
	private $content ;       //用于储存爬去的页面源代码
	private $lastcontent = 1;   //类似于指针的东西
		
	/*
	 *根据URL获取页面源文件
	 *
	 */
	function getContent ( $url )
	{
		$this->content = file_get_contents($url);
	}
	
	/*
	 *解析页面获取总关注用户数
	 *解析方法不同语言有不同方法
	 *以下以PHP为例
	 */
	function getAlluser()
	{
		$start = strpos( $this->content , 'class="tc"'  ) ;		//从原文件中查找第一个'class="tc"'，返回位置存在$start变量中
		$start = strpos( $this->content , '>' , $start )+8 ;	//从$start位置开始查找">"符号获取的位置+8后存在$start中
		$end = strpos( $this->content , '</span>' , $start )-1 ;//从$start位置开始查找'</span>'的位置-1后存在$end中
		return  substr( $this->content , $start , $end-$start ) ;//返回$start和$end中的内容
		
	}
	
	/*
	 *获取粉丝页数
	 */
	function getAllpage()
	{
		$start = strpos( $this->content , 'value="跳页"'  ) ;
		$start = strpos( $this->content , '>' , $start )+9 ;
		$end = strpos( $this->content , '</div>' , $start )-3 ;
		return  substr( $this->content , $start , $end-$start ) ;
		
	}
	
	/*
	 *通过解析页面检查是否返回错误
	 *
	 */
	function check($start)
	{
		if( substr( $this->content , $start , 3 )=="XHT") //如果从$start位置开始的三个字符是XHT则返回错误（有实验测试得到）
		return TRUE;
		else
		return FALSE;
	}
	
	/*
	 *获取关注用户
	 *参数是最后一页用户数，默认为10
	 *还是用老办法解析
	 */
	function getUser($lastpagecount = 10)
	{
		
		$start = strpos( $this->content , 'alt="pic"') ;
		$start = strpos( $this->content , '>' , $start )+36 ;
		
		if($this->check($start))
		{}
		else
		{
		
			$end = strpos( $this->content , '"' , $start ) ;
			echo "http://weibo.cn".substr( $this->content , $start , $end-$start ) ;//输出用户主页地址
		
		
		
			$start = $end+2 ;
			$end = strpos( $this->content , '</a>' , $start ) ;
			echo substr( $this->content , $start , $end-$start )."<br>" ;  //输出用户名
		
			$this->lastcontent = $end ;    //将指针指向$end即此部分的末尾，防止重复获取
		
			
			//一般每页10个，也可能有意外会少于10个，所以再循环9次，并用check()判断
			for($i=1;$i<$lastpagecount;$i++)
			{
			
			
				$start = strpos( $this->content , 'alt="pic"',$this->lastcontent) ;
				$start = strpos( $this->content , '>' , $start )+36 ;
			
				if($this->check($start))
				break;
			
				$end = strpos( $this->content , '"' , $start ) ;
				echo "http://weibo.cn".substr( $this->content , $start , $end-$start ) ;
			
				$start = $end+2 ;
				$end = strpos( $this->content , '</a>' , $start ) ;
				echo $uid2 = substr( $this->content , $start , $end-$start )."<br>" ;
			
				$this->lastcontent = $end ;
				
				
				
				
			
			}
		
		}
		
	}
	
	
}

$b = new Spiderlb() ;

//在此以百度微博为例，
$baseurl = 'http://weibo.cn/2525235360/follow' ;
$loginparam = '&gsid=XXXXXXXXXXXXXXXXX' ;  //gsid获取方法见README

//从page=1开始历遍
$url = $baseurl."?page=1".$loginparam ;
	
$b->getContent ( $url ) ;  


$alluser = $b->getAlluser();
$allpage = $b->getAllpage();

//如果总用户少于10个，获取一次即可
if($alluser<=10)
{
	$b->getUser();
}
else  
{
	/*
	 *否则进行翻页历遍，此处算法按理想情况每页10个用户来历遍
	 *但是实际可能会出现每页少于10个的情况
	 *所以可以用其他方法判断，即不查找总页数，页数持续增加，当页面的第一条用check()出现错误即停止
	 */
	$lastpagecount = $alluser%10 ;

	$b->getUser();
	for ($i = 2 ; $i <= $allpage ; $i++ )
	{
		$url = $baseurl."?page=".$i.$loginparam ;
		
		if($i==$allpage)
		{
			$b->getContent ( $url ) ;
			$b->getUser($lastpagecount);
			ob_flush() ; //这两个函数是为了持续输出设置，没有实际意义，可忽略
			flush() ;
		}
		else
		{
			$b->getContent ( $url ) ;
			$b->getUser();
			ob_flush() ;
			flush() ;
		}
		
	
	
	}
}

echo "关注用户数为：".$alluser."<br>"."总页数：".$allpage ;
