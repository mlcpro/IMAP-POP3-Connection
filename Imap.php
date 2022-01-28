<?php

/**
 * Test the valid connections of an electronic mail server, 
 * in case of failure the most common errors are managed.
 * 
 * @author Mlc github.com/mlcpro
 */
class Imap
{
  /**
   * The default connection protocol
   * 
   * @var string
   */
  private $protocol = ':993/imap/ssl/novalidate-cert';

  /**
   * The most popular protocols according to the php doc.
   * 
   * src: php.net/manual/fr/function.imap-open.php
   * 
   * @var array
   */
  private $protocols = [
    ':143/imap/notls',
    ':995/pop3/ssl/novalidate-cert',
    ':110/pop3/notls',
  ];

  /**
   * The most common errors.
   * 
   * @var array
   */
  private const ERROR = [
    'No such host as',
    'Can not authenticate to',
    'Connection failed to',
    'Can\'t connect to',
  ];

  private $response = [];

  /**
   * @var string|array
   */
  private $hosts;

  /**
   * @var string
   */
  private $username;

  /**
   * @var string
   */
  private $password;

  public function __construct($hosts, $username, $password)
  {
    $this->hosts = $hosts;
    $this->username = $username;
    $this->password = $password;

    if (is_array($this->hosts)) {
      foreach ($this->hosts as $this->key => $this->host) {
       $this->connect();
      }
    } else {
      $this->host = $this->hosts;
      $this->connect();
    }
  }
  
  private function connect()
  {
    echo $this->host . $this->protocol . "\n";
    error_reporting('E_ALL & ~E_NOTICE');
    $this->connection = imap_open('{' .  $this->host . $this->protocol . '}', $this->username, $this->password);
    if (!preg_match('/Resource\sid\s.+/', (string) $this->connection)) {
      return $this->errorManagement(imap_last_error());
    }

    $this->response['status'] = 'Success';
    $this->response['message'] = 'Connected';
    $this->response['mailbox'] = $this->host;
    imap_close($this->connection);
    exit;
  }


  private function errorManagement($e)
  {
    $this->response['status'] = 'Error';
    if (strpos($e, self::ERROR[0]) !== false) $e = 1;
    if (strpos($e, self::ERROR[1]) !== false) $e = 2;
    if (strpos($e, self::ERROR[2]) !== false || strpos($e, self::ERROR[3]) !== false) $e = 3;

    switch ($e) {
      case 1:
        if($this->key === array_key_last($this->hosts)) {
          $this->response['message'] = self::ERROR[0];
          exit;
        }
        break;
      case 2:
        $this->response['message'] = self::ERROR[1];
        exit;
        break;
      case 3:
        imap_close($this->connection);
        $this->protocolTesting();
        break;
      default:
        $this->response['message'] = self::ERROR[4];
        exit;
    }
  }

  private function protocolTesting()
  {
    $host = explode(':', $this->host)[0];
    foreach ($this->protocols as $key => $protocol) {
      $this->host = $host;
      $this->protocol = $protocol;
      unset($this->protocols[$key]);
      $this->connect();
    }
  }

  public function __destruct()
  {
    echo json_encode($this->response);
  }
}
