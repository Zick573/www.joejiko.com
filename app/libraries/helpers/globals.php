<?php
if ( ! function_exists('cdn_style_min'))
{
    /**
     * Generates the necessary HTML for a
     * CDN stylesheet link.
     *
     * @param  string $path
     * @return string
     */
    function cdn_style_min($path = 'styles.min.css')
    {
        $publicDirName = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28";
        $cssDirName = "css";

        $path = "$publicDirName/$cssDirName/$path";
        return "<link rel='stylesheet' href='{$path}'>";
    }
}

if ( ! function_exists('cdn'))
{
    /**
     * Generates the necessary HTML for a
     * CDN stylesheet link.
     *
     * @param  string $path
     * @return string
     */
    function cdn($path = 'https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/')
    {
        return $path;
    }
}

if ( ! function_exists('cdn_img'))
{
    /**
     * Generates the necessary HTML for a
     * CDN stylesheet link.
     *
     * @param  string $path
     * @return string
     */
    function cdn_img($path = 'https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/')
    {
        return $path;
    }
}

if ( ! function_exists('js_path'))
{
    /**
     * Generates the necessary HTML for a
     * CDN stylesheet link.
     *
     * @param  string $path
     * @return string
     */
    function js_path($path = '/assets/js/')
    {
        return $path;
    }
}