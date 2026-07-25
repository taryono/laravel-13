<?php

namespace Lib;
 
use Illuminate\Support\Str;

class Directories
{
    protected $main_folder;
    protected $allowed = [];
    public function __construct($main_folder, $allowed = ['php', 'html', 'css', 'js', 'txt'])
    {
        $this->main_folder = $main_folder;
        $this->allowed = $allowed;
    }

    public function listFolderFiles($main = null)
    {
        $dir = $this->getMainFolder();
        $fileFolderList = scandir($dir);
        $lists = '<ul class="drop" id="menu">';
        foreach ($fileFolderList as $fileFolder) {
            if ($fileFolder != '.' && $fileFolder != '..') {
                if (!is_dir($dir . '/' . $fileFolder)) {
                    $ext = pathinfo($fileFolder, PATHINFO_EXTENSION);
                    if (in_array($ext, $this->getAllowed())) {
                        $lists .= '<li><a href="' . base_path() . '/index.php?page=' . ltrim($dir . '/' . $fileFolder, './') . '"><i class="fas fa-file-alt"></i>' . $fileFolder . '</a>';
                    }
                } else {
                    $lists .= '<li><a href="#"><i class="fas fa-folder-open"></i>' . $fileFolder . '</a>';
                }
                if (is_dir($dir . '/' . $fileFolder)) {
                    $main = $dir . '/' . $fileFolder;
                    $this->listFolderFiles($main);
                }
                $lists .= '</li>';
            }
        }
        $lists .= '</ul>';
        return $lists;
    }

    public function getModules($parent = "")
    {
        $dir = !empty($parent) ? $parent : $this->getMainFolder();
        $fileFolderList = scandir($dir);

        $folders = [];
        foreach ($fileFolderList as $fileFolder) {
            if ($fileFolder != '.' && $fileFolder != '..') {
                if ($fileFolder == "Controllers") {
                    $folders[] = $fileFolder;
                }

                if ($fileFolder == "Views") {
                    return;
                }

                if (!is_dir($dir . '/' . $fileFolder)) {
                    $ext = pathinfo($fileFolder, PATHINFO_EXTENSION);
                    if (in_array($ext, $this->getAllowed())) {
                        $files[] = $fileFolder;
                    }
                } else {
                    $folders[] = $dir . '/' . $fileFolder;

                    if (is_dir($dir . '/' . $fileFolder)) {
                        if ($this->getModules($dir . '/' . $fileFolder)) {
                            $folders[$fileFolder][] = $this->getModules($dir . '/' . $fileFolder);
                        }

                    }
                }
            }
        } 
        return collect(array_filter($folders))->flatten();
    }

    public function getFolders($parent = "")
    {
        $dir = !empty($parent) ? $parent : $this->getMainFolder();
        $fileFolderList = scandir($dir);

        $folders = [];
        foreach ($fileFolderList as $fileFolder) {
            if ($fileFolder != '.' && $fileFolder != '..') {
                if ($fileFolder == "Controllers") {
                    $folders[] = $fileFolder;
                }

                if ($fileFolder == "Views") {
                    return;
                }

                if (!is_dir($dir . '/' . $fileFolder)) {
                    $ext = pathinfo($fileFolder, PATHINFO_EXTENSION);
                    if (in_array($ext, $this->getAllowed())) {
                        $files[] = $fileFolder;
                    }
                } else {
                    $folders[] = $dir . '/' . $fileFolder;

                    if (is_dir($dir . '/' . $fileFolder)) {
                        if ($this->getModules($dir . '/' . $fileFolder)) {
                            $folders[$fileFolder][] = $this->getModules($dir . '/' . $fileFolder);
                        }
                    }
                }
            }
        }


        // 1. Ambil semua key string
        $stringKeys = array_filter(array_keys($folders), 'is_string');

        // 2. Filter hanya key numerik
        $numericValues = array_filter($folders, 'is_string', ARRAY_FILTER_USE_KEY);

        // 3. Buang numeric values yang mengandung string key
        $result = array_filter($folders, function ($value, $key) use ($stringKeys) {
            if (!is_int($key)) return false; // hanya key numerik
            foreach ($stringKeys as $strKey) {
                if (stripos($value, $strKey) !== false) {
                    return false; // drop kalau mengandung string key
                }
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
        $all_modules = [];
        foreach ($result as $key => $value) {    
            
            $all_modules[Str::snake(getLastFolder($value))] = null;

        } 
         foreach ($numericValues as $key => $value) {
            $all_modules[Str::snake($key)] = $value[0]->map(function ($item) {
                return Str::snake(getLastFolder($item));
            })->toArray();
         }  
       return $all_modules;
    }

    /**
     * Get the value of allowed
     */
    public function getAllowed()
    {
        return $this->allowed;
    }

    /**
     * Set the value of allowed
     */
    public function setAllowed($allowed): self
    {
        $this->allowed = $allowed;

        return $this;
    }

    /**
     * Get the value of main_folder
     */
    public function getMainFolder()
    {
        return $this->main_folder;
    }

    /**
     * Set the value of main_folder
     */
    public function setMainFolder($main_folder): self
    {
        $this->main_folder = $main_folder;

        return $this;
    }
}
