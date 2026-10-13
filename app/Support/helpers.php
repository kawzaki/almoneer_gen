<?php

use App\Support\ArabicNumber;

if (!function_exists('arabic_years')) {
    function arabic_years(int $count, bool $includeNumber = true): string
    {
        return ArabicNumber::years($count, $includeNumber);
    }
}

if (!function_exists('arabic_tags')) {
    function arabic_tags(int $count, bool $includeNumber = true): string
    {
        return ArabicNumber::tags($count, $includeNumber);
    }
}

if (!function_exists('arabic_topics')) {
    function arabic_topics(int $count, bool $includeNumber = true): string
    {
        return ArabicNumber::topics($count, $includeNumber);
    }
}

if (!function_exists('arabic_lectures')) {
    function arabic_lectures(int $count, bool $includeNumber = true): string
    {
        return ArabicNumber::lectures($count, $includeNumber);
    }
}

if (!function_exists('arabic_items')) {
    function arabic_items(int $count, bool $includeNumber = true): string
    {
        return ArabicNumber::items($count, $includeNumber);
    }
}

if (!function_exists('arabic_plural')) {
    function arabic_plural(
        int $count,
        string $singular,
        string $dual,
        string $plural3to10,
        string $accusative11to99,
        ?string $hundreds = null,
        bool $includeNumber = true
    ): string {
        return ArabicNumber::format($count, $singular, $dual, $plural3to10, $accusative11to99, $hundreds, $includeNumber);
    }
}
