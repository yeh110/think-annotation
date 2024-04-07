<?php
/**
 * Desc: 节点获取
 * User: circle
 * Date: 2024/4/3
 * Email: <yeh110@qq.com>
 **/

namespace yeh110\annotation;

use Ergebnis\Classy\Constructs;
use think\helper\Str;
use yeh110\annotation\auth\Auth;
use ReflectionClass;
use ReflectionMethod;

class InteractsWithAuths
{
    public static function run($controllerDir)
    {
        $data             = [];
        $parsedClass      = [];
        $classFiles       = Constructs::fromDirectory($controllerDir);
        $controllerSuffix = config('route.controller_suffix') ? 'Controller' : '';
        foreach ($classFiles as $construct) {
            $class = $construct->name();
            if (in_array($class, $parsedClass))
                continue;
            $parsedClass[] = $class;
            $refClass      = new ReflectionClass($class);
            if ($refClass->isAbstract() || $refClass->isInterface() || $refClass->isTrait())
                continue;

            $filename = $construct->fileNames()[0];

            $prefix = $class;
            if (Str::startsWith($filename, $controllerDir)) {
                //控制器
                $filename = Str::substr($filename, strlen($controllerDir));
                $prefix   = str_replace($controllerSuffix . '.php', '', str_replace('/', '.', $filename));
            }

            if ($Controllers = (new Reader)->getAnnotation($refClass, \yeh110\annotation\auth\Auths::class)) {
                $items = [];
                foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $refMethod) {
                    if ($Methods = (new Reader)->getAnnotation($refMethod, Auth::class)) {
                        $is_login = isset($Methods->options['login']) && $Methods->options['login'] == 0 ? 0 : 1;
                        $is_auth  = isset($Methods->options['auth']) && $Methods->options['auth'] == 0 ? 0 : 1;
                        $is_auth  = $is_login == 0 ? 0 : $is_auth;
                        $items[]  = [
                            'name'       => $Methods->name,
                            'type'       => 'method',
                            'controller' => $prefix,
                            'method'     => $refMethod->getName(),
                            'node'       => "{$prefix}/{$refMethod->getName()}",
                            'is_auth'    => $is_auth,
                            'is_login'   => $is_login,
                            'option'     => $Methods->options,
                        ];
                    }
                }
                $is_login = isset($Controllers->options['login']) && $Controllers->options['login'] == 0 ? 0 : 1;
                $is_auth  = isset($Controllers->options['auth']) && $Controllers->options['auth'] == 0 ? 0 : 1;
                $is_auth  = $is_login == 0 ? 0 : $is_auth;
                $data[]   = [
                    'name'       => $Controllers->name,
                    'type'       => 'controller',
                    'controller' => $prefix,
                    'node'       => $prefix,
                    'is_auth'    => $is_auth,
                    'is_login'   => $is_login,
                    'option'     => $Controllers->options,
                    'methods'    => $items,
                ];
            }
        }
        unset($parsedClass);
        return $data;
    }
}