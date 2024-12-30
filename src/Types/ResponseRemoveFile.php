<?php
namespace Vitacode\Helpers\Types;

class ResponseRemoveFile {
    public $status;
    public $message;
    public function __construct($status, $message = '')
    {
        $this->status = $status;
        $this->message = $message;
    }
}