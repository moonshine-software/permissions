<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Http\Requests;

use MoonShine\Laravel\Http\Requests\MoonShineFormRequest;
use MoonShine\Support\Enums\Ability;
use MoonShine\Support\Enums\Action;

final class PermissionFormRequest extends MoonShineFormRequest
{
    public function authorize(): bool
    {
        if (! $this->getResource()?->hasAction(Action::UPDATE)) {
            return false;
        }

        return $this->getResource()?->can(Ability::UPDATE) ?? false;
    }

    /**
     * @return array{permissions: string[]}
     */
    public function rules(): array
    {
        return [
            'permissions' => ['array'],
        ];
    }
}
