<?php
if (isset($_FILES['upload']['name'])) {
    $file_name = $_FILES['upload']['name'];
    $file_tmp = $_FILES['upload']['tmp_name'];
    $target = 'upload/CKEDITOR/';
    
    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!file_exists($target)) {
        mkdir($target, 0777, true);
    }

    move_uploaded_file($file_tmp, $target . $file_name);
    
    $function_number = $_GET['CKEditorFuncNum'];
    $url = '/' . $target . $file_name; // ต้องมี '/' นำหน้า
    $message = '';

    echo '<script>';
    echo 'window.parent.CKEDITOR.tools.callFunction("' . $function_number . '", "' . $url . '", "' . $message . '");';
    echo '</script>';
}

