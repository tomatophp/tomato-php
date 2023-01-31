<?php

namespace TomatoPHP\TomatoPHP\Services\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;


class MenuHandler
{
    /**
     * @var array
     */
    public array $children = [];

    /**
     * @var Collection
     */
    public Collection $menu;

    /**
     * @return Collection
     */
    public static function get(): Collection
    {
        //Make $this->menu collection
        return (new static)->menus()->build()->load();
    }

    /**
     * @return Collection
     */
    public function load(): Collection
    {
        return $this->menu;
    }

    /**
     * @return $this
     */
    private function menus(): static
    {
        $providerMenu = TomatoMenuRegister::loadMenus();
        $menusClasses = array_merge(config('tomato-admin.menus'), $providerMenu);
        foreach($menusClasses as $class){
            $this->children[] = $class;
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function build(): static
    {
        $this->menu = collect();
       foreach ($this->children as $menu){
           $menuGroup = app($menu)->group;
           $menuName = app($menu)->menu;
           $menuItems = app($menu)->handler();

           if(!$this->menu->has($menuName)){
               $this->menu->put($menuName, collect([]));
           }

           $getGroup = $this->menu[$menuName]->where('label', $menuGroup)->first();
           if($getGroup){
               foreach($menuItems as $menuItemValue){
                   $getGroup['items']->push($menuItemValue);
               }
           }
           else {
               $this->menu[$menuName]->put($menuGroup, collect([
                   "label" => $menuGroup,
                   "items" => collect($menuItems)
               ]));
           }
       }

       return $this;
    }
}
