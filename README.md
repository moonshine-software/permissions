## MoonShine Permission

### Requirements

- MoonShine v2.0+

### Installation

```shell
composer require moonshine/permissions
```
### Run migration
```shell
php artisan migrate
```

### Get started

1. Change MoonShineUser model in config/moonshine.php

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

class MoonShineUser extends Model
{
    use HasMoonShinePermissions;
}
```

2. Publish system resources

```shell
php artisan moonshine:publish resources
````

3. Add trait WithPermissions to MoonShineUserResource

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
use MoonShine\MoonShineRequest;

protected function menu(): array
{
    return [
        MenuItem::make('Posts', new PostResource())
            ->canSee(function (MoonShineRequest $request) {
                return $request->user()->isHavePermission(
                    PostResource::class,
                    'view'
                );
            }),
    ];
}
```
