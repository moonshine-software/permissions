<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Http\Requests;

use MoonShine\Http\Requests\MoonShineFormRequest;

final class PermissionFormRequest extends MoonShineFormRequest
{
    public function authorize(): bool
    {
        if (! in_array(
            'update',
            $this->getResource()->getActiveActions(),
            true
        )) {
            return false;
        }

        return $this->getResource()->can('update');
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
