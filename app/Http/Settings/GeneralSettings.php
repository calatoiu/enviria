<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $text_factura;

    public static function group(): string
    {
        return 'general';
    }
}
