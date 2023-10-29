<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Traits;

use Illuminate\Support\Facades\Route;
use MoonShine\Decorations\Heading;
use MoonShine\Enums\Layer;
use MoonShine\Enums\PageType;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\Permissions\Components\Permissions;
use MoonShine\Permissions\Http\Controllers\PermissionController;
use MoonShine\Resources\Resource;

/**
 * @mixin Resource
 */
trait WithPermissions
{
    protected function bootWithPermissions(): void
    {
        $this->getPages()
            ->findByType(PageType::FORM)
            ?->pushToLayer(
                Layer::BOTTOM,
                Permissions::make(
                    'Permissions',
                    $this
                )->canSee(
                    fn() => auth()->user()->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID
                )
            );
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post(
            'permissions/{resourceItem}',
            PermissionController::class
        )->name('permissions');
    }
}
