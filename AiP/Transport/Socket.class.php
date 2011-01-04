<?php
namespace MFS\AppServer\Transport;

class Socket extends BaseTransport
{
    protected $socket = null;

    protected $connections = array();
    protected $connections_count = 0;

    protected $in_loop = false;

    public function loop()
    {
        $this->in_loop = true;
        while ($this->in_loop) {
            $conn = stream_socket_accept($this->socket, -1);

            if (false === $conn)
                return;

            call_user_func($this->callback, $conn);
        }
    }

    public function unloop()
    {
        $this->in_loop = false;
    }

    protected function addSocket($addr)
    {
        $errno = 0;
        $errstr = '';
        $this->socket = stream_socket_server($addr, $errno, $errstr);

        if (false == $this->socket) {
            throw new RuntimeException("Can't create socket(".$errno."): ".$errstr);
        }
    }
}