<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * A set of helpers that span across the entire budgetmyfunds main application
 * Since these are helper methods, they should be close to pure functions.
 */
class BudgetMyFunds
{
    /**
     * The tag for labeling budgetmyfunds main application cache
     */
    protected const CACHE_TAG = 'budgetmyfunds';

    /**
     * Saves data to the budgetmyfunds cache  forever
     * since the budgetmyfunds cache is cleared after every successful pull
     * @param  array|string|Model $tags
     * @param  callable $callback
     * @return mixed
     */
    public static function cache($tags, $callback)
    {
        return cache()->tags(static::CACHE_TAG)->rememberForever(static::cacheKey($tags), $callback);
    }

    /**
     * Makes a cache key from the given tags
     * @param  Illuminate\Contracts\Support\Arrayable|Illuminate\Database\Eloquent\Model|array|string $tags
     * @return string
     */
    public static function cacheKey($tags)
    {
        switch ($tags) {
            case $tags instanceof Model:
                return sprintf('%s.%s', get_class($tags), $tags->getRouteKey());
            case $tags instanceof Arrayable:
                return implode($tags->toArray(), '.');
            case is_array($tags):
                return implode($tags, '.');
            default:
                return $tags;
        }
    }

    /**
     * Clears the budgetmyfunds cache
     * @return boolean
     */
    public static function clearCache()
    {
        return cache()->tags(static::CACHE_TAG)->flush();
    }

    /**
     * Clears a specific key value pair from the cache
     */
    public static function forget($key)
    {
        return cache()->tags(static::CACHE_TAG)->forget(static::cacheKey($key));
    }

    /**
     * Gets the value of a specific key from the cache
     *
     * $param array
     * @return mixed
     */
    public static function getKey(array $key)
    {
        return cache()->tags(static::CACHE_TAG)->get(static::cacheKey($key));
    }

    /**
     * Checks to see if the key exists in the cache
     *
     * @param string $key
     * @return boolean
     */
    public static function hasKey($key)
    {
        return cache()->tags(static::CACHE_TAG)->has(static::cacheKey($key));
    }

    /**
     * Forgets many keys in the cache
     *
     * @param array of string($keys)
     * @return boolean
     */
    public static function forgetMany(array $keys)
    {
        foreach ($keys as $key) {
            cache()->tags(static::CACHE_TAG)->forget(static::cacheKey([$key]));
        }
    }
}
