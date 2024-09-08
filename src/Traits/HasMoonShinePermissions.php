<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Permissions\Models\MoonshineUserPermission;
use MoonShine\Laravel\MoonShineAuth;

trait HasMoonShinePermissions
{
    public function isHavePermission(string $resourceClass, Ability $ability): bool
    {
        return !empty($this->moonshineUserPermission?->permissions[$resourceClass][$ability->value]);
    }

    public function moonshineUserPermission(): HasOne
    {
        return $this->hasOne(
            MoonshineUserPermission::class,
            'moonshine_user_id',
            MoonShineAuth::getModel()?->getKeyName() ?? 'id'
        );
    }
}
