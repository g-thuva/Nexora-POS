<?php

namespace Livewire\Features\SupportCompiledWireKeys;

use Livewire\ComponentHook;

class SupportCompiledWireKeys extends ComponentHook
{
    // Minimal implementation to match Livewire's expected API.
    // Extends ComponentHook so ComponentHookRegistry can instantiate
    // and call lifecycle methods without throwing.
    public static function generateKey($deterministicBladeKey, $key)
    {
        if (! is_null($key) && $key !== 'null') {
            return $key;
        }

        return $deterministicBladeKey;
    }
}
