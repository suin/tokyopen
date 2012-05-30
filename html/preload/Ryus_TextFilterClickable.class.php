<?php

define('SHORTEN_CLICKABLE_LENGTH', 80);
if (!defined('XOOPS_ROOT_PATH')) exit;

class Ryus_TextFilterClickable extends XCube_ActionFilter
{
    function preBlockFilter()
    {
        $this->mRoot->mDelegateManager->add(
                                            'Legacy_TextFilter.MakeClickableConvertTable',
                                            array(&$this, 'hook'),
                                            XCUBE_DELEGATE_PRIORITY_3
                                            );
    }

    function hook(&$patterns, &$replacements)
    {
        $http_pat = "(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)(?=[\s\x80-\xff]|$)";
                                                      // ([^, \r\n\"\(\)'<>]+)
        $patterns =
            array(
                  "/${http_pat}/ie",
                  "/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)(?=[\s\x80-\xff]|$)/ie",
                  "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)(?=[\s\x80-\xff]|$)/i",
                  "/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([a-z0-9!#\$%&'\*\+\-\/=\?^_\`{\|}~\.]+)(?=[\s\x80-\xff]|$)/i",
                  );
        $replacements =
            array(
                  "'\\1<a href=\"\\2://\\3\" target=\"_blank\">'.xoops_substr('\\2://\\3',0,SHORTEN_CLICKABLE_LENGTH).'</a>'",
                  "'\\1<a href=\"http://www.\\2.\\3\" target=\"_blank\">www.'.xoops_substr('\\2.\\3</a>',0,SHORTEN_CLICKABLE_LENGTH).'</a>'",
                  "\\1<a href=\"ftp://ftp.\\2.\\3\" target=\"_blank\">ftp.\\2.\\3</a>",
                  "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"
                  );
    }

}

?>
