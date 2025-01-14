<?php
abstract class GenericFormElement{
    protected $question;

    abstract function question($q);
    abstract function answer($q, $v);
}
?>