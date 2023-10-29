<?php

declare(strict_types=1);

namespace MoonShine\Permissions\Models;

use MoonShine\Models\MoonshineUser as User;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

class MoonshineUser extends User
{
    use HasMoonShinePermissions;
}
