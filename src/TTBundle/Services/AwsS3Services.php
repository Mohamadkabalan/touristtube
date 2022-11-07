<?php

namespace TTBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\vendors\AwsS3\AwsS3Handler;
use TTBundle\Model\AwsS3;
use TTBundle\Model\AwsS3File;
use TTBundle\Model\AwsS3List;

class AwsS3Services
{

    public function __construct(ContainerInterface $container)
    {
        $this->container    = $container;
        $this->AwsS3Handler = new AwsS3Handler($container);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @return json response
     */
    public function listBuckets()
    {
        $result = $this->AwsS3Handler->listBuckets();
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param AwsS3 $s3
     *
     * @return json response
     */
    public function createBucket(AwsS3 $s3)
    {
        $result = $this->AwsS3Handler->createBucket($s3);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param AwsS3 $s3
     *
     * @return json response
     */
    public function deleteBucket(AwsS3 $s3)
    {
        $result = $this->AwsS3Handler->deleteBucket($s3);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param array $files
     * @param string $key
     * @param mixed $fileNames  a filename or a list of filenames
     *
     * @return json response
     */
    public function uploadFile(array $files, $key, $fileNames = '')
    {
        $toreturn = array();

        try {
            $acl = $this->container->getParameter('aws_s3')['acl']['public-read'];

            // Iterate over $_FILES  since we might have more than 1 file sent
            if (!empty($files)) {
                $filesToUpload = [];

                foreach ($files as $fileItems) {
                    if (isset($fileItems['tmp_name']) && !empty($fileItems['tmp_name'])) {
                        if (is_array($fileItems['tmp_name'])) {
                            // multiple upload
                            $ctr = count($fileItems['tmp_name']);
                            while ($ctr > 0) {
                                $file = [
                                    'name' => array_shift($fileItems['name']),
                                    'type' => array_shift($fileItems['type']),
                                    'tmp_name' => array_shift($fileItems['tmp_name']),
                                    'size' => array_shift($fileItems['size']),
                                ];

                                if (!empty($file['tmp_name'])) {
                                    $filesToUpload[] = $file;
                                }

                                $ctr--;
                                unset($file);
                            }

                            unset($ctr);
                        } else {
                            // single upload
                            $filesToUpload[] = $fileItems;
                        }
                    }
                    unset($fileItems);
                }

                if (!empty($filesToUpload)) {
                    // if $fileNames is not an array, then this fileName is used on the first file to upload.
                    if (!empty($fileNames) && !is_array($fileNames)) {
                        $fileName = $fileNames;

                        $fileNames                            = [];
                        $fileNames[$filesToUpload[0]['name']] = $fileName;
                        unset($fileName);
                    }

                    foreach ($filesToUpload as $file) {
                        // check the name to use.
                        $fileName = $file['name'];

                        if (isset($fileNames[$fileName])) {
                            $fileName = $fileNames[$fileName];
                        }

                        // prioritize $fileName if a filename is present on our $key.
                        $fileKey  = $key;
                        $pathinfo = pathinfo($fileKey);
                        if (isset($pathinfo['extension'])) {
                            $fileKey = "{$pathinfo['dirname']}/{$fileName}";
                        } else {
                            $fileKey = "{$fileKey}/{$fileName}";
                        }

                        // initialize and validate our path.
                        $path = "";
                        if (isset($file['tmp_name']) && !empty($file['tmp_name'])) {
                            $path = $file['tmp_name'];
                        } elseif (isset($file['path']) && !empty($file['path'])) {
                            $path = $file['path'];
                        }

                        $type = (isset($file['type']) && !empty($file['type'])) ? $file['type'] : '';
                        $size = (isset($file['size']) && !empty($file['size'])) ? $file['size'] : 0;

                        if (!empty($path)) {
                            $file = new AwsS3File();
                            $file->setKey($fileKey);
                            $file->setAcl($acl);
                            $file->setSourceFile($path);
                            $file->setContentType($type);
                            $file->setContentLength($size);

                            $toreturn[$fileName] = $this->AwsS3Handler->uploadFile($file);
                        } else {
                            $toreturn[$fileName] = ['error' => 'path not provided.'];
                        }

                        unset($file, $fileName, $pathinfo, $fileKey, $path, $type, $size);
                    }
                } else {
                    $toreturn['error'] = 'There are no files to upload.';
                }
            }

            unset($acl);
        } catch (\Exception $ex) {
            //$toreturn['error'] = "An error is encountered while uploading the file. Please contact the administrator";
            $toreturn['error'] = $ex->getTraceAsString();
        }

        unset($files, $key, $fileNames);
        return json_encode($toreturn, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $fileContent
     * @param string $key
     * @param string $fileName
     * @param string $fileType
     * @param boolean $binaryUpload
     *
     * @return json response
     */
    public function uploadFileBinary($fileContent, $key, $fileName, $fileType = '', $binaryUpload = true)
    {
        $toreturn = array();

        try {
            // for now, this method is a single upload i.e. takes ONE content and ONE filename
            $acl = $this->container->getParameter('aws_s3')['acl']['public-read'];

            // prioritize $fileName if a filename is present on our $key.
            $pathinfo = pathinfo($key);
            if (isset($pathinfo['extension'])) {
                $key = "{$pathinfo['dirname']}/{$fileName}";
            } else {
                $key = "{$key}/{$fileName}";
            }

            // check if the content provided is already a base64 encoded or not
            // before we get necessary file data (e.g. size, type, etc.)
            $content = $fileContent;
            if (base64_decode($fileContent, true) === false) {
                $content = base64_decode($content);
            }

            $size = strlen($content);

            if (empty($fileType)) {
                $finfo    = finfo_open();
                $fileType = finfo_buffer($finfo, $content, \FILEINFO_MIME_TYPE);

                finfo_close($finfo);
            }

            $file = new AwsS3File();
            $file->setKey($key);
            $file->setAcl($acl);
            $file->setContent($fileContent);
            $file->setContentType($fileType);
            $file->setContentLength($size);
            $file->setBinaryUpload($binaryUpload);

            $toreturn = $this->AwsS3Handler->uploadFile($file);

            unset($acl, $pathinfo, $size, $file);
        } catch (\Exception $ex) {
            //$toreturn['error'] = "An error is encountered while uploading the file. Please contact the administrator";
            $toreturn['error'] = $ex->getTraceAsString();
        }

        unset($fileContent, $key, $fileName, $fileType, $binaryUpload);
        return json_encode($toreturn, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $key
     *
     * @return boolean
     */
    public function fileExists($key)
    {
        $file = new AwsS3File();
        $file->setKey($key);

        $result = $this->AwsS3Handler->fileExists($file);
        return json_encode($result);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $key
     * @param string $newKey
     *
     * @return json response
     */
    public function renameFile($key, $newKey)
    {
        $acl = $this->container->getParameter('aws_s3')['acl']['public-read'];

        $file = new AwsS3File();
        $file->setKey($key);
        $file->setNewKey($newKey);
        $file->setAcl($acl);

        $result = $this->AwsS3Handler->renameFile($file);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return json response
     */
    public function getFile(AwsS3File $file)
    {
        $result = $this->AwsS3Handler->getFile($file);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $key
     *
     * @return json response
     */
    public function deleteFile($key)
    {
        $file = new AwsS3File();
        $file->setKey($key);

        $result = $this->AwsS3Handler->deleteFile($file);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $key
     * @param string $newKey
     *
     * @return json response
     */
    public function copyFile($key, $newKey)
    {
        $acl = $this->container->getParameter('aws_s3')['acl']['public-read'];

        $file = new AwsS3File();
        $file->setKey($key);
        $file->setNewKey($newKey);
        $file->setAcl($acl);

        $result = $this->AwsS3Handler->copyFile($file);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }

    /**
     * This method calls the handler method of AwsS3.
     *
     * @param string $key
     * @param string $searchString (search pattern)
     * @param array $options
     *
     * @return json response
     */
    public function listFiles($key, $searchString = '', $options = array())
    {
        $default_options   = array('data_handler_key' => 'Contents', 'output_mode' => 'raw');
        $effective_options = array_merge($default_options, $options);

        $list = new AwsS3List();
        $list->setKey($key);
        $list->setSearchString($searchString);

        $result = $this->AwsS3Handler->listFiles($list, '', $effective_options);
        return json_encode($result, \JSON_UNESCAPED_SLASHES);
    }
}
