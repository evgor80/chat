<?php

namespace App\Utils;

class SlugGenerator
{
    private static $pairs = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'kh',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    ];

    /**
     * Generate slug from given room name by lowercasing it, removing all not-alphanumerical charecters and replacing spaces with dashes
     * 
     * @param string $name
     * @return string
     */
    public static function generate(string $name)
    {
        //convert original name to lowercase
        $name = mb_strtolower($name);
        //create regex pattern from russian letters
        $pattern = '/' . implode('|', array_keys(self::$pairs)) . '/i';
        //replace russian letters in original name with their english matchers from given array
        $slug = preg_replace_callback(
            $pattern,
            function ($matches) {
                return implode("", array_map(function ($match) {
                    return self::$pairs[$match];
                }, $matches));
            },
            $name
        );
        //remove all non-alphanumeric symbols from the slug
        $slug = preg_replace('/[^\w ]+/', "", $slug);
        //replace all spaces in the slus with dashes
        $slug = preg_replace('/ +/', "-", $slug);
        //remove dashes from first and last positions in string, if any
        $slug = preg_replace('/^-|-$/', '', $slug);

        return $slug;
    }
}