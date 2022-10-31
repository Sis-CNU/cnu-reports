<?php

declare(strict_types=1);

namespace App;

/**
 * [Description Header]
 */
class Header
{
   /**
    * CACHE_CONTROL
    *
    * @var string
    */
   public const CACHE_CONTROL = "private, max-age=86400";

   /**
    * STRICT_TRANSPORT_SECURITY
    *
    * @var string
    */
   public const STRICT_TRANSPORT_SECURITY = "max-age=31536000; includeSubdomains; preload";

   /**
    * XSS_PROTECTION
    *
    * @var string
    */
   public const XSS_PROTECTION = "1; mode=block";

   /**
    * X_FRAME_OPTIONS
    *
    * @var string
    */
   public const X_FRAME_OPTIONS = "deny";

   /**
    * X_CONTENT_TYPE_OPTIONS
    *
    * @var string
    */
   public const X_CONTENT_TYPE_OPTIONS = "nosniff";

   /**
    * X_PERMITTED_CROSS_DOMAIN_POLICIES
    *
    * @var string
    */
   public const X_PERMITTED_CROSS_DOMAIN_POLICIES = "none";

   /**
    * REFERRER_POLICY
    *
    * @var string
    */
   public const REFERRER_POLICY = "origin-when-cross-origin, strict-origin-when-cross-origin";

   /**
    * FEATURE_POLICY
    *
    * @var string
    */
   public const FEATURE_POLICY = "vibrate 'none'; geolocation 'none'; camera: 'none'; payment: 'none'; microphone: 'none'";

   /**
    * CONTENT_SECURITY_POLICY
    *
    * @var string
    */
   public const CONTENT_SECURITY_POLICY =  "script-src 'self'; object-src 'self'";

   /**
    * CONTENT_SECURITY_POLICY
    *
    * @var string
    */
   public const CONTENT_SECURITY_POLICY_API =  "default-src 'none'";

   /**
    * CONTENT_TYPE_API
    *
    * @var string
    */
   public const CONTENT_TYPE_API =  "application/json; charset=utf-8";

   /**
    * ERROR_PAGE_DIRECTORY
    *
    * @var string
    */
   public const ERROR_PAGE_DIRECTORY = "../view/templates/error-page.php";

   /**
    * [Description for getHttpProtocol]
    *
    * @return bool
    * 
    */
   public static function getHttpProtocol(): string
   {
      return isset($_SERVER['SERVER_PROTOCOL']) ?
         $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
   }

   /**
    * [Description for getHttpStatus]
    *
    * @param mixed $code
    * 
    * @return string
    * 
    */
   public static function getHttpStatus($code): string
   {
      $http_protocol = self::getHttpProtocol();

      $http_status =  [
         100 => $http_protocol . " 100 Continue",
         101 => $http_protocol . " 101 Switching Protocols",
         200 => $http_protocol . " 200 OK",
         201 => $http_protocol . " 201 Created",
         202 => $http_protocol . " 202 Accepted",
         203 => $http_protocol . " 203 Non-Authoritative Information",
         204 => $http_protocol . " 204 No Content",
         205 => $http_protocol . " 205 Reset Content",
         206 => $http_protocol . " 206 Partial Content",
         300 => $http_protocol . " 300 Multiple Choices",
         301 => $http_protocol . " 301 Moved Permanently",
         302 => $http_protocol . " 302 Found",
         303 => $http_protocol . " 303 See Other",
         304 => $http_protocol . " 304 Not Modified",
         305 => $http_protocol . " 305 Use Proxy",
         307 => $http_protocol . " 307 Temporary Redirect",
         400 => $http_protocol . " 400 Bad Request",
         401 => $http_protocol . " 401 Unauthorized",
         402 => $http_protocol . " 402 Payment Required",
         403 => $http_protocol . " 403 Forbidden",
         404 => $http_protocol . " 404 Not Found",
         405 => $http_protocol . " 405 Method Not Allowed",
         406 => $http_protocol . " 406 Not Acceptable",
         407 => $http_protocol . " 407 Proxy Authentication Required",
         408 => $http_protocol . " 408 Request Time-out",
         409 => $http_protocol . " 409 Conflict",
         410 => $http_protocol . " 410 Gone",
         411 => $http_protocol . " 411 Length Required",
         412 => $http_protocol . " 412 Precondition Failed",
         413 => $http_protocol . " 413 Request Entity Too Large",
         414 => $http_protocol . " 414 Request-URI Too Large",
         415 => $http_protocol . " 415 Unsupported Media Type",
         416 => $http_protocol . " 416 Requested Range Not Satisfiable",
         417 => $http_protocol . " 417 Expectation Failed",
         500 => $http_protocol . " 500 Internal Server Error",
         501 => $http_protocol . " 501 Not Implemented",
         502 => $http_protocol . " 502 Bad Gateway",
         503 => $http_protocol . " 503 Service Unavailable",
         504 => $http_protocol . " 504 Gateway Time-out",
         505 => $http_protocol . " 505 HTTP Version Not Supported",
      ];

      return $http_status[$code];
   }

