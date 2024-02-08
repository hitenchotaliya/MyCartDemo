<?php

if (isset($_POST['submit'])) {
    foreach ($_FILES['doc']['name'] as $key => $val) {
        $rand = rand('11111', '99999');
        $file = $rand . '_' . $val;
        move_uploaded_file($_FILES['doc']['tmp_name'][$key], 'upload/' . $file);
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="doc[]" multiple />
    <input type="submit" name="submit" />
</form>