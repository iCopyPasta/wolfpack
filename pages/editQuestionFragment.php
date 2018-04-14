<!doctype html>

<?php

    include("../lib/php/selectQuestionByQuestionId.php");
    
     $retval = selectQuestionByQuestionId($edit_question_id);
     $retval = json_decode($retval,true);
    
     $edit_id = $edit_question_id;
     $edit_teacher_id = $retval[0]['teacher_id'];
     $edit_question_type = $retval[0]['question_type'];
     $edit_description = $retval[0]['description'];
     $edit_potential_answers = $retval[0]['potential_answers'];
     $edit_correct_answers = $retval[0]['correct_answers'];
?>




<html lang="en">
  <div class="modal-body">

    <form id="question_form">
         <select name="question_type" id="type" name ="question_type" onchange="typeChanged()" required>
          <option disabled selected value=' -- select an option -- '> -- select an option -- </option>
          <option value="True/False">True/False</option>
          <option value="Multiple Choice">Multiple Choice</option>
        </select>
        <div class="form-group" id="questionForm">
        <label  for="Question Description">
          Question Description</label>
        <input type="text" class="form-control" id="description" rows="3" name="description" required>
      </div>
        <div id="dynamicArea"> 
            <div id="newChoice"></div>
      </div>
      <button type="button" onclick="processForm()" class="btn btn-primary">Submit</button>
      <button type="button" onclick="resetForm()" class="btn btn-primary">Reset</button>
    </form>
    </div>
</html>

<script type='text/javascript'>
    
    var dynamicArea = document.getElementById("dynamicArea");
    var numberOfChoices = 1;
    
    var question_id = <?php echo $edit_id ?>;
    var teacher_id = <?php echo $edit_teacher_id ?>;
    var question_type = "<?php echo $edit_question_type ?>";
    var description = "<?php echo $edit_description ?>";
    var potential_answers = <?php echo $edit_potential_answers ?>;
    var correct_answers = <?php echo $edit_correct_answers ?>;
    
    
 
    document.getElementById('type').value = question_type;

    document.getElementById('description').value = description;
    
    for (i=0; i< potential_answers.length; i++) {
        if (question_type == "True/False")
            populateChoice(potential_answers[i],true);
        else
            populateChoice(potential_answers[i],false);
    }
    
    var injectAtEnd = "<div class=\"form-group\"><button type=\"button\" class=\"btn btn-success\" onclick=\"addChoice()\">Add new choice</button>        </div>"
    dynamicArea.insertAdjacentHTML('beforeend',injectAtEnd);
    
    for (i=0; i< correct_answers.length; i++) {
        document.getElementById(correct_answers[i]).checked = true;
    }
    
    
    
    function typeChanged() {
        
        if(document.getElementById('type').value == "True/False") {
            //t/f selected
            dynamicArea.innerHTML = "<div class=\"form-group\"><input type=\"text\" class=\"form-control\"rows=\"3\" required value=\"True\" disabled><input id=\"1\" type=\"checkbox\">This item is a correct answer</div><div class=\"form-group\"><input type=\"text\" class=\"form-control\"rows=\"3\" required value=\"False\" disabled><input id=\"2\" type=\"checkbox\">This item is a correct answer</div>";
        }
        else {
            //m/c selected
            dynamicArea.innerHTML = "<div class=\"form-group\"><label for=\"Answer 1\">Answer 1</label><input type=\"text\" class=\"form-control\"rows=\"3\" required><input id=\"1\" type=\"checkbox\">This item is a correct answer</div><div id=\"newChoice\"></div><div class=\"form-group\"><button type=\"button\" class=\"btn btn-success\" onclick=\"addChoice()\">Add new choice</button>        </div>";
        }
    }
    
    function populateChoice(value, isTrueFalse) {
        //append a new textfield to dynamicArea
      
        var parent = document.getElementById("newChoice");
        if (isTrueFalse)
            var newChild = "<div class=\"form-group\"><label for=\"Answer "+numberOfChoices+"\">Answer "+numberOfChoices+"</label><input type=\"text\" class=\"form-control\"rows=\"3\" value=\""+value+"\" required disabled><input id=\""+numberOfChoices+"\" type=\"checkbox\">This item is a correct answer</div>";
        else
            var newChild = "<div class=\"form-group\"><label for=\"Answer "+numberOfChoices+"\">Answer "+numberOfChoices+"</label><input type=\"text\" class=\"form-control\"rows=\"3\" value=\""+value+"\" required><input id=\""+numberOfChoices+"\" type=\"checkbox\">This item is a correct answer</div>";
        
        parent.insertAdjacentHTML('beforeend', newChild);
        
        numberOfChoices++;
    }
    
    function addChoice() {
        //append a new textfield to dynamicArea
      
        var parent = document.getElementById("newChoice");
        var newChild = "<div class=\"form-group\"><label for=\"Answer "+numberOfChoices+"\">Answer "+numberOfChoices+"</label><input type=\"text\" class=\"form-control\"rows=\"3\" required><input id=\""+numberOfChoices+"\" type=\"checkbox\">This item is a correct answer</div>";
        
        parent.insertAdjacentHTML('beforeend', newChild);
        
        numberOfChoices++;
    }
    
    function resetForm()  {
        numberOfChoices = 2;
        document.getElementById('type').value = ' -- select an option -- ';
        document.getElementById('dynamicArea').innerHTML = "";
        document.getElementById('description').value = "";
    }
  
    function processForm() {
        var elements = document.getElementById("question_form").elements;

        var type = elements[0].value;
        var desc = elements[1].value;
        var possibleAnswers = []
        var correctAnswers = []
        
        for (var i = 2, element; element = elements[i++];) {
    
                if (element.type === "checkbox") {
                    if (element.checked == true)
                        correctAnswers.push(element.id);
                }
                if (element.type === "text") {
                    if (element.value === "") {}
                    else {
                    possibleAnswers.push(element.value);
                    }
                
                }
                
            }
        
        
        console.log("type: " + type);
        console.log("desc: " + desc);
        console.log(JSON.stringify(possibleAnswers));
        console.log(JSON.stringify(correctAnswers));
       
        //send data as POST
        
        var send = new Object();
        send.question_type = type;
        send.description = desc;
        send.potential_answers = JSON.stringify(possibleAnswers);
        send.correct_answers = JSON.stringify(correctAnswers);
        send.question_id = question_id;
        send.teacher_id = teacher_id;
        
        
        postFrag('../lib/php/updateQuestion.php', send); 
        
    }
    
    
function postFrag(path, params, method) { // method: https://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}   
    
</script>
