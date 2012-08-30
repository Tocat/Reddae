这是一个基于PHP的简易新浪微博爬虫，可以爬取给定USER的关注和粉丝列表

代码只实现了基本功能大家可以自行补充

使用方法：<br>
1.先手动打开weibo.cn网站，注意是.cn！手机微博网站。<br>
2.登录微博，然后获取地址栏参数gsid<br>
3.将参数复制到源码的gsid处<br>
4.把服务器的超时时间设长一点，让PHP有足够时间运行<br>
5.将需要爬取的用户ID替换代码中的百度的ID<br>
6.运行<br>

补充：<br>
1.现在只实现了输出主页地址和用户名，没有保存到数据库中，大家可以自行添加 <br>
2.只需要修改下URL就可以爬取用户粉丝列表<br>
3.PHP效率较低，大量爬取可以用FACEBOOK的Hiphop将其转为c++，@https://github.com/facebook/hiphop-php<br>
4.交流请联系:tocat(在)outlook.com<br>
