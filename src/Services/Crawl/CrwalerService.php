<?php

namespace Mfarahani\TCrawl\Services\Crawl;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class CrwalerService
{
    private $httpProxy;

    private $channelName;

    const BASEURL = "https://t.me/";

    public function __construct(string $channelName, string $httpProxy = null)
    {
        $this->httpProxy   = $httpProxy;
        $this->channelName = $channelName;

        $this->connect();
        $this->setChannel($channelName);
    }

    private function connect()
    {
        $this->client = new Client(HttpClient::create(['timeout' => 60, 'proxy' => $this->httpProxy]));
    }

    private function setChannel()
    {
        $url = self::BASEURL . $this->channelName;

        $this->crawler = $this->client->request("GET", $url);

    }

    public function getInfo()
    {
        return (new InformationChannel($this->crawler))->getInfo();
    }

    public function getLatestMessages(bool $formWithTemplate = false, int $fromId = null)
    {
        return (new MessageChannel($this->crawler))->getLatestMessages($formWithTemplate, $this->channelName, $fromId);

    }
    public function getLastMessage(bool $formWithTemplate = false)
    {
        return (new MessageChannel($this->crawler))->getLastMessage($formWithTemplate, $this->channelName);

    }

    public function getLastId()
    {
        return (new MessageChannel($this->crawler))->getLastId();
    }

}
