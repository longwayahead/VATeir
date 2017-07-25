<?php
class Files {
    private $_db,
            $_path = URL . 'training/uploads/';

    public function __construct() {
      $this->_db = DB::getInstance();
    }
    public function checkExists($destination){
      if(file_exists($destination) === false) {
        if(mkdir($destination, 0755)){
          return true;
        } else {
          return false;
        }
      }
      return true;
    }

    public function directoryContents($report_id) {
      return array_diff(scandir($this->_path . $report_id . '/'), array('..', '.'));
    }

    public function removeDirectory($report_id) {
      if(isset($report_id) === true) {
        if(rmdir($this->_path . $report_id . '/')) {
          return true;
        } else {
          throw new Exception("Error removing directory");
        }
      }
      return false;
    }

    public function get($report_id) {
      $get = $this->_db->query("SELECT * FROM training_uploads WHERE report_id = ?", [[$report_id]]);
      if($get) {
        return $get->results();
      } else {
        return false;
      }
    }

    public function dbPut($uploader, $report_id, $original_name, $new_name) {
      $put = $this->_db->query("INSERT INTO training_uploads (uploader, report_id, originalName, fileName) VALUES (?, ?, ?, ?)", [[$uploader, $report_id, $original_name, $new_name]]);
      if($put) {
        return true;
      }
      return false;
    }

    public function upload($file_array, $report_id, $uploader) {
      if(!empty($file_array['name'][0])) { //we want to only do something if someone's uploaded something...
        echo '<pre>';
        print_r($file_array);
        echo '</pre>';

        $uploaded = [];
        $failed = [];
        $allowed = ['txt', 'xlsx'];


        foreach($file_array['name'] as $key => $data) {
          //this is all just to make life easier
          $file_name = $file_array['name'][$key];
          $file_error = $file_array['error'][$key];
          $file_size = $file_array['size'][$key];
          $file_temp = $file_array['tmp_name'][$key];

          $file_ext = explode('.', $file_name);
          $file_ext = strtolower(end($file_ext));

          if(in_array($file_ext, $allowed)) { //make sure the uploaded file extension is in the list of allowed file extensions
            if($file_error === 0) {
              if($file_size <= 10*1048576) { //<=10MB
                $sanitisedName = preg_replace('/\s+/', '_', $file_name);
                //we need a new unique name
                $newName = uniqid('', true) . '.' . $file_ext;

                //where the file will be uploaded to
                $destination = $this->_path . $report_id . '/';
                //check to make sure the directory exists. if not make one
                if($this->checkExists($destination)===true) {
                  //moving the file from PHP tmp to the above directory
                  if(move_uploaded_file($file_temp, $destination . $newName)) {
                    //record the upload in the database
                    $database = $this->dbPut($uploader, $report_id, $sanitisedName, $newName);
                    if($database == true){ //if adding the record went okay
                      $uploaded[$key] = $file_name;
                    } else { //all the error reporting is here below
                      $failed[$file_name] = 'Could not record upload in the database';
                    }
                  } else {
                    $failed[$file_name] = 'Failed to move file to active directory';
                  }
                } else {
                  $failed[$file_name] = 'Could not create directory';
                }
              } else {
                $failed[$file_name] = 'File size of '. $file_size .' bytes too large. 10MB maximum';
              }
            } else {
              $failed[$file_name] = 'Upload failed with error code ' . $file_error;
            }
          } else {
            $failed[$file_name] = 'File type not allowed';
          }
        }
        $newArray['failed'] = $failed;
        $newArray['uploaded'] = $uploaded;
        return $newArray;
      }
    }

    public function remove($id) {
      $record = $this->_db->query("SELECT report_id, fileName FROM training_uploads WHERE id = ?",[[$id]])->first();
      if($record) {
        $reportID = $record->report_id;
        if(unlink($this->_path . $record->report_id . '/' . $record->fileName)) {
          if($this->dbDelete($id)) {
            if(count($this->directoryContents($reportID) != 0)) {
              return true;
            } else {
              return $this->removeDirectory($reportID);
            }
          } else {
            throw new Excpetion("There was a problem removing the file's record from the database");
          }
        } else {
          throw new Exception("There was a problem deleting the file from filesystem");
        }
      }

    }
    public function dbDelete($id) {
      $delete = $this->_db->query("DELETE FROM training_uploads WHERE id = ?", [[$id]]);
      if($delete) {
        return true;
      }
      return false;
    }


}
