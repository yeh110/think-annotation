# think-annotation for ThinkPHP6,ThinkPHP8

> PHP8版本

## 安装

> composer require yeh110/think-annotation

## 配置

> 配置文件位于 `config/annotation.php`

## 使用方法

### 路由注解

~~~php
<?php

namespace app\controller;

use yeh110\annotation\Inject;
use yeh110\annotation\route\Get;
use yeh110\annotation\route\Group;
use yeh110\annotation\route\Middleware;
use yeh110\annotation\route\Resource;
use yeh110\annotation\route\Route;
use think\Cache;
use think\middleware\SessionInit;

#[Group("bb")]
#[Resource("aa")]
#[Middleware([SessionInit::class])]
class IndexController
{

    #[Inject]
    protected Cache $cache;

    public function index()
    {
        //...
    }

    #[Route('GET','xx')]
    public function xx()
    {
        //...
    }
    
    #[Get('cc')]
    public function cc()
    {
        //...
    }
}

~~~

> 默认会扫描controller目录下的所有类  
> 可对个别目录单独配置

```php
//...
    'route'  => [
        'enable'      => true,
        'controllers' => [
            app_path('controller/admin') => [
                'name'       => 'admin/api',
                'middleware' => [],
            ],
            root_path('other/controller')
        ],
    ],
//...
```

### 模型注解

~~~php
<?php

namespace app\model;

use think\Model;
use yeh110\annotation\model\relation\HasMany;

#[HasMany("articles", Article::class, "user_id")]
class User extends Model
{

    //...
}
~~~

### 权限注解

~~~php
<?php

namespace app\controller;

use yeh110\annotation\auth\Auths;
use yeh110\annotation\auth\Auth;

#[Auths("控制器名称")]
class User
{
    #[Auth('方法一')]
    public function xx()
    {
        //...
    }
    #[Auth('方法二', ['auth'=>false])]
    public function xx()
    {
        //...
    }
    //...
}
~~~

### 权限注解读取

~~~php
<?php

use yeh110\annotation\InteractsWithAuths;

$dir  = app_path('controller');
$data = InteractsWithAuths::run($dir);
print_r($data);

~~~

