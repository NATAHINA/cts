<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = '';
    public string $fromName  = '';
    public string $recipients = '';

    /*
    |--------------------------------------------------------------------
    | Protocole d'envoi
    |--------------------------------------------------------------------
    | mail, sendmail, ou smtp
    */
    public string $protocol = 'smtp';

    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int    $SMTPPort = ;
    public string $SMTPCrypto = 'tls';
    public bool   $SMTPKeepAlive = false;
    public int    $SMTPTimeout = 5;

    public bool   $wordWrap = true;
    public int    $wrapChars = 76;
    public string $mailType = 'html';
    public string $charset  = 'UTF-8';

    public bool   $validate = true;
    public int    $priority = 3;
    public string $CRLF   = "\r\n";
    public string $newline = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN = false;
}
