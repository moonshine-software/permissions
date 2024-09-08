<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use MoonShine\Permissions\Http\Requests\PermissionFormRequest;
use MoonShine\Permissions\Models\MoonshineUserPermission;
use MoonShine\Support\Enums\ToastType;

class PermissionController extends MoonShineController
{
    public function __invoke(PermissionFormRequest $request): RedirectResponse
    {
        $item = $request
            ->getResource()
            ?->getItem();

        if (! $request->has('permissions')) {
            $item->moonshineUserPermission()->delete();
        } else {
            MoonshineUserPermission::query()->updateOrCreate(
                ['moonshine_user_id' => $item->getKey()],
                request()
                    ->merge(['moonshine_user_id' => $item->getKey()])
                    ->only(['moonshine_user_id', 'permissions'])
            );
        }

        $this->toast(
            __('moonshine::ui.saved'),
            ToastType::SUCCESS
        );

        return back();
    }
}
