# beyod app项目模板
beyod是框架核心库，一般你无须安装此。
beyod-app是项目模板，请安装此项目以进行开发。

### 一、安装指南, 使用以下任何一个方法均可安装beyod-app项目结构
1. 使用composer安装
```
composer create-project --prefer-dist beyoio/beyod-app beyod-app
composer update
```
2. 下载基本结构包并解压后安装依赖   
https://github.com/beyoio/beyod-app/releases   
 安装依赖。

```
composer update
```

3. 如果github/composer下载过慢，可直接下载zip包
  https://codeload.github.com/beyoio/beyod-app/zip/master
  
### 二、启动
进入项目目录中，执行以下命令运行演示代码：
```
php beyod.php server/start
```

### 三、参考文档

见 beyod-app/docs/index.html


## beyod: 一个高性能分布式、事件驱动、异步非阻塞php socket网络应用框架


beyod是基于Libevent/epoll/Yii2 Framework的高性能分布式、事件驱动、异步非阻塞php实现的socket网络服务开发框架。 具备可扩展的网络协议支持架构，可以开发任何TCP/UDP层上的网络应用。可用于物联网、网络游戏、WEB、WebSocket、服务器推送、分布式网络应用服务等领域。

PHP被广泛用于web开发领域，但在socket服务方式较少使用，导致形成了一种错误的观点：PHP只能用于网站。

事实上，PHP通过模块方式可以扩展自身功能，内置的socket支持, 完全可以实现一个socket服务器。

借助Libui扩展，也可以实现桌面软件界面的开发。

beyod,  是beyond的缩写，意思是另一个，另一边的的意思，所以beyod的目的也是寻求php在另外一个领域的(socket服务器)实践。

==beyod本质是以PHP命令行方式运行实现完整的socket服务器，所以它无须nginx/Apache/php-fpm环境，就可以独立运行。==

beyod吸收并借鉴了Nginx/ReactPHP/Workerman/Swoole/Yii/等流行的技术方案，从而实现快速开发网络应用服务。

特性：
1. 事件驱动、异步非阻塞、多进程单线程架构（Nginx/node.js相同的进程架构）。
2. 纯PHP代码实现，所有代码开源，开发参考易于上手。
3. 高性能，PHP命令行长驻内存方式运行, 省去了传统的基于请求的资源分配和释放。另外得益于基于libevent非阻塞网络模型，不必为每个连接分配线程或进程。  
4. 支持大量并发连接，理论上，只要内存足够大，连接数是无上限的。测试中，单机10万tcp连接时，消耗内存仅2.4GB。 
5. 稳定可靠，可长时间运行, 工作进程崩溃自动恢复。  
使用Master-worker方式的多进程、单线程模型。实现了工作进程异常崩溃后的自动重启,  我们熟知的Nginx也使用了Master-worker进程模型，从而实现进程高可用性。
6. 丰富的网络协议支持  
支持TCP、UDP、Unix、SSL, 内置HTTP/WebSocket/Async Redis/Async TCP Client, 并支持自定义数据包解析，从而实现任何应用层协议。
7. SSL/reuse_port/cluster dispatcher/工作进程平滑重启等特性， 单个进程中可实现多端口监听、多个协议支持。
8. 毫秒级定时器。
9. 基于Yii2 Console框架开发，Yii2是一个事件驱动高性能主流PHP框架，内置完整的PHP命令行运行支持，内置功能模块丰富，组件化架构使整个系统易于扩展。
10. 丰富的数据库支持和组件化扩展。  
得益于yii框架的底层支持，支持大量的业务层功能需求，熟悉Yii web框架者可以直接上手实现业务层功能。  
  beyod不仅是一个网络底层开发框架，更是一个网络应用开发框架，可快速实现业务功能。
11. 易于扩展，beyod使用composer/psr标准结构，可以很容易使用第三方PHP框架实现业务功能。
