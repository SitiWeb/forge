<?php

namespace App\Library;

class Field
{
    public $name;
    public $type;
    public $settings;
    public $old ;
    public $label;
    public $width;
    public $placeholder;
    public $value;
    public $selected;

    public function __construct($name, $type = 'text', $settings = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->settings = $settings;
        foreach($this->settings as $key => $value){
            $this->$key = $value;
        }
    }

    // Add getter and setter methods for field properties if needed.
}
