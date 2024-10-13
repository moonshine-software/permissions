## MoonShine Permission

### Requirements

- MoonShine v3.0+

### Support MoonShine versions

| MoonShine   | ChangeLog   |
|-------------|-------------|
| 2.0+        | 1.0+        |
| 3.0+        | 2.0+        |

### Installation

```shell
composer require moonshine/permissions
```
### Run migrations

```shell
php artisan migrate
```

### Get started

1. Change MoonshineUser model in config/moonshine.php or in MoonShineServiceProvider

```php
use MoonShine\Permissions\Models\MoonshineUser;

return [
    // ...
    'auth' => [
        // ...
        'providers' => [
            'moonshine' => [
                'driver' => 'eloquent',
                'model' => MoonshineUser::class,
            ],
        ],
    ],
    // ...
];
```

Or add trait HasMoonShinePermissions to user model

```php
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

class MoonshineUser extends Model
{
    use HasMoonShinePermissions;
}
```

2. Add trait WithPermissions to MoonShineUserResource and change $model

```php
use MoonShine\Permissions\Traits\WithPermissions;

class MoonShineUserResource extends ModelResource
{
    use WithPermissions;

    public string $model = MoonShine\Permissions\Models\MoonshineUser::class;

    // ...
}
```

### Example of condition to display in menu

```php
use MoonShine\Laravel\Enums\Ability;

protected function menu(): array
{
    return [
        MenuItem::make('Posts', PostResource::class)
            ->canSee(fn () => auth()->user()->isHavePermission(PostResource::class, Ability::VIEW))
        ,
    ];
}
```
