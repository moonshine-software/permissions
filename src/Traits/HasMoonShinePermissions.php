<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use MoonShine\Permissions\Models\MoonshineUserPermission;
use MoonShine\MoonShineAuth;

trait HasMoonShinePermissions
{
    public function isHavePermission(string $resourceClass, string $ability): bool
    {
        return !empty($this->moonshineUserPermission?->permissions[$resourceClass][$ability]);
    }

    public function moonshineUserPermission(): HasOne
    {
        return $this->hasOne(
            MoonshineUserPermission::class,
            'moonshine_user_id',
            MoonShineAuth::model()?->getKeyName() ?? 'id'
        );
    }
}
