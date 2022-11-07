<?php

namespace TTBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;

class TTFileUtils
{
    protected $utils;
    protected $container;
    private $CONFIG_SERVER_ROOT;
    private $storage_engine = '';
    
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils         = $utils;
        $this->container     = $container;
        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine = $this->container->getParameter('STORAGE_ENGINE');
        $this->CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
    }

    public function fileExists($path, $bypass_storage_engine = false)
    {
        if(!$bypass_storage_engine && $this->storage_engine == 'aws_s3')
        {
            $path = str_replace($this->CONFIG_SERVER_ROOT, '', $path);

            $fileExists = $this->container->get("AwsS3Services")->fileExists( $path );
            
            if( !$fileExists || $fileExists == 'false' ) return false;
            else return true;
        }
        else
        {
            return file_exists( $path );
        }
    }

    public function unlinkFile( $file )
    {
        if( $this->storage_engine == 'aws_s3' )
        {
            $file = str_replace($this->CONFIG_SERVER_ROOT, '', $file);

            return $this->container->get("AwsS3Services")->deleteFile( $file );
        }
        else
        {
            return @unlink($file);
        }
    }

    public function globFiles( $key, $searchString = '', $flags = 0)
    {
        if( $this->storage_engine == 'aws_s3' )
        {
            $key = str_replace($this->CONFIG_SERVER_ROOT, '', $key);

            $list = $this->container->get("AwsS3Services")->listFiles($key, $searchString, array('data_handler_key' => 'contents', 'output_mode' => 'single_array'));

            $list = json_decode($list, true);
			
            $list_array = array();
            if (!isset($list) || !isset($list['contents'])) // also check for data_handler_key
		return $list_array;
	   
	foreach ($list['contents'] as $item)
	{
		$list_array[] = $item['key'];
	}
	    

            return $list_array;
        }
        else
        {
            return glob( $key.$searchString, $flags);
        }
    }

    public function renameFile( $file, $new_filename )
    {
        if( $this->storage_engine == 'aws_s3' )
        {
            $file = str_replace($this->CONFIG_SERVER_ROOT, '', $file);
            $new_filename = str_replace($this->CONFIG_SERVER_ROOT, '', $new_filename);

            return $this->container->get("AwsS3Services")->renameFile( $file, $new_filename );
        }
        else
        {
            return rename( $file, $new_filename );
        }
    }

    public function copyFile( $file, $new_filename )
    {
        if( $this->storage_engine == 'aws_s3' )
        {
            $file = str_replace($this->CONFIG_SERVER_ROOT, '', $file);
            $new_filename = str_replace($this->CONFIG_SERVER_ROOT, '', $new_filename);

            return $this->container->get("AwsS3Services")->copyFile( $file, $new_filename );
        }
        else
        {
            return copy( $file, $new_filename );
        }
    }

    public function getImageSizeFile( $file, $add_static2 = true )
    {
        if( $this->storage_engine == 'aws_s3' && $add_static2 )
        {
            $file = str_replace($this->CONFIG_SERVER_ROOT, '', $file);
            $file = $this->container->get("TTRouteUtils")->generateMediaURL($file);
        }
        
        return getimagesize( $file );
    }

    public function getImageSizeFromStringFile( $file )
    {
        if( $this->storage_engine == 'aws_s3' )
        {
            $file = str_replace($this->CONFIG_SERVER_ROOT, '', $file);
            $file = $this->container->get("TTRouteUtils")->generateMediaURL($file);
        }

        return getimagesizefromstring( $file );
    }
}
