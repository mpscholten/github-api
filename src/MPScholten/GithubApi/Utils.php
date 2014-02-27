<?php


namespace MPScholten\GithubApi;


class Utils
{
    public static function flatArrayKeyExists(array $array, $key)
    {
        $separatorPos = strpos($key, '.');

        if ($separatorPos === false) {
            return array_key_exists($key, $array);
        }

        $first = substr($key, 0, $separatorPos);
        if (array_key_exists($first, $array)) {
            return static::flatArrayKeyExists($array[$first], substr($key, $separatorPos + 1));
        }

        return false;
    }

    public static function flatArrayGet(array $array, $key)
    {
        $separatorPos = strpos($key, '.');

        if ($separatorPos === false) {
            return $array[$key];
        }

        $first = substr($key, 0, $separatorPos);
        return static::flatArrayGet($array[$first], substr($key, $separatorPos + 1));
    }
}
