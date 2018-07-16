<?php

namespace vendor\core;


class Router
{
    /**
     * массив маршрутов
     * @var array
     */
    protected static $routes = [];

    /**
     * текущий маршрут
     * @var array
     */
    protected static $route = [];

    /**
     * добавляет маршрут в Router::$routes
     * @param string $regexp Шаблон регулярки
     * @param array $route
     */
    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    /**
     * ищет URL в массиве маршрутов
     * @param string $url
     * @return bool
     */
    protected static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Перенаправляет URL
     * @param string $url
     * @return void
     */
    public static function dispatch($url)
    {
        $url = self::removeQueryStr($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'];
            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                } else {
                    echo "контроллер $controller не имеет экшена $action";
                }
            } else {
                echo "контроллер $controller не найден";
            }
        } else {
            include '404.html';
        }
    }

    /**
     * преобразует $name в CamelCase
     * @param string $name
     * @return string
     */
    protected static function upperCamelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    /**
     * преобразует первую букву в нижний регистр
     * @param string $name
     * @return string
     */
    protected static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

    /**
     * Отрезает GET параметры
     * @param string $url
     * @return string
     */
    protected static function removeQueryStr($url)
    {
        if ($url) {
            $param = explode('&', $url, 2);
            if (false === strpos($param[0], '=')) {
                return rtrim($param[0], '/');
            } else {
                return '';
            }
        }
    }
}