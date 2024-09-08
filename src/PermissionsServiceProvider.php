<?php

declare(strict_types=1);

namespace MoonShine\Permissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

final class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * @param  ConfiguratorContract<MoonShineConfigurator>  $configurator
     */
    public function boot(ConfiguratorContract $configurator): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/permissions.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'moonshine-permissions');

        $configurator->authorizationRules(
            static function (ResourceContract $resource, Model $user, Ability $ability): bool {
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
