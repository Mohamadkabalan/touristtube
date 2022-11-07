<?php

namespace TTBundle\vendors\AwsS3;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use TTBundle\vendors\AwsS3\Config as AwsS3Config;
use TTBundle\Model\AwsS3;
use TTBundle\Model\AwsS3File;
use TTBundle\Model\AwsS3List;

class AwsS3Handler
{

    /**
     * The class constructor when we create a new instance of AwsS3Handler class.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        // Initialize parameters
        $this->container = $container;
        $this->config    = new AwsS3Config($container);

        // Initialize S3Client
        $credentials  = new Credentials($this->config->access_key_id, $this->config->secret_access_key);
        $this->client = new S3Client([
            'version' => $this->config->version,
            'region' => $this->config->region,
            'credentials' => $credentials
        ]);
    }

    /**
     * This method lists the buckets on AwsS3.
     *
     * @return array response
     */
    public function listBuckets()
    {
        return $this->send("listBuckets");
    }

    /**
     * This method creates a new bucket on AwsS3.
     *
     * @param AwsS3 $s3
     *
     * @return array response
     */
    public function createBucket(AwsS3 $s3)
    {
        return $this->send("createBucket", [
                'Bucket' => $s3->getBucket(),
                'CreateBucketConfiguration' => [
                    'LocationConstraint' => $this->config->region,
                ]
        ]);
    }

    /**
     * This method deletes a bucket on AwsS3.
     *
     * @param AwsS3 $s3
     *
     * @return array response
     */
    public function deleteBucket(AwsS3 $s3)
    {
        return $this->send("deleteBucket", [
                'Bucket' => $s3->getBucket()
        ]);
    }

    /**
     * This method uploads a file to AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return array response
     */
    public function uploadFile(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        try {
            if ($file->isBinaryUpload() && (empty($file->getKey()) && empty($file->getContent()))) {
                return array('status' => false, 'error' => 'File empty.');
            } elseif (!file_exists($file->getSourceFile()) && empty($file->getContent())) {
                return array('status' => false, 'error' => 'File not found.');
            }

            // upload file
            $expr   = '{url:ObjectURL}';
            $result = $this->send("putObject", $file->toArray(), false, $expr);

            if (isset($result['url']) && !empty($result['url'])) {
                // get file
                return $this->getFile($file);
            } else {
                return array('status' => false, 'error' => 'An error is encountered when uploading the file.');
            }
        } catch (\Exception $ex) {
            return array('status' => false, 'error' => $ex->getMessage());
        }
    }

    /**
     * This method checks if an object exists or not on AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return boolean
     */
    public function fileExists(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        return $this->client->doesObjectExist($file->getBucket(), $file->getKey());
    }

    /**
     * This method renames an object on AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return array response
     */
    public function renameFile(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        $toreturn = [];

        // get objects' key
        $keys = $this->getKeys($file->getBucket(), $file->getKey());

        if (!empty($keys)) {
            // aws s3 directory usually ends with '/' else it's a file
            $isKeyDir    = (substr($file->getKey(), -1) === '/');
            $isNewKeyDir = (substr($file->getNewKey(), -1) === '/');

            if ($isKeyDir !== $isNewKeyDir) {
                $toreturn = ['status' => false, 'error' => "Cannot rename a directory into a file OR a file renamed into a directory."];
            }

            $filesToRename = [];
            foreach ($keys as $key) {
                if (strpos($key, $file->getKey()) !== FALSE) {
                    $filesToRename[] = [
                        'key' => $key,
                        'newKey' => str_replace($file->getKey(), $file->getNewKey(), $key)
                    ];
                }
                unset($key);
            }

            if (!empty($filesToRename)) {
                $keysToDelete = [];

                // copy object(s) and rename
                $toreturn['renamed']     = [];
                $toreturn['not_renamed'] = [];
                $toreturn['url']         = [];

                foreach ($filesToRename as $fileToRename) {
                    $item = new AwsS3File();
                    $item->setBucket($file->getBucket());
                    $item->setKey($fileToRename['key']);
                    $item->setNewKey($fileToRename['newKey']);
                    $item->setAcl($file->getAcl());

                    $copyResult = $this->copyFile($item);
                    if (isset($copyResult['url']) && !empty($copyResult['url'])) {
                        $toreturn['renamed'][] = $item->getNewKey();
                        $toreturn['url'][]     = $copyResult['url'];
                    } else {
                        $toreturn['not_renamed'][] = $item->getNewKey();
                    }

                    // track keys to delete
                    $keysToDelete[] = $fileToRename['key'];
                    unset($fileToRename, $item, $copyResult);
                }

                // delete object(s)
                $toreturn['deleted'] = [];
                if ($isKeyDir && empty($toreturn['not_renamed'])) {
                    // delete whole directory
                    $deleteResult = $this->deleteFile($file);
                    if (isset($deleteResult['deleted'])) {
                        $toreturn['deleted'] = array_merge($toreturn['deleted'], $deleteResult['deleted']);
                    }

                    unset($deleteResult);
                } else {
                    // delete each object
                    foreach ($keysToDelete as $key) {
                        $item = new AwsS3File();
                        $item->setBucket($file->getBucket());
                        $item->setKey($key);

                        $deleteResult = $this->deleteFile($item);
                        if (isset($deleteResult['deleted'])) {
                            $toreturn['deleted'] = array_merge($toreturn['deleted'], $deleteResult['deleted']);
                        }

                        unset($key, $item, $deleteResult);
                    }
                }

                // show files that are not removed
                $toreturn['not_deleted'] = array_diff($keysToDelete, $toreturn['deleted']);
                if (empty($toreturn['not_deleted'])) {
                    unset($toreturn['not_deleted']);
                }

                if (empty($toreturn['not_renamed'])) {
                    unset($toreturn['not_renamed']);
                }

                unset($toreturn['deleted'], $keysToDelete);
            } else {
                $toreturn = ['status' => false, 'error' => 'File does not exist.'];
            }

            unset($isKeyDir, $isNewKeyDir, $filesToRename);
        } else {
            $toreturn = ['status' => false, 'error' => 'File does not exist.'];
        }

        unset($file, $keys);
        return $toreturn;
    }

