<?php
/**
 * Util class.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

class Util
{
    /**
     * Extracts database uri.
     *
     * @param  string
     * @return array
     */
    public static function parseDatabaseUri($uri)
    {
        if (! preg_match('#^[^/]+://#', $uri)) {
            $uri = "mysql://{$uri}";
        }
        $parsed = \parse_url($uri);
        preg_match('#^/([^/]+)/([^/]+)#', $parsed['path'], $matches);
        $parsed['database'] = $matches[1];
        $parsed['table'] = $matches[2];
        if (empty($parsed['port'])) {
            $parsed['port'] = 3306;
        }
        if (empty($parsed['pass'])) {
            $parsed['pass'] = '';
        }
        return $parsed;
    }
}
