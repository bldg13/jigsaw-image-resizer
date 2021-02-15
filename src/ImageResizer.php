<?php

    namespace bldg13\ImageResizer;

    class ImageResizer
    {
        public function resizeImageWidth()
        {

            $widths = [ 'small' => 400, 'medium' => 800, 'large' => 1024, 'xlarge' => 1600 ];

            $sourcePath = 'source/_images';
            $newPath = 'source/assets/images';
            $dirPerms = 0755;
            $recursive = true;

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

