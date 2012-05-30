<?php


class Ryus_File
{
    public function removeDirectory($dir) {
      if ($handle = opendir("$dir")) {
       while (false !== ($item = readdir($handle))) {
         if ($item != "." && $item != "..") {
           if (is_dir("$dir/$item")) {
             self::removeDirectory("$dir/$item");
           } else {
             unlink("$dir/$item");

             //echo " removing $dir/$item<br>\n";

           }
         }
       }
       closedir($handle);
       rmdir($dir);
      }
    }
    
}