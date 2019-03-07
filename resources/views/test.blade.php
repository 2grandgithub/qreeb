<html>
<form method="post" action="/upload_file" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="file">
    <input type="submit">
</form>
</html>
