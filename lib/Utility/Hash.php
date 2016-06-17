<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/17
 * Time: 8:42
 * Description
 */

namespace Cake\Utility;

class Hash
{
    public static function get($data, $path, $default = null)
    {
        if (!(is_array($data) || $data instanceof ArrayAccess)) {
            throw new InvalidArgumentException(
                'Invalid data type, must be an array or \ArrayAccess instance.'
            );
        }

        if (empty($data) || $path === null || $path === '') {
            return $default;
        }

        if (is_string($path) || is_numeric($path)) {
            $parts = explode('.', $path);
        } else {
            if (!is_array($path)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid Parameter %s, should be dot separated path or array.',
                    $path
                ));
            }

            $parts = $path;
        }

        switch (count($parts)) {
            case 1:
                return isset($data[$parts[0]]) ? $data[$parts[0]] : $default;
            case 2:
                return isset($data[$parts[0]][$parts[1]]) ? $data[$parts[0]][$parts[1]] : $default;
            case 3:
                return isset($data[$parts[0]][$parts[1]][$parts[2]]) ? $data[$parts[0]][$parts[1]][$parts[2]] : $default;
            default:
                foreach ($parts as $key) {
                    if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        return $default;
                    }
                }
        }

        return $data;
    }

    public static function insert(array $data, $path, $values = null)
    {
        $noTokens = strpos($path, '[') === false;
        if ($noTokens && strpos($path, '.') === false) {
            $data[$path] = $values;
            return $data;
        }

        if ($noTokens) {
            $tokens = explode('.', $path);
        } else {
            $tokens = Text::tokenize($path, '.', '[', ']');
        }

        if ($noTokens && strpos($path, '{') === false) {
            return static::_simpleOp('insert', $data, $tokens, $values);
        }

        $token = array_shift($tokens);
        $nextPath = implode('.', $tokens);

        list($token, $conditions) = static::_splitConditions($token);

        foreach ($data as $k => $v) {
            if (static::_matchToken($k, $token)) {
                if (!$conditions || static::_matches($v, $conditions)) {
                    $data[$k] = $nextPath
                        ? static::insert($v, $nextPath, $values)
                        : array_merge($v, (array)$values);
                }
            }
        }
        return $data;
    }
}