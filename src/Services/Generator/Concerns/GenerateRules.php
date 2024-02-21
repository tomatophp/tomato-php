<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateRules
{
    private function generateRules(bool $edit = false): string
    {
        $rules = "";
        foreach ($this->cols as $key => $item) {
            if ($item['name'] !== 'id') {
                if($key !== 0){
                    $rules .= "            ";
                }
                $rules .= "'{$item['name']}' => ";
                $rules .= "'";
                if($item['required'] === 'required'){
                    if($edit){
                        $rules .= 'sometimes';
                    }
                    else {
                        $rules .= 'required';
                    }

                }
                else {
                    $rules .= 'nullable';
                }

                if($item['maxLength']){
                    $rules .= '|max:'.$item['maxLength'];
                }
                if($item['type'] === 'string' || $item['type'] === 'email' || $item['type'] === 'phone'){
                    $rules .= '|string';
                }
                if($item['type'] === 'email'){
                    $rules .= '|email';
                }
                if($item['type'] === 'tel'){
                    $rules .= '|min:12';
                }
                if($item['type'] === 'password'){
                    $rules .= '|confirmed|min:6';
                }
                if($item['type'] === 'relation'){
                    $rules .= '|exists:'.$item['relation']['table'].',id';
                }

                $rules .= "'";
                if (($key !== count($this->cols) - 1)) {
                    $rules .= ",".PHP_EOL;
                }
            }
        }

        return $rules;
    }
}
