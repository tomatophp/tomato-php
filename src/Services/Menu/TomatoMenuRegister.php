<?php

namespace TomatoPHP\TomatoPHP\Services\Menu;

class TomatoMenuRegister
{
    /**
     * @var array|null
     */
    public static ?array $menu = [];

    /**
     * @param string $item
     * @return void
     */
    public static function registerMenu(string $item): void
    {
        self::$menu[] = $item;
    }

    /**
     * @return array
     */
    public static function loadMenus(): array
    {
        return self::$menu;
    }
}
