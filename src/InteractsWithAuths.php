<?php
/**
 * Desc:
 * User: circle
 * Date: 2024/4/3
 * Email: <yeh110@qq.com>
 **/

namespace yeh110\annotation;

use Ergebnis\Classy\Constructs;
use yeh110\annotation\auth\Auth;
use ReflectionClass;
use ReflectionMethod;
class InteractsWithAuths
{
    public static function run($controllerDir)
    {
        $data        = [];
        $parsedClass = [];
        $classFiles  = Constructs::fromDirectory($controllerDir);
        foreach ($classFiles as $construct) {
            $class = $construct->name();
            if (in_array($class, $parsedClass))
                continue;
            $parsedClass[] = $class;
            $refClass      = new ReflectionClass($class);
            if ($refClass->isAbstract() || $refClass->isInterface() || $refClass->isTrait())
                continue;
            if ($Controllers = (new Reader)->getAnnotation($refClass, \yeh110\annotation\auth\Auths::class)) {
                $items = [];
                foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $refMethod) {
                    if ($Methods = (new Reader)->getAnnotation($refMethod, Auth::class)) {
                        $items[] = [
                            'name'   => $Methods->name,
                            'option' => $Methods->options
                        ];
                    }
                }
                $data[] = [
                    'name'    => $Controllers->name,
                    'option'  => $Controllers->options,
                    'methods' => $items
                ];
            }
        }
        unset($parsedClass);
        return $data;
    }
}