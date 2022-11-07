<?php

class Twig_Extension_twigTT extends Twig_Extension {

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('lang', array($this, 'langTwig')),
            new Twig_SimpleFilter('link', array($this, 'linkTwig')),
            new Twig_SimpleFilter('cond', array($this, 'condTwig'), array('pre_escape' => true)),
            new Twig_SimpleFilter('cond2', array($this, 'condTwig'), array('pre_escape' => null)),
        );
    }

    public function langTwig($str, $arr = Null) {
        if ($arr == Null) {
            return _($str);
        } else {
            if (is_array($arr)) {
                return sprintf(_($str), $arr);
            } else {
                $singleArr = array($arr);
                return sprintf(_($str), $singleArr);
            }
        }
    }

    public function linkTwig($str) {
        return ReturnLink($str);
    }

    public function condTwig($str, $condition, $val2 = NULL) {
        if ($condition) {
            return $str;
        } else {
            if ($val2 != NULL) {
                return $val2;
            } else {
                return '';
            }
        }
    }

    public function getName() {
        return 'langTwig';
    }

    public function langGet() {
        return LanguageGet();
    }

}
