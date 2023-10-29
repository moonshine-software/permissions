<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Components;

use Closure;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\MoonshineComponent;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Switcher;
use MoonShine\Resources\ModelResource;
use MoonShine\Traits\HasResource;
use MoonShine\Traits\WithLabel;

/**
 * @method static static make(Closure|string $label, ModelResource $resource)
 */
final class Permissions extends MoonshineComponent
{
    use HasResource;
    use WithLabel;

    protected string $view = 'moonshine-permissions::components.permissions';

    protected Model $item;

    protected $except = [
        'getItem',
        'getResource',
        'getForm',
    ];

    public function __construct(
        Closure|string $label,
        ModelResource $resource
    ) {
        $this->setResource($resource);
        $this->setLabel($label);
    }

    public function getItem(): Model
    {
        return $this->getResource()->getItemOrInstance();
    }

    public function getForm(): FormBuilder
    {
        $url = $this->getResource()
            ->route('permissions', $this->getItem()->getKey());

        $elements = [];
        $values = [];
        $all = true;

        foreach (moonshine()->getResources() as $resource) {
            $checkboxes = [];
            $class = 'ps_' . class_basename($resource::class);
            $allSections = true;

            foreach ($resource->gateAbilities() as $ability) {
                $values['permissions'][$resource::class][$ability] = $this->getItem()->isHavePermission(
                    $resource::class,
                    $ability
                );

                if (! $values['permissions'][$resource::class][$ability]) {
                    $allSections = false;
                    $all = false;
                }

                $checkboxes[] = Switcher::make(
                    $ability,
                    "permissions." . $resource::class . ".$ability"
                )
                    ->customAttributes(['class' => 'permission_switcher ' . $class])
                    ->setName("permissions[" . $resource::class . "][$ability]");
            }

            $elements[] = Column::make([
                Switcher::make($resource->title())->customAttributes([
                    'class' => 'permission_switcher_section',
                    '@change' => "document
                          .querySelectorAll('.$class')
                          .forEach((el) => {el.checked = parseInt(event.target.value); el.dispatchEvent(new Event('change'))})",
                ])->setValue($allSections)->hint('Toggle off/on all'),

                ...$checkboxes,
                Divider::make(),
            ])->columnSpan(6);
        }

        return FormBuilder::make($url)
            ->fields([
                Switcher::make('All')->customAttributes([
                    '@change' => <<<'JS'
                        document
                          .querySelectorAll('.permission_switcher, .permission_switcher_section')
                          .forEach((el) => {el.checked = parseInt(event.target.value); el.dispatchEvent(new Event('change'))})
                    JS
    ,
                ])->setValue($all),
                Divider::make(),
                Grid::make(
                    $elements
                ),
            ])
            ->fill($values)
            ->submit(__('moonshine::ui.save'));
    }

    protected function viewData(): array
    {
        return [
            'label' => $this->label(),
            'form' => $this->getItem()?->exists
                ? $this->getForm()
                : '',
            'item' => $this->getItem(),
            'resource' => $this->getResource(),
        ];
    }
}
