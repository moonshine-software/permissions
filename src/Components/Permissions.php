<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Components;

use Closure;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Core\Traits\HasResource;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\MoonShineComponent;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Traits\WithLabel;

/**
 * @method static static make(Closure|string $label, ModelResource $resource)
 */
final class Permissions extends MoonShineComponent
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
        parent::__construct();

        $this->setLabel($label);
        $this->setResource($resource);
    }

    public function getItem(): Model
    {
        return $this->getResource()->getItemOrInstance();
    }

    public function getForm(): FormBuilder
    {
        $url = $this->getResource()->getRoute('permissions', $this->getItem()->getKey());

        $elements = [];
        $values = [];
        $all = true;

        /**
         * @var HasMoonShinePermissions $item
         */
        $item = $this->getItem();

        /**
         * @var ModelResource $resource
         */
        foreach ($this->getCore()->getResources() as $resource) {
            $checkboxes = [];
            $class = 'ps_' . class_basename($resource::class);
            $allSections = true;

            foreach ($resource->getGateAbilities() as $ability) {
                $values['permissions'][$resource::class][$ability->value] = $item->isHavePermission(
                    $resource::class,
                    $ability
                );

                if (! $values['permissions'][$resource::class][$ability->value]) {
                    $allSections = false;
                    $all = false;
                }

                $checkboxes[] = Switcher::make(
                    $ability->value,
                    "permissions." . $resource::class . ".$ability->value"
                )
                    ->customAttributes(['class' => 'permission_switcher ' . $class])
                    ->setNameAttribute("permissions[" . $resource::class . "][$ability->value]");
            }

            $elements[] = Column::make([
                Switcher::make($resource->getTitle())->customAttributes([
                    'class' => 'permission_switcher_section',
                    '@change' => "document
                          .querySelectorAll('.$class')
                          .forEach((el) => {el.checked = event.target.checked; el.dispatchEvent(new Event('change'))})",
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
                          .forEach((el) => {el.checked = event.target.checked; el.dispatchEvent(new Event('change'))})
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
        $itemExists = $this->getItem()?->exists;

        return [
            'itemExists' => $itemExists,
            'label' => $this->getLabel(),
            'form' => $itemExists
                ? $this->getForm()
                : '',
        ];
    }
}
