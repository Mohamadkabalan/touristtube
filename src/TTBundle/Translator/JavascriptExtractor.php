<?php
namespace TTBundle\Translator;
use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Extractor\FileVisitorInterface;

class JavascriptExtractor implements FileVisitorInterface
{
    public function visitFile(\SplFileInfo $file, MessageCatalogue $catalogue)
    {
        // print("\nParsing ". $file."\n");
        if ('.js' !== substr($file, -3)) {
            return;
        }
        $content = file_get_contents($file);
        if (null === $content || '' === $content) {
            return;
        }
        $matches = array();
        $content = preg_replace("/Translator\.trans/", "\nTranslator.trans", $content);
//        $m = preg_match_all("/Translator\.trans\s*\(\s*(['\"])(.+)\1/m", $content, $matches, PREG_SET_ORDER);
//        $m = preg_match_all("/Translator\.trans\s*\(\s*(['\"])(.+)(?<!\\\)\g{1}/xm", $content, $matches, PREG_SET_ORDER);
        $m = preg_match_all("/Translator\.trans\s*\(\s*(['\"])(.+)(?<!\\\)\g{1}\s*\)\s*(?!Translator\.trans)/xmU", $content, $matches, PREG_SET_ORDER);
        
        if ($m !== false && $m)
        {
            foreach ($matches as $match)
            {
    //            if (!preg_match('/(^|\.)MSG_[^.]+$/', $k)) {
    //                continue;
    //            }
                print("\n". $match[2]." (found in $file)\n");
                $message = new Message($match[2], 'client');
                $message->addSource(new FileSource((string) $file));
                $catalogue->add($message);
            }
        }
    }
    public function visitPhpFile(\SplFileInfo $file, MessageCatalogue $catalogue, array $ast) { }
    public function visitTwigFile(\SplFileInfo $file, MessageCatalogue $catalogue, \Twig_Node $node) { }
}