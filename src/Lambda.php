<?php
namespace Spindle\Stream;

final class Lambda
{
    static function cache($args, $code)
    {
        static $cache = array();
        if (!isset($cache[$args][$code])) {
            $cache[$args][$code] = create_function($args, $code);
        }
        return $cache[$args][$code];
    }

    static function create($args, $mixed, $return = true)
    {
        static $callable;
        if (empty($callable)) {
            $callable = version_compare(\PHP_VERSION, '5.4.0', '>');
        }

        if (is_callable($mixed)) {
            if ($callable) return $mixed;
            if (is_string($mixed)) return $mixed;
            if (is_array($mixed)) {
                list($obj, $method) = $mixed;
                if (is_string($obj)) {
                    return "$obj::$method";
                } else {
                    return function() use($mixed) {
                        $args = func_get_args();
                        return call_user_func_array($mixed, $args);
                    };
                }
            }
            return $mixed;
        } elseif (is_string($mixed)) {
            return self::cache($args, $return ? "return $mixed;" : "$mixed;");
        }
    }

    static function createArgsString($num)
    {
        $num = (int)$num;
        if ($num < 1) {
            return '';
        }
        if ($num === 1) {
            return '$_';
        }
        $args = array();
        $varname = 'a';
        while ($num--) {
            $args[] = '$' . $varname++;
        }

        return implode(',', $args);
    }
}
