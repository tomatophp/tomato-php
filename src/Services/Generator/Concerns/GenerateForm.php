<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Str;

trait GenerateForm
{
    private function generateViewItem(string $name): string
    {
        $form = "";
        $form .= "<div class=\"flex justify-between\">".PHP_EOL;
        $form .= "              <div>".PHP_EOL;
        $form .= "                  <h3 class=\"text-lg font-bold\">".PHP_EOL;
        $form .= "                      {{__('".Str::ucfirst(str_replace('_', ' ', $name))."')}}".PHP_EOL;
        $form .= "                  </h3>".PHP_EOL;
        $form .= "              </div>".PHP_EOL;
        $form .= "              <div>".PHP_EOL;
        $form .= "                  <h3 class=\"text-lg\">".PHP_EOL;
        $form .= '                      {{ $model->'.$name . "}}".PHP_EOL;
        $form .= "                  </h3>".PHP_EOL;
        $form .= "              </div>".PHP_EOL;
        $form .= "          </div>".PHP_EOL;

        return $form;
    }

    private function generateForm(bool $view=false): string
    {
        $form = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $form .= "          ";
            }
            if(
                $item['type'] === 'string' ||
                $item['type'] === 'email' ||
                $item['type'] === 'tel' ||
                ($item['name'] === 'password' && !$view)
            ){
                $type = $item['type'] === 'string' ? 'text' : $item['type'];
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input name=\"{$item['name']}\" type=\"".$type."\"  placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" />";
                    if($item['name'] === 'password'){
                        $form .= PHP_EOL."          <x-splade-input name=\"{$item['name']}_confirmation\" type=\"".$type."\"  placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))." Confirmation\" />";
                    }
                }
            }
            if($item['type'] === 'textarea'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-textarea name=\"{$item['name']}\" placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" autosize />";
                }
            }
            if($item['type'] === 'relation'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-select placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" name=\"".$item['name']."\" remote-url=\"/admin/".$item['relation']['table']."/api\" remote-root=\"model.data\" option-label=\"name\" option-value=\"id\" choices/>";
                }
            }
            if($item['type'] === 'date'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" name=\"".$item['name']."\" date />";
                }
            }
            if($item['type'] === 'time'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" name=\"".$item['name']."\" time=\"{ time_24hr: false }\" />";
                }
            }
            if($item['type'] === 'datetime'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input placeholder=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" name=\"".$item['name']."\" date time=\"{ time_24hr: false }\" />";
                }
            }
            if($item['type'] === 'boolean'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-checkbox name=\"".$item['name']."\" value=\"".$item['default']."\" label=\"".Str::ucfirst(str_replace('_', ' ', $item['name']))."\" />";
                }
            }

            if($key!== count($this->cols)-1){
                $form .= PHP_EOL;
            }
        }
        return $form;
    }
}