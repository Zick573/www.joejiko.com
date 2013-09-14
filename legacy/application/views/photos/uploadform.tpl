<form action="/upload" method="post" enctype="multipart/form-data">
  <label for="image">Image:</label>
  <input type="file" name="image" id="image"><br>

  <label for="caption">Caption</label>
  <input type="text" name="caption" id="caption">

  <input type="hidden" name="user" id="user" value="1">
  <button type="submit">upload</button>
</form>