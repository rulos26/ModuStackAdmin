<?php

if (!function_exists('core_version')) {
    /**
     * Obtener la versión del módulo Core
     *
     * @return string
     */
    function core_version(): string
    {
        return '1.0.0';
    }
}

if (!function_exists('core_config')) {
    /**
     * Obtener configuración del módulo Core
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function core_config(string $key, $default = null)
    {
        return config("core.{$key}", $default);
    }
}

if (!function_exists('core_log')) {
    /**
     * Log específico del módulo Core
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    function core_log(string $message, string $level = 'info'): void
    {
        \Log::channel('single')->{$level}("[Core] {$message}");
    }
}

