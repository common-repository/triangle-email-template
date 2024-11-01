<?php

namespace Triangle\Helper;

!defined( 'WPINC ' ) or die;

/**
 * Helper library for Triangle plugins
 *
 * @package    Triangle
 * @subpackage Triangle\Includes
 */

class Directory {

    /**
     * Get lists of directories
     * @return  void
     * @var     string  $path   Directory path
     */
    public function getDir($path, $directories = []) {
        foreach(glob($path.'/*', GLOB_ONLYDIR) as $dir) {
            $directories[] = basename($dir);
        }
        return $directories;
    }

    /**
     * Get files within directory
     * @return  void
     * @var     string  $dir   plugin hooks directory (Api, Controller)
     */
    public function getDirFiles($dir, &$results = array()) {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                self::getDirFiles($path, $results);
            }
        }
        return $results;
    }

    /**
     * Check directories is empty
     */
    public function is_dir_empty($dir) {
        if (!is_readable($dir)) return NULL;
        return (count(scandir($dir)) == 2);
    }

    /**
     * Delete directories and files
     * @return void
     */
    public function deleteDir($dirPath) {
        system('rm -rf -- ' . escapeshellarg($dirPath), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

    /**
     * Copy directories contents (Files and Dir) to another directories
     * @return void
     */
    public function copyDir($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDir($src . '/' . $file,$dst . '/' . $file);
                } else { copy($src . '/' . $file,$dst . '/' . $file); }
            }
        }
        closedir($dir);
    }

}