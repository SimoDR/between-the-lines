<?php
class factoryImg{
    public function uploadImage($directory,$input_file){
        $dest_path = '../img/'.$directory;
        $basename = date('Y-m-d') .'_'.round(microtime(true));
        $error="";
        $success = 1;
        $path_parts = pathinfo($_FILES[$input_file]["name"]);
        $imgExtension = strtolower($path_parts['extension']);
        $size = getimagesize($_FILES[$input_file]["tmp_name"]);
        $dest_file = $dest_path . $basename . '.' . $imgExtension;
        
        if($size == false) {
            $success = 0;
            $error= $error."<div class=\"errorMessage\">Il <span xml:lang=\"en\">file</span> non è un'immagine.</div>";
        }
        elseif ($_FILES[$input_file]["size"] > 3000000) {//3MB
            $success = 0;
            $error= $error."<div class=\"errorMessage\"><span xml:lang=\"en\">File</span> troppo grande. Caricare un <span xml:lang=\"en\">file</span> di dimensione inferiore a 3MB <span class=\"sr-only\">3 <span lang=\"en\">Mega Bytes<\span><\span>.</div>";
        }
        if($imgExtension != "gif" && $imgExtension != "jpeg" && $imgExtension != "jpg" && $imgExtension != "png" ) {
            $success = 0;
            $error= $error."<div class=\"errorMessage\">Il formato non è supportato. È possibile caricare solo <span xml:lang=\"en\">file</span> con formato JPG, JPEG, PNG e GIF.</div>";
        }       
        if ($success == 1) {
            if (is_uploaded_file($_FILES[$input_file]['tmp_name'])){       
                 move_uploaded_file($_FILES[$input_file]['tmp_name'], $dest_file);
            }
        }
        
        $toReturn['error']=$error;
        $toReturn['path']=$dest_file;

        return $toReturn;
    }

    public function deleteImage($path){
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
?>
