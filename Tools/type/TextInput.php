<?php 
namespace Project\Tools\Type;

use Project\Tools\GenericFormElement;

class TextInput extends GenericFormElement {

    public function question() {
        echo "<p>{$this->question}</p>";
        echo "<input type='text' name='answer'><br>";
    }

    public function answer($value) {
        if (empty(trim($value))) {
            throw new \Exception("La réponse ne peut pas être vide.");
        }

        return true;
    }
}