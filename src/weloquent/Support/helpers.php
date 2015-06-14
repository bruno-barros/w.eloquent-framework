<?php
/**
 * Helpers
 */

if (!function_exists('assets'))
{
    /**
     * Full url to assets folder.
     *
     * @see themes/base/app/config/assets.php
     *
     * @param string $assetsName
     *
     * @return string
     */
    function assets($assetsName = '')
    {
        return Config::get('assets.url') . '/' . trim($assetsName, '/');
    }
}

if (!function_exists('share'))
{
    /**
     * View::share('name', 'data');
     *
     * @param string $var
     * @param mixed  $data
     */
    function share($var, $data = null)
    {
        \Illuminate\Support\Facades\View::share($var, $data);
    }
}


if (!function_exists('lang'))
{
    /**
     * Load string locale from app text domain
     *
     * @param      $string
     * @param null $context
     * @param null $txtdom
     * @param bool $scape
     *
     * @return string|void
     */
    function lang($string, $context = null, $txtdom = null, $scape = false)
    {
        $td = (!$txtdom) ? Config::get('app.textdomain') : $txtdom;

        if ($context)
        {
            return _x($string, $context, $td);
        }

        return __($string, $td);
    }
}


if (!function_exists('classes_str'))
{
    /**
     * Return post classes separated by space
     *
     * @param string $extra
     *
     * @return string
     */
    function classes_str($extra = '')
    {
        $classes = get_post_class($extra);

        return implode(' ', $classes);
    }
}

if (!function_exists('set_value'))
{
    /**
     * Fill input if there is value on session
     *
     * @param        $field
     * @param string $sessionName Default 'fields'
     *
     * @return null
     */
    function set_value($field, $sessionName = 'fields')
    {
        if (Session::has("{$sessionName}.{$field}"))
        {
            $value = Session::get("{$sessionName}.{$field}");
            Session::forget("{$sessionName}.{$field}");

            return $value;
        }

        return null;
    }
}

if (!function_exists('form_field_error'))
{
    /**
     * Return the first error from a field
     *
     * @param String $field Input name
     *
     * @return string
     */
    function form_field_error($field)
    {
        $html = '';
        if (Session::has('errors') && Session::get('errors')->has($field))
        {
            $html = '<label for="field_' . $field . '" class="error" >';
            $html .= Session::get('errors')->first($field);
            $html .= '</label >';
        }

        return $html;
    }
}


if (!function_exists('env'))
{
    /**
     * retrieve the ENV var
     *
     * @param      $envVar
     * @param null $default
     *
     * @return null|string
     */
    function env($envVar, $default = null)
    {
        if (!$value = getenv($envVar))
        {
            return $default;
        }

        return $value;
    }
}
