<?php

/**
 * Helpers
 */
class Helpers
{
    /**
     * Constructor disabled
     */
    private function __construct()
    {

    }

    /**
     * Get value from environment
     *
     * @static
     * @param string $key
     *
     * @return mixed
     */
    public static function getEnv($key)
    {
        $env   = getenv($key);
        $value = strtolower($env);

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if ($value === 'null') {
            return null;
        }

        if (is_numeric($env)) {
            return is_int($env) ? (int) $env : (float) $env;
        }

        return $env;
    }
}
