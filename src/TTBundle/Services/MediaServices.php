<?php

namespace TTBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaServices
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @param unknown $path
     * @param unknown $fileName
     *            This function prepares the header parameters to download each image
     */
    private function prepareMediaHeader($path, $fileName = null)
    {
        if (!isset($fileName)) $fileName = basename($path);
        //
        $value    = exif_imagetype($path);
        if (!$value) {

            header("Content-Type: application/force-download");
        }

        $contentType = mime_content_type($path);
        if (!isset($contentType) || $contentType == "") $contentType = "application/octet-stream";

        // header("Content-Disposition: attachment; filename=\"".$fileName."\"");
        header("Content-Type: ".$contentType);
        // header("Content-Length: " . filesize($filepath));
        header("Connection: close");
    }

    public function downloadMedia($path, $fileName = null, $width = null, $height = null)
    {
        // If STORAGE_ENGINE param is set, use the S3 path to echo the image else use the normal disk path
        $storage_engine = '';
        if ($this->container->hasParameter('STORAGE_ENGINE')) {
            $storage_engine = $this->container->getParameter('STORAGE_ENGINE');
        }

        if ($storage_engine == 'aws_s3') {
            $image = "";

            $service = $this->container->get('AwsS3Services');

            if ($service->fileExists($path)) {
                $file = new \TTBundle\Model\AwsS3File();
                $file->setKey($path);

                $result = json_decode($service->getFile($file), true);
                if (isset($result['url']) && !empty($result['url'])) {
                    // $image = file_get_contents($result['url']);
                    $image = file_get_contents($this->container->get('TTRouteUtils')->generateMediaURL($path));
                }

                if (!isset($result['type'])) {
                    // unknown filetype is set to application/octet-stream.
                    $result['type'] = 'application/octet-stream';
                }

                header("Content-Type: {$result['type']}");
            }
        } else {
            if ($this->container->get("TTFileUtils")->fileExists($path) && is_file($path)) {
                if ((isset($width) || isset($height)) && ($width != "" && $height != "")) {
                    $image = $this->scaleImageFileToBlob($path, $width, $height);
                } else {
                    $handle = fopen($path, "rb");
                    $image  = fread($handle, filesize($path));
                    fclose($handle);
                }
                $this->prepareMediaHeader($path, $fileName);
            } else {
                $image = "";
            }
        }

        echo $image;
    }

    /**
     *
     * @param unknown $file
     * @param unknown $max_width
     * @param unknown $max_height
     * @return string This function is responbile of resizing the required image
     */
    private function scaleImageFileToBlob($file, $max_width = null, $max_height = null)
    {
        list ($width, $height, $image_type) = $this->container->get("TTFileUtils")->getImageSizeFile($file);

        if (!isset($max_width)) $max_width  = $width;
        if (!isset($max_height)) $max_height = $height;

        switch ($image_type) {
            case 1:
                $src = imagecreatefromgif($file);
                break;
            case 2:
                $src = imagecreatefromjpeg($file);
                break;
            case 3:
                $src = imagecreatefrompng($file);
                break;
            default:
                return '';
                break;
        }

        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        if (($width <= $max_width) && ($height <= $max_height)) {
            $tn_width  = $width;
            $tn_height = $height;
        } elseif (($x_ratio * $height) < $max_height) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width  = $max_width;
        } else {
            $tn_width  = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }

        $tmp = imagecreatetruecolor($tn_width, $tn_height);

        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($image_type == 1) or ( $image_type == 3)) {
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
            imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
        }
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

        /*
         * imageXXX() only has two options, save as a file, or send to the browser.
         * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
         * So I start the output buffering, use imageXXX() to output the data stream to the browser,
         * get the contents of the stream, and use clean to silently discard the buffered contents.
         */
        ob_start();

        switch ($image_type) {
            case 1:
                imagegif($tmp);
                break;
            case 2:
                imagejpeg($tmp, NULL, 100);
                break; // best quality
            case 3:
                imagepng($tmp, NULL, 0);
                break; // no compression
            default:
                echo '';
                break;
        }

        $final_image = ob_get_contents();

        ob_end_clean();

        return $final_image;
    }
}