   /**
    * [Description for headerResponse]
    *
    * @param int $code
    * @param bool $replace
    * 
    * @return string|false
    * 
    */
   public static function headerResponse(int $code, bool $replace = true)
   {
      http_response_code($code);
      self::addSecurityHeaders($code);
      header(self::getHttpStatus($code), $replace, $code);

      if (file_exists(self::ERROR_PAGE_DIRECTORY) && $code >= 300 && $code <= 499) {
         $data = ["errorMessage" => self::getHttpStatus($code)];
         extract($data);
         ob_start();
         require_once(self::ERROR_PAGE_DIRECTORY);
         flush();
         echo ob_get_clean();
      }
   }

   /**
    * [Description for apiHeaderResponse]
    *
    * @param int $code
    * @param bool $replace
    * 
    * @return void
    * 
    */
   public static function apiHeaderResponse(int $code, bool $replace = true)
   {
      http_response_code($code);
      self::addApiSecurityHeaders($code);
      header(self::getHttpStatus($code), $replace, $code);
   }

   /**
    * [Description for addApiSecurityHeaders]
    *
    * @param mixed $code
    * 
    * @return void
    * 
    */
   private static function addApiSecurityHeaders($code)
   {
      header("Status: " . Header::getHttpStatus($code));
      header("Cache-Control: " . self::CACHE_CONTROL);
      header("Content-type: " . self::CONTENT_TYPE_API);
      header("X-XSS-Protection: " . self::XSS_PROTECTION);
      header("Feature-Policy: " . self::FEATURE_POLICY);
      header("Referrer-Policy: " . self::REFERRER_POLICY);
      header("X-Frame-Options: " . self::X_FRAME_OPTIONS);
      header("X-Content-Type-Options: " . self::X_CONTENT_TYPE_OPTIONS);
      header("Content-Security-Policy: " . self::CONTENT_SECURITY_POLICY_API);
      header("Strict-Transport-Security: " . self::STRICT_TRANSPORT_SECURITY);
      header("X-Permitted-Cross-Domain-Policies: " . self::X_PERMITTED_CROSS_DOMAIN_POLICIES);
   }

   /**
    * [Description for addSecurityHeaders]
    *
    * @return void
    * 
    */
   private static function addSecurityHeaders(int $code)
   {
      header("Status: " . Header::getHttpStatus($code));
      header("Feature-Policy: " . self::FEATURE_POLICY);
      header("X-Frame-Options: " . self::X_FRAME_OPTIONS);
      header("X-XSS-Protection: " . self::XSS_PROTECTION);
      header("X-Content-Type-Options: " . self::X_CONTENT_TYPE_OPTIONS);
      header("Content-Security-Policy: " . self::CONTENT_SECURITY_POLICY);
      header("Strict-Transport-Security: " . self::STRICT_TRANSPORT_SECURITY);
      header("X-Permitted-Cross-Domain-Policies: " . self::X_PERMITTED_CROSS_DOMAIN_POLICIES);
   }

   /**
    * [Description for redirectHeaders]
    *
    * @param string $url
    * 
    * @return void
    * 
    */
   public static function redirectHeaders(string $url)
   {
      if (self::urlExist($url)) {         
         header("Location: $url");
         self::addSecurityHeaders(200);
      }
   }

   /**
    * [Description for urlExist]
    *
    * @param string $url
    * 
    * @return bool
    * 
    */
   public static function urlExist(string $url): bool
   {
      return curl_init($url) !== false;
   }
}