    /**
     * This method returns a file from AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return array response
     */
    public function getFile(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        $expr = '{url:"@metadata".effectiveUri, type:ContentType, size:ContentLength}';

        return $this->send("getObject", [
                'Bucket' => $file->getBucket(),
                'Key' => $file->getKey()
                ], false, $expr);
    }

    /**
     * This method deletes a file from AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return array response
     */
    public function deleteFile(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        //get the objects' key;
        $keys = $this->getKeys($file->getBucket(), $file->getKey());

        if (!empty($keys)) {
            // delete objects
            $expr     = '{deleted:Deleted[*].Key, statusCode:"@metadata".statusCode, url:"@metadata".effectiveUri}';
            $toreturn = $this->send('deleteObjects', [
                'Bucket' => $file->getBucket(),
                'Delete' => [
                    'Objects' => array_map(function($key) {
                            return ['Key' => $key];
                        }, $keys)
                ]
                ], false, $expr);

            if (isset($toreturn['deleted']) && !empty($toreturn['deleted'])) {
                $notDeleted = array_diff($keys, $toreturn['deleted']);
                if (!empty($notDeleted)) {
                    $toreturn['not_deleted'] = $notDeleted;
                }
                unset($notDeleted, $expr);
                return $toreturn;
            } else {
                return array('status' => false, 'error' => 'None of the file(s) is/are deleted.');
            }
        } else {
            return array('status' => false, 'error' => 'File does not exist.');
        }
    }

    /**
     * This method copies a file inside the same bucket or a different bucket in AwsS3.
     *
     * @param AwsS3File $file
     *
     * @return array response
     */
    public function copyFile(AwsS3File $file)
    {
        if (empty($file->getBucket())) {
            $file->setBucket($this->config->bucket);
        }

        $expr = '{url:ObjectURL}';

        return $this->send("copyObject", [
                'Bucket' => $file->getBucket(),
                'CopySource' => $file->getBucket().'/'.$file->getKey(),
                'Key' => $file->getNewKey(),
                'ACL' => $file->getAcl()
                ], false, $expr);
    }

