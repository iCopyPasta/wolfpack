<!doctype html>
<html lang="en">
  <div class="modal-body">

    <form action="../lib/php/createClassWeb.php" method="post">
      <div class="form-group">
        <label for="Class Title">
          Class Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>
        <div class="form-group">
        <label for="Class Description">
          Class Description</label>
        <input type="text" class="form-control" id="description" rows="3" name="description" required>
      </div>
        <div class="form-group">
        <label for="Class Offering">
          Class Offering</label>
        <input type="text" class="form-control" id="offering" name="offering" required>
      </div>
        <div class="form-group">
        <label for="Class Location">
          Class Location</label>
        <input type="text" class="form-control" id="location" name="location" required>
      </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</html>

<script type='text/javascript'>
        function addFields(){
            
            if(document.getElementById("radio_select").checked == true){
                
                // Number of inputs to create
                var number = document.getElementById("member").value;
                // Container <div> where dynamic content will be placed
                var container = document.getElementById("potential_answers_container");
                // Clear previous contents of the container
                while (container.hasChildNodes()) {
                    container.removeChild(container.lastChild);
                }
                for (i=0;i<number;i++){
                    // Append a node with a random text
                    container.appendChild(document.createTextNode("Answer " + (i+1)));
                    // Create an <input> element, set its type and name attributes
                    var input = document.createElement("input");
                    input.type = "text";
                    input.name = "question" + i;

                    var checkbox = document.createElement("input");
                    checkbox.type="checkbox";
                    checkbox.id = "checkbox" + i;
                    checkbox.name = "checkbox" + i;

                    container.appendChild(input);
                    container.appendChild(checkbox);
                    // Append a line break 
                    container.appendChild(document.createElement("br"));
                }   
            }
            else if(document.getElementById("radio_choice").checked == true){
                // Number of inputs to create
                var number = document.getElementById("member").value;
                // Container <div> where dynamic content will be placed
                var container = document.getElementById("potential_answers_container");
                // Clear previous contents of the container
                while (container.hasChildNodes()) {
                    container.removeChild(container.lastChild);
                }
                for (i=0;i<number;i++){
                    // Append a node with a random text
                    container.appendChild(document.createTextNode("answerChoice " + (i+1)));
                    // Create an <input> element, set its type and name attributes
                    var input = document.createElement("input");
                    input.type = "text";
                    input.name = "question" + i;

                    var checkbox = document.createElement("input");
                    checkbox.type="radio";
                    checkbox.id = "radioChoice" + i;
                    checkbox.name = "correctChoiceAnswer";

                    container.appendChild(input);
                    container.appendChild(checkbox);
                    // Append a line break 
                    container.appendChild(document.createElement("br"));
                }
            }
            else if(document.getElementById("radio_trueFalse").checked == true){
                // Number of inputs to create
                var number = document.getElementById("member").value;
                // Container <div> where dynamic content will be placed
                var container = document.getElementById("potential_answers_container");
                // Clear previous contents of the container
                while (container.hasChildNodes()) {
                    container.removeChild(container.lastChild);
                }
                for (i=0;i<2;i++){
                    // Append a node with a random text
                    container.appendChild(document.createTextNode("answerChoice " + (i+1)));
                    // Create an <input> element, set its type and name attributes
                    var input = document.createElement("input");
                    input.type = "text";
                    input.name = "question" + i;

                    var checkbox = document.createElement("input");
                    checkbox.type="radio";
                    checkbox.id = "radioTF" + i;
                    checkbox.name = "correctTrueFalseAnswer";

                    container.appendChild(input);
                    container.appendChild(checkbox);
                    // Append a line break 
                    container.appendChild(document.createElement("br"));
                }
                    
            }else if(document.getElementById("radio_image").checked == true){
                alert("not implemented, yet");
                
            }else{
                alert("No selected question type!");
            }
            
        }
</script>
