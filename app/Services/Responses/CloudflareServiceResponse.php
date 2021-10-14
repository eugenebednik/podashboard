<?php

namespace App\Services\Responses;

use Illuminate\Http\Response;

class CloudflareServiceResponse
{
    protected bool $success = false;

    protected int $code = Response::HTTP_OK;

    protected string $message = '';

    protected mixed $payload = null;

    public function getSuccess() : bool
    {
        return $this->success;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getPayload() : mixed
    {
        return $this->payload;
    }

    public function setSuccess(bool $success) : self
    {
        $this->success = $success;
        return $this;
    }

    public function setCode(int $code) : self
    {
        $this->code = $code;
        return $this;
    }

    public function setMessage(string $message) : self
    {
        $this->message = $message;
        return $this;
    }

    public function setPayload(mixed $payload) : self
    {
        $this->payload = $payload;
        return $this;
    }
}