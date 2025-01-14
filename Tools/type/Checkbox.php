<?php 
namespace Tools\type;
use Tools\GenericFormElement;
class Checkbox extends GenericFormElement{

    public function __construct($question) {
        $this->question = $question;
    }
    function question($q) {
        $html = $q['text'] . "<ul>";
        $i = 0;
        foreach ($q["choices"] as $c) {
            $i += 1;
            $html .= "<li><label for='$q[name]-$i'>$c[text]</label>";
            $html .= "<input type='checkbox' name='$q[name][]' value='$c[value]' id='$q[name]-$i'></li>";
            
        }
        echo $html . "</ul>";
        echo '</li>';
    }
    
    function answer($q, $v) {
        global $question_correct, $score_total, $score_correct;
        $score_total += $q["score"];
        if ($v===null){ return;}
        $diff1 = array_diff($q["answer"], $v);
        $diff2 = array_diff($v, $q["answer"]);
        if (count($diff1) == 0 && count($diff2) == 0) {
            $question_correct += 1;
            $score_correct += $q["score"];
        }
    }

}

?>