<?php

namespace Livewire\Features\SupportReleaseTokens;

use Livewire\ComponentHook;

class SupportReleaseTokens extends ComponentHook
{
    // Minimal stub so ComponentHookRegistry can instantiate this
    // feature when the vendor implementation is not present.
    // Extend ComponentHook to provide hook plumbing (setComponent, call* methods).

    public static function provide()
    {
        // No-op: the real feature may publish views or assets, but
        // for this minimal stub we don't need to do anything.
    }
}
