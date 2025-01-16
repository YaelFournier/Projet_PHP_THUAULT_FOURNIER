<?php
abstract class GenericFormElement {
    protected $question;

    public function __construct($question) {
        $this->question = $question;
    }

    public function renderTitle() {
        echo "<p>{$this->question}</p>";
    }

    abstract public function question(); // Méthode pour afficher le champ
    abstract public function answer($value); // Méthode pour valider la réponse
}
