<?php 
// ./e_errorhandler.php in root of ZF2 app
//adapt from http://stackoverflow.com/questions/277224/how-do-i-catch-a-php-fatal-error
define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |
        E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
 
define('DISPLAY_ERRORS', TRUE);
define('ERROR_REPORTING', E_ALL | E_STRICT);
 
register_shutdown_function('shut');
set_error_handler('handler');
 
//catch function
function shut()
{
    $error = error_get_last();
    if ($error && ($error['type'] & E_FATAL)) {
        handler($error['type'], $error['message'], $error['file'], $error['line']);
    }
}
 
function handler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
 
        case E_ERROR: // 1 //
            $typestr = 'E_ERROR'; break;
        case E_WARNING: // 2 //
            $typestr = 'E_WARNING'; break;
        case E_PARSE: // 4 //
            $typestr = 'E_PARSE'; break;
        case E_NOTICE: // 8 //
            $typestr = 'E_NOTICE'; break;
        case E_CORE_ERROR: // 16 //
            $typestr = 'E_CORE_ERROR'; break;
        case E_CORE_WARNING: // 32 //
            $typestr = 'E_CORE_WARNING'; break;
        case E_COMPILE_ERROR: // 64 //
            $typestr = 'E_COMPILE_ERROR'; break;
        case E_CORE_WARNING: // 128 //
            $typestr = 'E_COMPILE_WARNING'; break;
        case E_USER_ERROR: // 256 //
            $typestr = 'E_USER_ERROR'; break;
        case E_USER_WARNING: // 512 //
            $typestr = 'E_USER_WARNING'; break;
        case E_USER_NOTICE: // 1024 //
            $typestr = 'E_USER_NOTICE'; break;
        case E_STRICT: // 2048 //
            $typestr = 'E_STRICT'; break;
        case E_RECOVERABLE_ERROR: // 4096 //
            $typestr = 'E_RECOVERABLE_ERROR'; break;
        case E_DEPRECATED: // 8192 //
            $typestr = 'E_DEPRECATED'; break;
        case E_USER_DEPRECATED: // 16384 //
            $typestr = 'E_USER_DEPRECATED'; break;
    }
     
    $message = " Error PHP in file : ".$errfile." at line : ".$errline."
    with type error : ".$typestr." : ".$errstr." in ".$_SERVER['REQUEST_URI'];
 
    if(!($errno & ERROR_REPORTING)) {
        return;
    }
 
    if (DISPLAY_ERRORS) {
        //logging...
        $logger = new Zend\Log\Logger;
         
        //stream writer         
        $writerStream = new Zend\Log\Writer\Stream(__DIR__.'/data/logs/'.date('Ymd').'-log.txt');
        //mail writer
        $mail = new Zend\Mail\Message();
        $mail->setFrom('bug-hunter@demodyne.org', 'Bug hunter');
        $mail->addTo('debug@demodyne.org', 'Debug Demodyne');
        $transport = new Zend\Mail\Transport\Sendmail(); 
        $writerMail = new Zend\Log\Writer\Mail($mail, $transport);
        $writerMail->setSubjectPrependText("PHP Error :  $typestr : $errstr ");
         
        $logger->addWriter($writerStream);
        $logger->addWriter($writerMail);
       
        //log it!
        $logger->crit($message);
         
        //show user that's the site is down right now
        include __DIR__.'/module/DGIModule/view/error/e_handler.phtml';
        die;
    }
}