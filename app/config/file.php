<?php 

class File
{
    public $name;
    public $tmp;
    public $ext;

    private $ext_allowed = array("tiff", "pjp", "pjpeg", "jfif", "webp", "tif", "bmp", "png", "jpg", "jpeg", "svgz", "gif", "svg", "ico", "xbm", "dib" );

    public function upload()
    {
        $this->ext = pathinfo($this->name, PATHINFO_EXTENSION);
        
        if(in_array($this->ext, $this->ext_allowed))
        {
            move_uploaded_file($this->tmp,"./../../../app/src/imgs/souvenirs/".$this->name);
            return true;
        }
        
        return false; //extensions not allowed
    }
}