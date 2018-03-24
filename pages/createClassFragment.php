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

