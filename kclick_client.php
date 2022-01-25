<?php
/**
Usage:
    require_once 'kclick_client.php';
    $client = new KClickClient('http://geogramma.it/test', 'test');
    $client->sendUtmLabels(); # send only utm labels
    $client->sendAllParams(); # send all params
    $client
        ->keyword('[KEYWORD]')
        ->execute();          # use executeAndBreak() to break the page execution if there is redirect or some output

*/
class KClickClient
{
    private $_token;

    const UNIQUENESS_COOKIE = 'uniqueness_cookie';
    /**
     * @var KHttpClient
     */
    private $_httpClient;
    private $_debug = false;
    private $_site;
    private $_params = array();
    private $_log = array();
    private $_excludeParams = array('api_key', 'token', 'language', 'ua', 'ip', 'referrer', 'uniqueness_cookie');
    private $_result;

    const VERSION = 2;
    const ERROR = '[KTrafficClient] Something is wrong. Enable debug mode to see the reason.';

    public function __construct($site, $token)
    {
        $this->site($site);
        $this->token($token);
        $this->version(self::VERSION);
        $this->fillParams();
    }

    public function fillParams()
    {
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        $this->setHttpClient(new KHttpClient())
            ->ip($this->_findIp())
            ->ua(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null)
            ->language((isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : ''))
            ->seReferrer($referrer)
            ->referrer($referrer)
            ->setUniquenessCookie($this->_getUniquenessCookie())
        ;
    }

    public function currentPageAsReferrer()
    {
        $this->referrer($this->_getCurrentPage());
        return $this;
    }

    public function debug($state = true)
    {
        $this->_debug = $state;
        return $this;
    }

    public function seReferrer($seReferrer)
    {
        $this->_params['se_referrer'] = $seReferrer;
        return $this;
    }

    public function referrer($referrer)
    {
        $this->_params['referrer'] = $referrer;
        return $this;
    }

    public function setHttpClient($httpClient)
    {
        $this->_httpClient = $httpClient;
        return $this;
    }

    public function setUniquenessCookie($value)
    {
        $this->_params[self::UNIQUENESS_COOKIE] = $value;
        return $this;
    }

    public function site($name)
    {
        $this->_site = $name;
    }

    public function token($token)
    {
        $this->_params['token'] = $token;
        return $this;
    }

    public function version($version)
    {
        $this->_params['version'] = $version;
        return $this;
    }

    public function ua($ua)
    {
        $this->_params['ua'] = $ua;
        return $this;
    }

    public function language($language)
    {
        $this->_params['language'] = $language;
        return $this;
    }

    public function keyword($keyword)
    {
        $this->_params['keyword'] = $keyword;
        return $this;
    }

    public function ip($ip)
    {
        $this->_params['ip'] = $ip;
        return $this;
    }

    public function sendUtmLabels()
    {
        foreach ($_GET as $name => $value) {
            if (strstr($name, 'utm_')) {
                $this->_params[$name] = $value;
            }
        }
    }

    public function sendAllParams()
    {
        foreach ($_GET as $name => $value) {
            if (empty($this->_params[$name]) && !in_array($name, $this->_excludeParams)) {
                $this->_params[$name] = $value;
            }
        }
    }

    public function saveUniquenessCookie($value)
    {
        if (!headers_sent()) {
            setcookie($this->getCookieName(), $value, $this->_getCookiesExpireTimestamp(), '/', $this->_getCookieHost());
        }
        $_COOKIE[$this->getCookieName()] = $value;
    }

    public function param($name, $value)
    {
        if (!in_array($name, $this->_excludeParams)) {
            $this->_params[$name] = $value;
        }
        return $this;
    }

