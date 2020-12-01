<?php
namespace App\Models;
use stdClass;

class JSend
{
    public $status;
    public $data;
    public $message;
    public $code;

    public function __construct($status, $data = null, $message = null, $code = null)
    {
        $this->status = $status;
        $this->data = new stdClass();
        $this->message = $message;
        $this->code = $code;
    }
}
?>