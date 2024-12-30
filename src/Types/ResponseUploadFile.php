<?php
namespace Vitacode\Helpers\Types;

class ResponseUploadFile {
    public $status;
    public $data;
    public $message;
    public $path;
    public $url;
    public function __construct($status, $data = [], $message = '', $path = '', $url = '')
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
        $this->path = $path;
        $this->url = $url;
    }
}