<?php

namespace TTBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends \Twig_Extension
{
    protected $container;
    private $storage_engine = '';
    private $subdomain_suffix = '';
    private $domain;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        if( $this->container->hasParameter('STORAGE_ENGINE') ) $this->storage_engine = $this->container->getParameter('STORAGE_ENGINE');
        if( $this->container->hasParameter('subdomain_suffix') ) $this->subdomain_suffix = $this->container->getParameter('subdomain_suffix');
        $this->domain = $this->container->getParameter('domain');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('generateLangURL', function ($lang, $path, $page_type = '')
            {
                $langroute        = '';
                if ($lang && $lang != 'en') $langroute        = '/'.$lang;
                if (substr($path, 0, 1) != '/') $path             = '/'.$path;

                if( strrpos($path, '/ajax/') !==false )
                {
                    $page_type = 'empty';
                }
                
                if( $page_type != 'empty' )
                {
                    $subdomain_root = 'www';

                    if (in_array($page_type, array('media', 'restaurants', 'corporate', 'channels', 'deals', 'where-is', 'nearby')))
                            $subdomain_root = $page_type;

                    $langroute = 'https://'.$subdomain_root.$this->subdomain_suffix.'.'.$this->domain.$langroute;
                }
                return $langroute.$path;
            }),
            new \Twig_SimpleFunction('generateLangRoute', function ($lang, $route, $parameters = array())
            {
                if ($lang && $lang != 'en') $route .= '_lang';
                return $this->container->get('router')->generate($route, $parameters, false);
            }),
            new \Twig_SimpleFunction('generateMediaURL', function ( $path, $full_path = false )
            {
                if (substr($path, 0, 1) != '/') $path = '/'.$path;
                $prefix_route = '';
                if( $this->storage_engine == 'aws_s3' || $full_path )
                {
                    $subdomain_root = 'www';
                    if( $this->storage_engine == 'aws_s3' ) $subdomain_root = 'static2';
					
					$suffix = $this->subdomain_suffix;					
					
                    $prefix_route = 'https://'.$subdomain_root.$suffix.'.'.$this->domain;
                }

                return $prefix_route.$path;
            })
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('generateLangURL', array($this, 'generateLangURLFilter')),
        );
    }

    public function generateLangURLFilter($pth)
    {
        return $pth.'_lang';
    }

    public function getName()
    {
        return 'app_extension';
    }
}
