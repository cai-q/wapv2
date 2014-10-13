Readme
=======

- by： 蔡乾
- last modified： 2014-8-19 15:00
************
软件简介
----
该软件用于抓取PC端页面中有关信息，转换成手机端可以显示的页面。

- [需求文档](https://www.zybuluo.com/ronaldoooo/note/25962)
- [设计文档](https://www.zybuluo.com/ronaldoooo/note/26613)

以上文档仅作参考。项目实现过程中对初始设计有部分改动。

重要改动
-----

- 由于在url解析的时候会引起不必要的混乱，在数据库中存放的url地址均去掉了`http://`头部。

抓取方法
-----
1. 开启apache + mysql，确保数据库已被导入
2. 定位至`localhost/.../fetch.php`
3. 将url作为参数传入，如`fetch.php?url=http://news.cnhubei.com/xw/wuhan/201408/t3017764.shtml`
4. 抓取后生成的文件存放于`waproot`目录下，相关资源文件在`resource`目录下。

代码结构说明
--------

- resource/
- src/
	- class_mysql.php
	- imagebmp.php
	- models.php
	- toolbox.php
- templates/
- templates_c/
- waproot/
- admin.php
- common.php
- config.php
- fetch.php

##resource/
资源文件目录。从PC版网页上获取的如图片，视频地址，将资源文件转换后存放至此。在实际使用中，该文件夹不一定存在于项目目录中，甚至可能位于不同的服务器上。

##src/
源代码目录。除却和项目启动有关的逻辑之外，项目的底层代码均位于该文件夹之中。和项目启动有关的文件放在项目根目录中。

###class_mysql.php
该文件时自原版`uhome-center`软件中拷贝出来。其实是一个轻量的数据库存取模型，项目中所有和mysql数据库交换的过程均会通过此类来完成。

###imagebmp.php
该文件是针对bmp图像处理的文件，出自CSDN。由于php GD库自身并不支持bmp图像的处理。（bmp并不是合适的web图像。）该文件中的函数是仿照GD库中对jpeg的处理方法写成，在测试中执行效率比较低。

###toolbox.php
该文件包含了`uhome-center`中德原始文件`common_function`，在这里进行了面向对象化。如需调用其中方法，请通过类名静态调用。

在最新的版本中，还增加了两个函数，一个用于寻找最佳匹配模式，另一个用于计算转换后文件存储地址。

##templates/
该文件夹存放`Smarty`的模板文件。在该项目中，所有模板均继承自`base.tpl`。在子模板中，只对付模板中声明的块进行重写。

##template_c/
该文件夹是存放模板文件编译后对应的php文件。`Smarty`会自动管理这个过程。

##waproot/
该文件夹用于存放抓取出信息然后生成的页面，用于移动端显示。

##admin.php
该文件是预留的程序后台入口。在当前的版本中并未将后台移植进来。

##common.php
该文件包含了程序启动的一些必须的逻辑，包含了一些常量的定义，以及一些文件的引入。一般来说程序运行之前必须，也只需引入这一个文件。

##config.php
数据库配置文件。

##fetch.php
该文件是抓取逻辑的入口。

错误列表
--------

- 1001 图片不存在
- 1002 图片创建失败