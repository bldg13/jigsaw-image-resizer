<?php

    namespace Bldg13;
    use Bldg13\Helpers\Config\Config;

    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;
    use RecursiveRegexIterator;
    use RegexIterator;

    require 'config/Config.php';
    
    class ImageResizer
    {
        public static function resizeImageWidth()
        {
            $config = new Config;

            // load package config file
            $config->load(__DIR__ . '/config.php');
            
            // or load Jigsaw config file
            // $config->load('config.php');
            
            $widths = $config->get('image-resizer.widths.large');
            $sourcePath = $config->get('image-resizer.paths.source');
            $newPath = $config->get('image-resizer.paths.new');
            $dirPerms = $config->get('image-resizer.directory.permissions');
            $recursive = $config->get('image-resizer.directory.recursive');
            
            // var_dump($sourcePath); die();

            $directory = new RecursiveDirectoryIterator($sourcePath);
            $iterator = new RecursiveIteratorIterator($directory);
            $regex = new RegexIterator($iterator, '@^(?<directory>.+)/(?<filename>[^/]+)\.(?P<extension>
                        jpe?g|png)$@i', RecursiveRegexIterator::GET_MATCH);

            foreach ($regex as $entry => $match) {
                
                $newDirectoryPath = str_replace($sourcePath, $newPath, $match['directory']);

                // set the original filename w/o the extension
                $originalFilename = $match['filename'];

                // set the original extension
                $originalExtension = $match['extension'];

                // write a new file for each width 
                // and name it based on the width and original filename
                foreach ($widths as $title => $size) {

                    if (! is_dir($newDirectoryPath . '/' . $title)) {
                        mkdir($newDirectoryPath . '/' . $title, $dirPerms, $recursive);
                    }
                    
                    // create a new Imagick instance
                    $newImage = new Imagick($entry);
                    // var_dump($newImage); die();

                    // compress and strip out some junk
                    if ($originalExtension == 'jpg' && 'jpeg') {
                        $newImage->setImageCompression(Imagick::COMPRESSION_JPEG);
                        $newImage->setImageCompressionQuality(85);
                    }
                    $newImage->stripImage();
                    
                    // using 0 in the second $arg will keep the same image ratio
                    $newImage->resizeImage($size,0, imagick::FILTER_LANCZOS, 0.9);

                    // write new image at 'images/small/filename.jpg'
                    $newImage->writeImage($newDirectoryPath . '/' . $title . '/' . $originalFilename . '.' . $originalExtension);
                }
            }
        }
    }

