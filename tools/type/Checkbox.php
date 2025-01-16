<?php 
namespace Project\Tools\Type;

use Project\Tools\GenericFormElement;

class Checkbox extends GenericFormElement {
    private $options;

    public function __construct($question, array $options) {
        parent::__construct($question);
        $this->options = $options;
    }

    public function question() {
        echo "<p>{$this->question}</p>";
        foreach ($this->options as $option) {
            echo "<label><input type='checkbox' name='questions[{$this->question}][answers][][text]' value='{$option}'> {$option}</label>";
            echo "<input type='hidden' name='questions[{$this->question}][answers][][isCorrect]' value='0'>";
        }
    }    

    public function answer($values) {
        if (!is_array($values)) {
            throw new \InvalidArgumentException("La réponse doit être un tableau.");
        }

        $invalidAnswers = array_diff($values, $this->options);
        if (!empty($invalidAnswers)) {
            throw new \Exception("Certaines réponses ne sont pas valides : " . implode(', ', $invalidAnswers));
        }

        return true;
    }
}