<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait GenerateForm
{
    private function generateViewItem(string $name,string $value=null, string $type="text"): string
    {
        $text = "";
        if($value){
            $text = $value;
        }
        else {
            $text = $name;
        }

        $form = '<x-tomato-admin-row :label="__(\''.Str::ucfirst(str_replace('_', ' ', $name)).'\')" :value="$model->'.$text.'" type="'.$type.'" />'.PHP_EOL;
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
                ($item['name'] === 'password' && !$view)
            ){
                $type = $item['type'] === 'string' ? 'text' : $item['type'];
                if($view){
                    $form .= $this->generateViewItem($item['name'],null, $item['type']);
                }
                else {
                    $form .= "<x-splade-input :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"{$item['name']}\" type=\"".$type."\"  :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" />";
                    if($item['name'] === 'password'){
                        $form .= PHP_EOL."          <x-splade-input name=\"{$item['name']}_confirmation\" :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))." Confirmation')\" type=\"".$type."\"  :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))." Confirmation')\" />";
                    }
                }
            }
            if($item['type'] === 'textarea'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-textarea :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"{$item['name']}\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" autosize />";
                }
            }
            if($item['type'] === 'longText'){
                if($view){
                    $form .= $this->generateViewItem($item['name'], null, "rich");
                }
                else {
                    $form .= "<x-tomato-admin-rich :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"{$item['name']}\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" autosize />";
                }
            }
            if($item['type'] === 'int'){
                if($view){
                    $form .= $this->generateViewItem($item['name'], null, "number");
                }
                else {
                    $form .= "<x-splade-input :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" type='number' name=\"{$item['name']}\" />";
                }
            }
            if($item['type'] === 'color'){
                if($view){
                    $form .= $this->generateViewItem($item['name'], null, "color");
                }
                else {
                    $form .= "<x-tomato-admin-color :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"{$item['name']}\" />";
                }
            }
            if($item['type'] === 'icon'){
                if($view){
                    $form .= $this->generateViewItem($item['name'], null, "icon");
                }
                else {
                    $form .= "<x-tomato-admin-icon :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"{$item['name']}\" />";
                }
            }
            if($item['type'] === 'relation'){
                if($view){

                    $form .= $this->generateViewItem(Str::remove('_id',$item['name']),Str::remove('_id',Str::ucfirst($item['name']))."->".$item['relation']['relationColumn']);
                }
                else {
                    $itemLable=($item['relation']['relationColumnType'] == 'json')?'name.'.app()->getLocale():'name';
                    $form .= "<x-splade-select :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" :remote-url=\"route('admin.".$item['relation']['table'].".api')\" remote-root=\"data\" option-label=\"$itemLable\" option-value=\"id\" choices/>";
                }
            }
            if($item['type'] === 'date'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" date />";
                }
            }
            if($item['type'] === 'time'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" time=\"{ time_24hr: false }\" />";
                }
            }
            if($item['type'] === 'datetime'){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-splade-input :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" date time=\"{ time_24hr: false }\" />";
                }
            }
            if($item['type'] === 'boolean'){
                if($view){
                    $form .= $this->generateViewItem($item['name'], null, "bool");
                }
                else {
                    $form .= "<x-splade-checkbox :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" />";
                }
            }
            if($item['type'] === 'json' && ($item['name']== 'name' ||$item['name']== 'title'|| $item['name']== 'description')){
                if($view){
                    $form .= $this->generateViewItem($item['name']);
                }
                else {
                    $form .= "<x-tomato-translation :label=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."-EN')\" :placeholder=\"__('".Str::ucfirst(str_replace('_', ' ', $item['name']))."')\" name=\"".$item['name']."\" />";

                }
            }


            if($key!== count($this->cols)-1){
                $form .= PHP_EOL;
            }
        }
        return $form;
    }
}
