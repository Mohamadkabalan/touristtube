<?php

namespace TTBundle\vendors\AwsS3;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'access_key_id'}                 = $container->getParameter('aws_s3')['credentials']['access_key_id'];
        $this->{'secret_access_key'}             = $container->getParameter('aws_s3')['credentials']['secret_access_key'];
        $this->{'version'}                       = $container->getParameter('aws_s3')['version'];
        $this->{'region'}                        = $container->getParameter('aws_s3')['region'];
        $this->{'bucket'}                        = $container->getParameter('aws_s3')['bucket'];
        $this->{'acl_private'}                   = $container->getParameter('aws_s3')['acl']['private'];
        $this->{'acl_public_read'}               = $container->getParameter('aws_s3')['acl']['public-read'];
        $this->{'acl_public_read_write'}         = $container->getParameter('aws_s3')['acl']['public-read-write'];
        $this->{'acl_authenticated_read'}        = $container->getParameter('aws_s3')['acl']['authenticated-read'];
        $this->{'acl_aws_exec_read'}             = $container->getParameter('aws_s3')['acl']['aws-exec-read'];
        $this->{'acl_bucket_owner_read'}         = $container->getParameter('aws_s3')['acl']['bucket-owner-read'];
        $this->{'acl_bucket_owner_full_control'} = $container->getParameter('aws_s3')['acl']['bucket-owner-full-control'];
    }
}
