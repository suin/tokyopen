<?php

if (!defined('XOOPS_ROOT_PATH')) exit;

if (!class_exists('SslFilter')) {
    class SslFilter extends XCube_ActionFilter
    {
        function postFilter()
        {
            if (class_exists('WizMobile')) {
                $user =& Wizin_User::getSingleton();
                if ($user->bIsMobile == false) {
                    ob_start(array($this, '_sslFilter'));
                }
            } else {
                ob_start(array($this, '_sslFilter'));
            }
        }

        function _sslFilter($buf = '')
        {
            $sslXoopsUrl = str_replace('http://', 'https://', XOOPS_URL);
            $replaceLinkPattern = '(' . strtr(XOOPS_URL, array('/' => '\/', '.' => '\.')) . ')('
                . '\/user\.php|\/register\.php|\/edituser\.php|\/lostpass\.php|\/userinfo\.php|\/modules\/inquiry)(\S*)';
            // link
            //
            $pattern = '(<a)([^>]*)(href=)([\"\'])' . $replaceLinkPattern . '([\"\'])([^>]*)(>)';
            preg_match_all("/" . $pattern . "/i", $buf, $matches, PREG_SET_ORDER);
            if (!empty($matches)) {
                foreach ($matches as $key => $match) {
                    $link = str_replace($match[5], $sslXoopsUrl, $match[0]);
                    $buf = str_replace($match[0], $link, $buf);
                }
            }
            // form
            //
            $pattern = '(<form)([^>]*)(action=)([\"\'])' . $replaceLinkPattern . '([\"\'])([^>]*)(>)';
            preg_match_all("/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER);
            if (!empty($matches)) {
                foreach ($matches as $key => $match) {
                    if (!empty($match[5])) {
                        $form = $match[0];
                        $action = $match[5];
                        if (substr($action, 0, 5) === 'http:') {
                            $form = str_replace($match[5], $sslXoopsUrl, $match[0]);
                            $buf = str_replace($match[0], $form, $buf);
                        }
                        $action = '';
                    }
                }
            }
            if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'] === 'on')) {
                $replaceLinkPattern = '(' . strtr(XOOPS_URL, array('/' => '\/', '.' => '\.')) . ')(\S*)';
                // replace google analytics url
                $buf = str_replace('http://www.google-analytics.com/urchin.js',
                    'https://ssl.google-analytics.com/urchin.js', $buf);
                // replace img link
                $pattern = '(<img)([^>]*)(src=)([\"\'])' . $replaceLinkPattern . '([\"\'])([^>]*)(>)';
                preg_match_all("/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER);
                if (!empty($matches)) {
                    foreach ($matches as $key => $match) {
                        $link = str_replace($match[5], $sslXoopsUrl, $match[0]);
                        $buf = str_replace($match[0], $link, $buf);
                    }
                }
                // replace css link
                $pattern = '(<link)([^>]*)(href=)([\"\'])' . $replaceLinkPattern . '([\"\'])([^>]*)(>)';
                preg_match_all("/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER);
                if (!empty($matches)) {
                    foreach ($matches as $key => $match) {
                        $link = str_replace($match[5], $sslXoopsUrl, $match[0]);
                        $buf = str_replace($match[0], $link, $buf);
                    }
                }
                // replace js link
                $pattern = '(<script)([^>]*)(src=)([\"\'])' . $replaceLinkPattern . '([\"\'])([^>]*)(>)';
                preg_match_all("/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER);
                if (!empty($matches)) {
                    foreach ($matches as $key => $match) {
                        $link = str_replace($match[5], $sslXoopsUrl, $match[0]);
                        $buf = str_replace($match[0], $link, $buf);
                    }
                }
            }
            return $buf;
        }
    }
}
