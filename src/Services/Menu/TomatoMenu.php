<?php

namespace TomatoPHP\TomatoPHP\Services\Menu;

abstract class TomatoMenu
{
    /**
     * @var ?string
     * @example ACL
     */
    public ?string $group;

    /**
     * @var ?string
     * @example Dashboard
     */
    public ?string $menu;

    /**
     * @return array
     */
    public static function handler(): array
    {
        return [];
    }
}
