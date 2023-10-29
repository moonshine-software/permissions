<?php

declare(strict_types=1);

namespace MoonShine\Permissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\MoonShine;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

final class PermissionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'moonshine-permissions');

        MoonShine::defineAuthorization(
            static function (ResourceContract $resource, Model $user, string $ability): bool {
                $hasUserPermissions = in_array(
                    HasMoonShinePermissions::class,
                    class_uses_recursive($user),
                    true
                );

                if (! $hasUserPermissions) {
                    return true;
                }

                if (! $user->moonshineUserPermission) {
                    return true;
                }

                return $user->isHavePermission(
                    $resource::class,
                    $ability
                );
            }
        );
    }
}
