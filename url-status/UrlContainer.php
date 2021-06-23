<?php


namespace makerus\urlstatus;

class UrlContainer
{
    protected $urls;

    public function __construct()
    {
        $this->urls = array();
    }

    public function fromArray(array $urls)
    {
        $this->urls = $urls;
    }

    public function clear()
    {
        $this->urls = array();
    }

    public function diff(array $urls): array
    {
        return array_diff($this->urls, $urls);
    }

    public function get()
    {
        return $this->urls;
    }
}