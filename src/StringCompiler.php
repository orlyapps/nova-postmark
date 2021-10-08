<?php

namespace Orlyapps\NovaPostmark;

use Illuminate\View\Factory;

/**
 * Kompiliert Blade Strings in HTML
 */
class StringCompiler
{
    public static function compile($value, array $args = [])
    {
        $args = array_merge($args, ['__env' => app(Factory::class)]);

        $generated = \Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);
        // We'll include the view contents for parsing within a catcher
        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try {
            eval('?>' . $generated);
        }
        // If we caught an exception, we'll silently flush the output
        // buffer so that no partially rendered views get thrown out
        // to the client and confuse the user with junk.
        catch (\Exception $exception) {
            ob_get_clean();

            throw $exception;
        }

        return ob_get_clean();
    }
}
