<?php

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency
     */
    function format_currency($amount, $currency = 'LKR')
    {
        return $currency . ' ' . number_format($amount, 2);
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date string
     */
    function format_date($date, $format = 'd/m/Y')
    {
        if ($date instanceof \Carbon\Carbon) {
            return $date->format($format);
        }
        
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('cents_to_currency')) {
    /**
     * Convert cents to currency format
     */
    function cents_to_currency($cents)
    {
        return number_format($cents / 100, 2);
    }
}

if (!function_exists('currency_to_cents')) {
    /**
     * Convert currency to cents
     */
    function currency_to_cents($amount)
    {
        return (int) round($amount * 100);
    }
}