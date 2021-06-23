<?php


namespace makerus\urlstatus;


class RequestStatus
{
    protected $timeout;
    protected $maxConnection;

    public function __construct(int $timeout = 5, int $maxConnection = 10)
    {
        $this->timeout = $timeout;
        $this->maxConnection = $maxConnection;
    }

    public function getStatus($url): int
    {
        $curl = $this->configure($url);
        $this->execute($curl);

        return curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    }

    private function configure($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_MAXCONNECTS, $this->maxConnection);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return $curl;
    }

    private function execute($curl): string
    {
        return curl_exec($curl);
    }


}