    public function params($value)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                parse_str($value, $result);
                foreach ($result as $name => $value) {
                    $this->param($name, $value);
                }
            }
        }

        return $this;
    }

    public function reset()
    {
        $this->_result = null;
    }

    public function performRequest()
    {
        if ($this->_result) {
            return $this->_result;
        }
        $request = $this->_buildRequestUrl();
        $this->_log[] = 'Request: ' . $request;
        try {
            $result = $this->_httpClient->request($request);
            $this->_log[] = 'Response: ' . $result;
        } catch (KTrafficClientError $e) {
            if ($this->_debug) {
                throw $e;
            } else {
                return self::ERROR;
            }
        }
        $this->_result = json_decode($result);
        return $this->_result;
    }

    public function execute($break = false, $print = true)
    {
        $content = $this->getContent();

        if ($print) {
            $this->updateCookies();
            $this->sendHeaders();
            echo $content;
        } else {
            return $content;
        }

        if ($break && !empty($content)) {
            exit;
        }
    }

    public function getContent()
    {
        $result = $this->performRequest();
        $content = '';
        if (!empty($result)) {
            if (!empty($result->error)) {
                $content .=  $result->error;
            }

            if (!empty($result->body)) {
                $content .= $result->body;
            }
        }

        if ($this->_debug) {
            $content .= $this->showLog();
        }
        return $content;
    }

    public function showLog($separator = '<br />')
    {
        $this->performRequest();
        return implode($separator, $this->_log);
    }

    public function getCookieName()
    {
        return hash('sha1', $this->_site);
    }

    public function executeAndBreak()
    {
        $this->execute(true);
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function updateCookies()
    {
        $result = $this->performRequest();

        if (!empty($result->info) && !empty($result->info->sub_id)) {
            $startSession =  (!function_exists('session_status') || !session_status());
            if ($startSession && !headers_sent()) {
                @session_start();
            }
            $_SESSION['sub_id'] = $result->info->sub_id;
            $_SESSION['subid'] = $result->info->sub_id;
        }

        if (!empty($result->uniqueness_cookie)) {
            $this->saveUniquenessCookie($result->uniqueness_cookie);
        }
    }

    public function sendHeaders()
    {
        $result = $this->performRequest();

        if (!empty($result->status)) {
            http_response_code($result->status);
        }

        if (!empty($result->headers)) {
            foreach ($result->headers as $header) {
                if (!headers_sent()) {
                    header($header);
                }
            }
        }

        if (!empty($result->contentType)) {
            $header = 'Content-Type: ' . $result->contentType;
            if (!headers_sent()) {
                header($header);
            }
        }
    }

    // @deprecated
    public function updateHeaders()
    {
        $this->sendHeaders();
    }

    public function getOffer($params = array())
    {
        $result = $this->performRequest();
        if (empty($result->info->token)) {
            $this->_log[] = 'Campaign hasn\'t returned offer';
            return 'no_offer';
        }
        $params['_lp'] = 1;
        $params['_token'] = $result->info->token;
        return $this->_buildOfferUrl($params);
    }

    public function getSubId()
    {
        $result = $this->performRequest();
        if (empty($result->info->sub_id)) {
            $this->_log[] = 'Campaign hasn\'t returned sub_id';
            return 'no_subid';
        }
        return $result->info->sub_id;
    }

    public function isBot()
    {
        $this->param('info', true);
        $result = $this->performRequest();
        if (isset($result->info)) {
            return isset($result->info->is_bot) ? $result->info->is_bot : false;
        }
    }

    public function isUnique($level = 'campaign')
    {
        $this->param('info', true);
        $result = $this->performRequest();
        if (isset($result->info) && $result->info->uniqueness) {
            return isset($result->info->uniqueness->$level) ? $result->info->uniqueness->$level : false;
        }
    }

    public function getBody()
    {
        $result = $this->performRequest();
        return $result->body;
    }

    public function getHeaders()
    {
        $result = $this->performRequest();
        return $result->headers;
    }

    private function _buildOfferUrl($params = array())
    {
        $request = parse_url($this->_site);
        $lastChar = substr($request['path'], -1);
        if ($lastChar != '/' && $lastChar != '\\') {
            $path = str_replace(basename($request['path']), '', $request['path']);
        } else {
            $path = $request['path'];
        }
        $path = ltrim($path, "\\\/");
        $params = http_build_query($params);
        return "{$request['scheme']}://{$request['host']}/{$path}?{$params}";
    }


    private function _getCurrentPage()
    {
        if ((isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']  == 443) || !empty($_SERVER['HTTPS'])) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
        return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private function _buildRequestUrl()
    {
        $this->param('info', true);
        $request = parse_url($this->_site);
        $params = http_build_query($this->getParams());
        return "{$request['scheme']}://{$request['host']}/{$request['path']}?{$params}";
    }


    private function _findIp()
    {
        $ip = null;
        $headers = array('HTTP_X_FORWARDED_FOR', 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'REMOTE_ADDR');
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $tmp = explode(',', $_SERVER[$header]);
                $ip = trim($tmp[0]);
                break;
            }
        }
        if (strstr($ip, ',')) {
            $tmp = explode(',', $ip);
            if (stristr($_SERVER['HTTP_USER_AGENT'], 'mini')) {
                $ip = trim($tmp[count($tmp) - 2]);
            } else {
                $ip = trim($tmp[0]);
            }
        }

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    private function _getUniquenessCookie()
    {
        return !empty($_COOKIE[$this->getCookieName()]) ? $_COOKIE[$this->getCookieName()] : '';
    }

    private function _getCookiesExpireTimestamp()
    {
        return time() + 60 * 60 * 24 * 31;
    }

    private function _getCookieHost()
    {
        if (isset($_SERVER['HTTP_HOST']) && substr_count($_SERVER['HTTP_HOST'], '.') < 3) {
            $host = '.' . str_replace('www.', '', $_SERVER['HTTP_HOST']);
        } else {
            $host = null;
        }
        return $host;
    }
}

class KHttpClient
{
    const UA = 'KHttpClient';

    public function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, self::UA);
        $result = curl_exec($ch);
        if (curl_error($ch)) {
            throw new KTrafficClientError(curl_error($ch));
        }

        if (empty($result)) {
            throw new KTrafficClientError('Empty response');
        }
        return $result;
    }
}

class KTrafficClientError extends \Exception {}
