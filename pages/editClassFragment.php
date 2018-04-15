<!doctype html>

<?php

    include("../lib/php/selectClassByClassId.php");
    
     $retval = selectClassByClassId($edit_class_id);
     $retval = json_decode($retval,true);
    
     $edit_id = $edit_class_id;
     $edit_title = $retval[0]['title'];
     $edit_description = $retval[0]['description'];
     $edit_offering = $retval[0]['offering'];
     $edit_location = $retval[0]['location'];

?>




<html lang="en">
  <div class="modal-body">

    <form action="../lib/php/updateClassCourseSection.php" method="post">
      <div class="form-group">
        <label for="Class Title">
          Class Title</label>
        <input type="text" class="form-control" id="editTitle" name="title" required>
      </div>
        <div class="form-group">
        <label for="Class Description">
          Class Description</label>
        <input type="text" class="form-control" id="editDescription" rows="3" name="description" required>
      </div>
        <div class="form-group">
        <label for="Class Offering">
          Class Offering</label>
        <input type="text" class="form-control" id="editOffering" name="offering" required>
      </div>
        <div class="form-group">
        <label for="Class Location">
          Class Location</label>
        <input type="text" class="form-control" id="editLocation" name="location" required>
      </div>
        <button type="submit" class="btn btn-primary">Submit</button>
         <input type="hidden" name="class_id" value="<?php echo $edit_id ?>">
    </form>
    </div>
</html>

<script type='text/javascript'>

    
    var class_id = <?php echo $edit_id ?>;
    var edit_title = "<?php echo $edit_title ?>";
    var edit_description = "<?php echo $edit_description ?>";
    var edit_offering = "<?php echo $edit_offering ?>";
    var edit_location = "<?php echo $edit_location ?>";

    
    
 
    document.getElementById('editTitle').value = edit_title;

    document.getElementById('editDescription').value = edit_description;
    
    document.getElementById('editOffering').value = edit_offering
    
    document.getElementById('editLocation').value = edit_location;
    
    

</script>
