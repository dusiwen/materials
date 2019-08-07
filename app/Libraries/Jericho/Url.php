<?php

namespace Jericho;
class Url
{
    /**
     * repeat for orientation to some url
     * @author JerichoPH
     *
     * @param string $URL target url
     */
    public static function redirect($URL)
    {
        $URL = str_replace(array("\n", "\r"), '', $URL);
        header('Location:' . trim($URL));
    }

    /**
     * url地址转换为route
     * @param null $URL 手动输入地址 如果为空则获取当前地址
     * @param int $ROUTE_MAX_LENGTH
     * @return array|string
     */
    public static function toRoute($URL = null, $ROUTE_MAX_LENGTH = 3)
    {
        # 获取当前路由
        $currentRoute = strtolower($URL);
        if (is_empty($URL)) $currentRoute = strtolower($_SERVER['PHP_SELF']);

        # 判断路由是否带有.php文件
        $currentRoute = trim($currentRoute, '/');
        $ex = explode('/', $currentRoute);

        # 合法化最长获取长度
        $ROUTE_MAX_LENGTH = $ROUTE_MAX_LENGTH < count($ex) ? $ROUTE_MAX_LENGTH : count($ex);

        $newRoute = '';
        if (preg_match("/^.+\..+$/i", $ex[0])) {
            #带有.php
            for ($i = 0; $i < $ROUTE_MAX_LENGTH; $i++) {
                $newRoute .= $ex[$i] . '/';
            }
            $newRoute = rtrim($newRoute, '/');
        } else {
            #不带.php
            for ($i = 0; $i < $ROUTE_MAX_LENGTH; $i++) {
                $newRoute .= $ex[$i] . '/';
            }
            $newRoute = rtrim($newRoute, '/');
        }

        #去掉新路由最后的html/htm/php
        $newRoute = explode('.', $newRoute);
        $newRoute = $newRoute[0];

        return $newRoute;
    }

    /**
     * 获取当前URI
     * @param boolean $toLower 是否转小写
     * @return string
     */
    public static function toUri($toLower = false)
    {
        $current_uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $current_uri = rtrim($current_uri, '.html');
        $current_uri = rtrim($current_uri, '.htm');
        $current_uri = trim($current_uri, '/');
        if ($toLower) $current_uri = strtolower($current_uri);
        return $current_uri;
    }

    /**
     * repeat for orientation to some url and prompt message when success done
     * @author JerichoPH
     * @param string $URL target url
     * @param string $MSG prompt content
     * @param int $WAIT wait second when repeat orientation to url. default wait 1 second.
     */
    public static function redirectSuccess($URL, $MSG, $WAIT = 1)
    {
        $html = "
<br>
	<div style='border: 2px solid dodgerblue; border-radius: 4px; background-color: lightblue; height: 300px;

	width: 75%; text-align: center; color: black; font-size: 18px; margin: 0 auto;'>
	<br><br>
	<p style='font-size: 28px;'><b>执行成功！</b></p>
	{$MSG}
</div>
	";
        if (!headers_sent()) {
            // redirect
            if (0 === $WAIT) {
                header('Location: ' . $URL);
            } else {
                header("refresh:{$WAIT};url={$URL}");
                echo($html);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$WAIT};URL={$URL}'>";
            if ($WAIT != 0)
                $str .= $html;
            exit($str);
        }
    }

    /**
     * repeat for orientation to some url and prompt message when fail done
     * @author JerichoPH
     *
     * @param string $URL target url
     * @param string $MSG prompt content
     * @param int $WAIT wait second when repeat orientation to url. default wait 1 second.
     */
    public static function redirectFail($URL, $MSG, $WAIT = 1)
    {
        $html = "
<br>
	<div style='border: 2px solid red; border-radius: 4px; background-color: pink; height: 500px;

	width: 75%; text-align: center; color: black; font-size: 18px; margin: 0 auto;'>
	<br><br>
	<p style='font-size: 28px;'><b>糟糕，出错了！</b></p>
	{$MSG}
</div>
	";
        if (!headers_sent()) {
            // redirect
            if (0 === $WAIT) {
                header('Location: ' . $URL);
            } else {
                header("refresh:{$WAIT};url={$URL}");
                echo($html);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$WAIT};URL={$URL}'>";
            if ($WAIT != 0)
                $str .= $html;
            exit($str);
        }
    }

    /**
     * 数组转URL参数
     * @param array $params 待转换数组
     * @param string $url url
     * @return string
     */
    public static function buildParams($params, $url = null)
    {
        $query = http_build_query($params);
        return $url == null ? $query : $url . '?' . $query;
    }
}