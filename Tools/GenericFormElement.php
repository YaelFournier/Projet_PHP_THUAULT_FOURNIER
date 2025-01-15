<?php
namespace Tools;

abstract class GenericFormElement {
    protected $question;

    public function __construct($question) {
        $this->question = $question;
    }

    abstract public function question();
    abstract public function answer($value);
}