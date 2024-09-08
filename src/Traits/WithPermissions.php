<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Traits;

use MoonShine\Laravel\Models\MoonshineUserRole;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Permissions\Components\Permissions;
use MoonShine\Support\Enums\Layer;

/**
 * @mixin ModelResource
 */
trait WithPermissions
{
    protected function loadWithPermissions(): void
    {
        $this->getFormPage()
            ?->pushToLayer(
                Layer::BOTTOM,
                Permissions::make(
                    'Permissions',
                    $this
                )->canSee(
                    fn (
                    ) => MoonShineAuth::getGuard()->user()->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID
                )
            );
    }
}
