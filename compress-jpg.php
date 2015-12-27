function itm_optimizeImage($file, $compression = 70, $maxDimensions = ['width' => null, 'height' => null]) {
    $save = false;
    $fi = new finfo(FILEINFO_MIME);
    $mime = explode(';', $fi->file($file));
    switch ($mime[0]) {
        // possible to optimize other image types in the future
        case 'image/jpeg':
            try {
                $iMagick = new Imagick($file);
                if ($iMagick->getImageCompressionQuality() > $compression) {
                    $file = !itm_compressJPEG($file, $compression, $maxDimensions, $iMagick);
                }
            }
            catch (Exception $e) {
                error_log(__FUNCTION__ . " $path/$file failed: " . $e->getMessage());
                return false;
            }
            if ($file) {
                $pathParts = pathinfo($file);
                rename($file, $pathParts['dirname'] . '/' . $pathParts['filename'] . '.large.' . $pathParts['extension']);
                $iMagick->writeImage($file);
            }
            $iMagick->clear();
            break;
    }

    return $file;
}

function itm_compressJPEG($file, $compression = 70, $maxDimensions = ['width' => null, 'height' => null], &$iMagick = null) {
    try {
        $iMagickCreated = true;
        if ($iMagick) $iMagickCreated = false;
        else $iMagick = new Imagick($file);

        $iMagick->setImageResolution(72,72);
        $iMagick->resampleImage(72,72,imagick::FILTER_UNDEFINED,1);
        $geometry = $iMagick->getImageGeometry();
        if (($geometry['width'] / $maxDimensions['width']) > ($geometry['height'] / $maxDimensions['height'])) {
            // scale by width
            $iMagick->scaleImage($maxDimensions['width'], 0);
        } else {
            // scale by height
            $iMagick->scaleImage(0, $maxDimensions['height']);
        }
        $iMagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $iMagick->setImageCompressionQuality($compression);
        $iMagick->setImageFormat('jpg');
        $iMagick->stripImage();

        if ($iMagickCreated) {
            $pathParts = pathinfo($file);
            rename($file, $pathParts['dirname'] . '/' . $pathParts['filename'] . '.large.' . $pathParts['extension']);
            $iMagick->writeImage($file);
            $Imagick->clear();
        }
        return $file;
    }
    catch (Exception $e) {
        error_log(__FUNCTION__ . " $path/$file failed: " . $e->getMessage());
        return false;
    }
}