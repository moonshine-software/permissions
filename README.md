## MoonShine Permission

### Requirements

- MoonShine v2.0+

### Installation

```shell
composer require moonshine/permissions
```

```shell
php artisan migrate
```

### Get started

1. Change MoonShineUser model in app/moonshine.php

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

Or add trait HasChangeLog to user model

```php
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

class MoonShineUser extends Model
{
    use HasMoonShinePermissions;
}
```

2. Add trait to resource

```php
use MoonShine\Permissions\Traits\WithPermissions;

class PostResource extends ModelResource
{
    use WithPermissions;
}
```