    /**
     * This method lists the bucket files within s given key (prefix) in AwsS3.
     *
     * @param AwsS3List $list
     * @param string $expr  This is to override default JMESPath expression used on the method.
     * @param array $options
     *
     * @return array response
     */
    public function listFiles(AwsS3List $list, $expr = '', $options = array())
    {
        $default_options   = array('data_handler_key' => 'Contents', 'output_mode' => 'raw');
        $effective_options = array_merge($default_options, $options);

        $data_handler_key = $effective_options['data_handler_key'];

        if (empty($list->getBucket())) {
            $list->setBucket($this->config->bucket);
        }

        if (empty($expr)) {
            $actual_search_key = ''; // will hold the longest array element when the search string is split by '*'

            if (strpos($list->getSearchString(), '*') !== false) {
                foreach (explode('*', $list->getSearchString()) as $key) {
                    if (strlen($key) > strlen($actual_search_key)) $actual_search_key = $key;
                }
            }


            $expr = "{".$effective_options['data_handler_key'].":Contents[?contains(Key, '${actual_search_key}')].{key:Key, size:Size}, maxKeys:MaxKeys, keyCount:KeyCount, continuationToken:ContinuationToken, nextContinuationToken:NextContinuationToken}";
        }

        $toreturn = $this->send("listObjectsV2", $list->toArray(), true, $expr, $effective_options);


        if (empty($list->getSearchString())) return $toreturn;

        switch ($effective_options['output_mode']) {
            case 'single_array':

                $resetIndexes = false;

                foreach ($toreturn[$data_handler_key] as $indx => $content) {
                    $matchResult = preg_match($list->getSearchStringRegExPattern(), basename($content['key']));

                    if ($matchResult === false || !$matchResult) {
                        unset($toreturn[$data_handler_key][$indx]);

                        $toreturn['keyCount'] --;

                        $resetIndexes = true;
                    }
                }

                if ($resetIndexes) $toreturn[$data_handler_key] = array_values($toreturn[$data_handler_key]);


                break;
            case 'raw':
            default:
                foreach ($toreturn as &$returnItem) {
                    // filter our return item if searchString is not empty (applies the pattern only on the filenames and not the whole key)
                    if (isset($returnItem[$data_handler_key]) && $returnItem[$data_handler_key]) {
                        $contents = [];
                        foreach ($returnItem[$data_handler_key] as $content) {
                            $match = preg_match($list->getSearchStringRegExPattern(), basename($content['key']));
                            if ($match !== false && $match) {
                                $contents[] = $content;
                            }

                            unset($content, $match);
                        }

                        $returnItem[$data_handler_key] = $contents;
                        unset($contents);
                    }

                    $returnItem['keyCount'] = count($returnItem[$data_handler_key]);
                }
        }

        return $toreturn;
    }

    /**
     * This method list the keys of the files inside a certain bucket within a given key (prefix) in AwsS3.
     *
     * @param String $bucket
     * @param String $prefix    the key (optional)
     *
     * @return array list of file keys.
     */
    private function getKeys($bucket, $prefix = '')
    {
        $toreturn = [];

        // list objects and get the keys;
        $list = new AwsS3List();
        $list->setBucket($bucket);
        $list->setKey($prefix);

        $expr  = 'Contents[*].Key';
        $files = $this->listFiles($list, $expr);

        foreach ($files as $keyItems) {
            if (is_array($keyItems)) {
                $toreturn = array_merge($toreturn, $keyItems);
            }
        }

        return $toreturn;
    }

    /**
     * This method makes the request of a certain AwsS3 operation.
     *
     * @param String $operation
     * @param array $params
     * @param boolean $paginator
     * @param string $expr
     * @param array $options Currently used when pagination is requested; TODO:: use when no pagination is requested.
     *
     * @return array response
     */
    private function send($operation, array $params = array(), $paginator = false, $expr = '', $options = array())
    {
        $default_options   = array('data_handler_key' => 'Contents', 'output_mode' => 'raw');
        $effective_options = array_merge($default_options, $options);

        $data_handler_key = $effective_options['data_handler_key'];

        $contents = array();

        try {
            if ($paginator) {
                $result = $this->client->getPaginator(ucfirst($operation), $params);

                foreach ($result as $item) {
                    $newItems = null;

                    if (empty($expr)) $newItems = $item->toArray();
                    else $newItems = $item->search($expr);

                    if ($newItems == null || !isset($newItems[$data_handler_key]) || !$newItems[$data_handler_key]) continue;

                    switch ($effective_options['output_mode']) {
                        case 'single_array':
                            foreach ($newItems[$data_handler_key] as $newItem)
                                $contents[] = $newItem;
                            break;
                        case 'raw':
                        default:
                            $contents[] = $newItems;
                    }
                }

                return array($data_handler_key => $contents, 'keyCount' => count($contents));
            } else {
                $result = $this->client->{$operation}($params);

                if (empty($expr)) $contents = $result->toArray();
                else $contents = $result->search($expr);

                return $contents;
            }
        } catch (S3Exception $ex) {
            $contents['error'] = $ex->getAwsErrorMessage();
        }

        return $contents;
    }
}
