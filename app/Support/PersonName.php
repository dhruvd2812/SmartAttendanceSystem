<?php

namespace App\Support;

class PersonName
{
    /*
    |--------------------------------------------------------------------------
    | Human Readable Name
    |--------------------------------------------------------------------------
    |
    | Several accounts were created with an email address stored in the name
    | column. Anywhere we greet or label a person we only want the human part,
    | never the domain:
    |
    |   namra@faculty.com  -> Namra
    |   john.doe@x.edu     -> John Doe
    |   Dr. Priya Shah     -> Dr. Priya Shah   (left untouched)
    |
    */

    public static function human(?string $value, string $fallback = 'User'): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $fallback;
        }

        if (! str_contains($value, '@')) {
            return $value;
        }

        $local = strstr($value, '@', true);

        if ($local === false || trim($local) === '') {
            return $fallback;
        }

        // "john.doe" / "john_doe" / "john-doe" -> "John Doe"
        $local = preg_replace('/[._\-]+/', ' ', $local);
        $local = trim(preg_replace('/\s+/', ' ', $local));

        return $local === '' ? $fallback : ucwords($local);
    }

    /*
    |--------------------------------------------------------------------------
    | Initial (for avatar bubbles)
    |--------------------------------------------------------------------------
    */

    public static function initial(?string $value, string $fallback = 'U'): string
    {
        $name = static::human($value, '');

        return $name === '' ? $fallback : mb_strtoupper(mb_substr($name, 0, 1));
    }
}
