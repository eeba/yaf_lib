#使用yaf框架应该


## 安装
[下载地址](https://pecl.php.net/package/yaf)

### Linux平台下安装：

```shell
$ cd ~
$ wget https://pecl.php.net/get/yaf-3.0.5.tgz
$ tar zxvf yaf-3.0.5.tgz
$ cd yaf-3.0.5
$ /usr/local/php/bin/phpize  
$ ./configure --with-php-config=/usr/local/php/bin/php-config 
$ make
$ make install

```
 打开php.ini
 
 ```shell
 $ vim  /usr/local/php/etc/php.ini
 ```
 在文件最后添加yaf扩展
 
 `extension=yaf.so`
 
 重启PHP
 
 ```shell
 $ /etc/init.d/php-fpm restart
 ```
 
 查看php扩展模块
 
 ```shell
 $ php -m
 ```
