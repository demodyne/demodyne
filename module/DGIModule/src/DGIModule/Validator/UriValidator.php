<?php 
namespace DGIModule\Validator;

use Zend\Validator\Uri as BaseUri;

class UriValidator extends BaseUri
{
    protected $messageTemplates = array(
        self::INVALID => "Invalid type given. String expected",
        self::NOT_URI => "The address does not appear to be a valid URL (should start with 'http(s)://')",
    );
} 