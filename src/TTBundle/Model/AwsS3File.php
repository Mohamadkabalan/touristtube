<?php

namespace TTBundle\Model;

class AwsS3File extends AwsS3
{
    private $acl;
    private $content;
    private $sourceFile;
    private $contentType;
    private $contentLength;
    private $binaryUpload;

    public function __construct()
    {
        parent::__construct();

        $this->acl           = '';
        $this->content       = '';
        $this->sourceFile    = '';
        $this->contentType   = '';
        $this->contentLength = 0;
        $this->binaryUpload  = false;
    }

    public function getAcl()
    {
        return $this->acl;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getBase64EncodedContent()
    {
        if (base64_decode($this->getContent(), true) === false) {
            return base64_encode($this->getContent());
        }

        return $this->getContent();
    }

    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getContentLength()
    {
        return $this->contentLength;
    }

    public function isBinaryUpload()
    {
        return boolval($this->binaryUpload);
    }

    public function setAcl($acl)
    {
        $this->acl = $acl;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setSourceFile($sourceFile)
    {
        $this->sourceFile = $sourceFile;
        return $this;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
        return $this;
    }

    public function setBinaryUpload($binaryUpload)
    {
        $this->binaryUpload = boolval($binaryUpload);
        return $this;
    }

    public function toArray()
    {
        $toreturn = [
            'ACL' => $this->getAcl(),
            'Bucket' => $this->getBucket(),
            'Key' => $this->getKey(),
        ];

        if ($this->isBinaryUpload()) {
            $toreturn['Body'] = $this->getBase64EncodedContent();
        } elseif (!empty($this->getSourceFile())) {
            $toreturn['SourceFile'] = $this->getSourceFile();

            // initialize file data if source file provided exists.
            if (file_exists($this->getSourceFile())) {
                if (empty($this->getContentType()) || empty($this->getContentLength())) {
                    $content = file_get_contents($this->getSourceFile());

                    $size = strlen($content);
                    $type = mime_content_type($path);

                    $this->setContentLength($size);
                    $this->setContentType($type);
                }
            }
        }

        if ($this->isBinaryUpload()) {
            $toreturn['Body'] = $this->getBase64EncodedContent();
        } else {
            $content = '';
            if (!empty($this->getSourceFile())) {
                $toreturn['SourceFile'] = $this->getSourceFile();

                // initialize file data if source file provided exists.
                if (file_exists($this->getSourceFile())) {
                    $content = file_get_contents($this->getSourceFile());

                    if (!empty($content) && (empty($this->getContentType()) || empty($this->getContentLength()))) {
                        $size = strlen($content);
                        $type = mime_content_type($path);

                        $this->setContentLength($size);
                        $this->setContentType($type);
                    }
                }
            } else {
                $toreturn['Body'] = $this->getContent();
            }
        }

        if (!empty($this->getContentLength())) {
            $toreturn['ContentLength'] = $this->getContentLength();
        }

        if (!empty($this->getContentType())) {
            $toreturn['ContentType'] = $this->getContentType();
        }

        return $toreturn;
    }
}
