<?php

class Photo extends Db_object
{
    protected static $db_table = "photos";
    protected static $db_table_fields = array('photo_id', 'title', 'description', 'filename', 'type', 'size');


    public $photo_id;
    public $title;
    public $description;
    public $filename;
    public $type;
    public $size;


    public $tmp_path;
    public $upload_directory = 'images';
    public $errors = array();
    public $upload_errors_array = array(
        UPLOAD_ERR_OK           => "There is no error, the file uploaded with success.",
        UPLOAD_ERR_INI_SIZE     => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
        UPLOAD_ERR_FORM_SIZE    => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
        UPLOAD_ERR_PARTIAL      => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE      => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION    => "A PHP extension stopped the file upload."
    );


    public function set_file($file)
    {

        // guard statement
        // empty
        if (empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "There was no file uploaded here";
            return false;
            // other type of errors
        } elseif ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors_array[$file["error"]];
            return false;
        } else {
            // https://www.php.net/manual/en/function.basename.php
            $this->filename  = basename($file['name']);
            $this->tmp_path  = $file['tmp_name'];
            $this->type      = $file['type'];
            $this->size      = $file['size'];
        }
    }

    // we are utilizing the methods from parent class
    public function save()
    {
        if ($this->photo_id) {
            $this->update();
        } else {
            // errors checking
            if (!empty($this->errors)) {
                return false;
            }

            // file is empty or path is empty
            if (empty($this->filename) || empty($this->tmp_path)) {
                $this->errors[] = "the file is not available";
                return false;
            }


            // permanent location of the file
            $target_path = SITE_ROOT . DS . 'admin' . $this->upload_directory . DS . $this->filename;

            // guard if file exists
            if (file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exists";
                return false;
            }

            // move_uploaded_file(filename, destination);
            if (move_uploaded_file($this->tmp_path, $target_path)) {
                if ($this->create()) {
                    unset($this->tmp_path);
                    return true;
                }
            } else {
                // permissions scope problem
                $this->errors[] = "The file directory probably does not have permission";
                return false;
            }



            // $this->create();
        }
    }
}
