<?php

namespace App\Services;

class NameParserService
{
    public static function parse(string $name): array
    {
        $titles = 'Mr|Mrs|Ms|Dr|Prof|Mister';

        $patterns = [
            // Standard Name: Mr John Smith
            '/^(' . $titles . ')\s+(\w+)\s+(\w+)$/i' => function ($matches) {
                return [
                    'title' => $matches[1],
                    'first_name' => $matches[2],
                    'initial' => null,
                    'last_name' => $matches[3],
                ];
            },
            // Format with initials: Mr J. Smith
            '/^(' . $titles . ')\s+(\w)\.\s+(\w+)$/i' => function ($matches) {
                return [
                    'title' => $matches[1],
                    'first_name' => null,
                    'initial' => $matches[2],
                    'last_name' => $matches[3],
                ];
            },
            // Married couple with shared last name
            '/^(' . $titles . ')\s+and\s+(' . $titles . ')\s+(\w+)$/i' => function ($matches) {
                return [
                    ['title' => $matches[1], 'first_name' => null, 'initial' => null, 'last_name' => $matches[3]],
                    ['title' => $matches[2], 'first_name' => null, 'initial' => null, 'last_name' => $matches[3]],
                ];
            },
            // Married couple with "&" or "and" in title (e.g., Dr & Mrs Joe Bloggs or Dr and Mrs Joe Bloggs)
            '/^(' . $titles . ')\s+(and|&)\s+(' . $titles . ')\s+(\w+)\s+(\w+)$/i' => function ($matches) {
                return [
                    ['title' => $matches[1], 'first_name' => null, 'initial' => null, 'last_name' => $matches[4]],
                    ['title' => $matches[3], 'first_name' => null, 'initial' => null, 'last_name' => $matches[4]],
                ];
            },
            // Multiple people: Mr Tom Staff and Mr John Doe
            '/^(' . $titles . ')\s+(\w+)\s+(\w+)\s+and\s+(' . $titles . ')\s+(\w+)\s+(\w+)$/i' => function ($matches) {
                return [
                    ['title' => $matches[1], 'first_name' => $matches[2], 'initial' => null, 'last_name' => $matches[3]],
                    ['title' => $matches[4], 'first_name' => $matches[5], 'initial' => null, 'last_name' => $matches[6]],
                ];
            },
            // Hyphenated last names (e.g., Mrs Hughes-Eastwood)
            '/^(' . $titles . ')\s+(\w+)\s+([A-Za-z\-]+)$/i' => function ($matches) {
                return [
                    'title' => $matches[1],
                    'first_name' => $matches[2],
                    'initial' => null,
                    'last_name' => $matches[3],
                ];
            },
        ];

        // Loop through patterns and try to match the name
        foreach ($patterns as $pattern => $callback) {
            if (preg_match($pattern, $name, $matches)) {
                $parsed = $callback($matches);
                return is_array(reset($parsed)) ? $parsed : [$parsed];
            }
        }

        throw new \InvalidArgumentException("Unrecognized name format: $name");
    }
}
