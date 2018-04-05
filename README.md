# 购物车
yaf+opcache for mvc+orm to api

## 涉及技术
#### 前端
- requirejs:模块化和异步加载
- bootstrap,yeti:响应式框架,支持手机访问,yeti主题
- forp:页面响应性能,访问index_forp.php

#### 后端
- smarty/twig:php模板引擎,默认关闭
- memcached:kv快速存取,默认关闭
- medoo:orm数据库半框架,library/Db.php

**yaf.ini文件详细说明:**
```
[yaf]
extension=yaf.so
yaf.environ = product
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0     // 如果使用类,可以开启
yaf.use_spl_autoload = 0  // 冒泡获取自动加载器
```


### 目录结构

对于Yaf的应用, 都应该遵循类似下面的目录结构.

本项目的目录说明
```
+ public
  |- index.php //入口文件
  |- index_forp.php //性能测试入库
  |- .htaccess //重写规则
  |- favicon.jpg
  |+ css
  |+ images
  |+ js
+ conf
  |- application.ini //配置文件
+ application
  |+ controllers
     |- Index.php //默认控制器
  |+ views    
     |+ index   //控制器
     |- index.phtml //默认视图
  |- Bootstrap.php //项目的全局配置,包括路由和memcached的配置等
  |- yaf_classes.php //yaf框架的函数列表,方便补全
+ modules //其他模块
+ library //本地类库
+ models  //model目录
+ plugins //插件目录
+ tests   //测试目录
+ globals   //插件目录和全局配置
  |+ cache  //模板生成的缓存文件
  |+ composer         //composer下载的lib
     |- composer.json //composer的依赖配置
```



### 重写规则

除非我们使用基于query string的路由协议(Yaf_Route_Simple, Yaf_Route_Supervar), 否则我们就需要使用WebServer提供的Rewrite规则, 把所有这个应用的请求, 都定向到上面提到的入口文件.

#### Apache的Rewrite (httpd.conf)
.htaccess
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php
```
当然也可以写在httpd.conf[option]
```
DocumentRoot "path/public" #需要定位到本项目的public文件夹
<Directory "path/public">
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule .* index.php
</Directory>
```


#### Nginx的Rewrite (nginx.conf)
```
root path/public #需要定位到本项目的public文件夹
location / {
    try_files $uri $uri/ /index.php;
}
```


#### Lighttpd的Rewrite (lighttpd.conf)
```
$HTTP["host"] =~ "(www.)?domain.com$" {
  url.rewrite = (
     "^/(.+)/?$"  => "/index.php/$1",
  )
}
```


#### SAE的Rewrite (config.yaml)
```
name: your_app_name
version: 1
handle:
    - rewrite: if(path ~ "^(?!public/)(.*)") goto "/public/$1"
    - rewrite: if(!is_file()) goto "/public/index.php"
```

或者在SAE面板
appconfig->rewrite->高级设置->直接在大框框下填入下面的内容->保存
```
    - rewrite: if(path ~ "^(?!public/)(.*)") goto "/public/$1"
    - rewrite: if(!is_file()) goto "/public/index.php"
```
