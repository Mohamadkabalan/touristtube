<?php

namespace TTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TTBundle\Model\AwsS3;
use TTBundle\Model\AwsS3File;

class AwsS3Controller extends Controller
{

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed object attributes.
    public function listBucketsAction()
    {
        $result = $this->get("AwsS3Services")->listBuckets();

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed object attributes.
    public function createBucketAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $bucket = (isset($criteria['bucket']) && !empty($criteria['bucket'])) ? $criteria['bucket'] : '';

        $awsS3 = new AwsS3();
        // Bucket name should conform with DNS requirements i.e. no uppercase chars, no underscores, 3-63 chars, not end with a dash, no adjacent periods, no dashes next to periods
        $awsS3->setBucket($bucket);

        $result = $this->get("AwsS3Services")->createBucket($awsS3);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed object attributes.
    public function deleteBucketAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $bucket = (isset($criteria['bucket']) && !empty($criteria['bucket'])) ? $criteria['bucket'] : '';

        $awsS3 = new AwsS3();
        $awsS3->setBucket($bucket);

        $result = $this->get("AwsS3Services")->deleteBucket($awsS3);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function uploadFileAction()
    {
        $result = '';

        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key      = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';
        //$path     = (isset($criteria['path']) && !empty($criteria['path'])) ? $criteria['path'] : '';
        $content  = (isset($criteria['content']) && !empty($criteria['content'])) ? $criteria['content'] : '';
        //$files    = (isset($_FILES['file']) && !empty($_FILES['file'])) ? $_FILES['file'] : [];
        $fileName = (isset($criteria['fileName']) && !empty($criteria['fileName'])) ? $criteria['fileName'] : [];
        $rest     = (isset($criteria['rest']) && !empty($criteria['rest'])) ? $criteria['rest'] : false;

        if (!empty($_FILES)) {
            $result = $this->get("AwsS3Services")->uploadFile($_FILES, $key, $fileName);
        } elseif (!empty($content) && !empty($fileName)) {
            $result = $this->get("AwsS3Services")->uploadFileBinary($content, $key, $fileName);
        }

        if ($rest) {
            return new Response($result);
        } else {
            $data = [
                'result' => $result
            ];

            return $this->render('awss3/awsS3Test.twig', $data);
        }
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function fileExistsAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';

        $result = $this->get("AwsS3Services")->fileExists($key);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function renameFileAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key    = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';
        $newKey = (isset($criteria['newKey']) && !empty($criteria['newKey'])) ? $criteria['newKey'] : '';

        $result = $this->get("AwsS3Services")->renameFile($key, $newKey);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed object attributes.
    public function getFileAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $bucket = (isset($criteria['bucket']) && !empty($criteria['bucket'])) ? $criteria['bucket'] : $this->container->getParameter('aws_s3')['bucket']['dev'];
        $key    = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';

        $file = new AwsS3File();
        $file->setBucket($bucket);
        $file->setKey($key);

        $result = $this->get("AwsS3Services")->getFile($file);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function deleteFileAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';

        $result = $this->get("AwsS3Services")->deleteFile($key);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function copyFileAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key    = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';
        $newKey = (isset($criteria['newKey']) && !empty($criteria['newKey'])) ? $criteria['newKey'] : '';

        $result = $this->get("AwsS3Services")->copyFile($key, $newKey);

        return new Response($result);
    }

    // This controller method is for testing/simulation purposes. The service method should be called directly with the needed parameters.
    public function listFilesAction()
    {
        $request  = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());

        $key          = (isset($criteria['key']) && !empty($criteria['key'])) ? $criteria['key'] : '';
        $searchString = (isset($criteria['searchString']) && !empty($criteria['searchString'])) ? $criteria['searchString'] : '';

        // Used to get a specific number of results up to 1000 a time ($maxKeys) and using a token to get the next result set
        //$maxKeys = (isset($criteria['maxKeys']) && !empty($criteria['maxKeys'])) ? $criteria['maxKeys'] : 0;
        //$token   = (isset($criteria['token']) && !empty($criteria['token'])) ? $criteria['token'] : '';

        $result = $this->get("AwsS3Services")->listFiles($key, $searchString);

        return new Response($result);
    }
}
