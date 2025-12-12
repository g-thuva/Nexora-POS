<?php

if (! function_exists('safe_count')) {
    /**
     * Return a safe count for collections/paginators/arrays.
     * - If object has total() (LengthAwarePaginator), return that.
     * - If it's countable, return count().
     * - Otherwise return 0.
     *
     * @param mixed $value
     * @return int
     */
    function safe_count($value): int
    {
        if (is_null($value)) {
            return 0;
        }

        // If paginator or object exposes total()
        if (is_object($value) && method_exists($value, 'total')) {
            try {
                $t = $value->total();
                return is_numeric($t) ? (int) $t : 0;
            } catch (\Throwable $e) {
                // fallthrough
            }
        }

        if (is_countable($value)) {
            return count($value);
        }

        return 0;
    }
}
