<?php

namespace App\Library;

class Form
{
    public $name;
    public $action;
    public $fields;
    public $method;
    public $submit;

    public function __construct($name = "Form", $action = "", $method = "GET")
    {
        $this->name = $name;
        $this->action = $action;
        $this->method = $method;
        $this->fields = []; 
        $this->submit =  new \stdClass();
        $this->submit->text = "submit";
    
    }

    // public static function render($name)
    // {
    //     return "Hello, $name!";
    // }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAction()
    {
        return $this->action;
    }
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getMethod()
    {
        return $this->method;
    }
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getSubmitText(){
        return $this->submit->text;
    }

    public function setSubmitText($text){
        $this->submit->text = $text;
    }

    public function addField($name , $type, $label = false, $options = [], $width = false, $placeholder = false, $old = false)
    {
        $field = new Field($name,  $type, $label, $options, $width, $placeholder, $old);
        $this->fields[] = $field;
        return $field; // Return the added field in case you want to further customize it.
    }

    public function getFields()
    {
        return $this->fields;
    }
}
