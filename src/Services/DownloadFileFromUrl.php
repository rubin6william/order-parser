<?php

namespace App\Services;

use League\Flysystem\Filesystem;
use Symfony\Component\HttpClient\CurlHttpClient;

class DownloadFileFromUrl
{
    protected CurlHttpClient $client;

    public function __construct(public string $customerOrderFilePath)
    {
        $this->client = new CurlHttpClient();
    }

    public function download(string $url): string
    {
        $response = $this->client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Failed to read the file from : ' . $url);
        }

        $outputFilePath = $this->customerOrderFilePath . '/order.jsonl';

        $fileHandler = fopen($outputFilePath, 'w');

        foreach($this->client->stream($response) as $chunk) {
            fwrite($fileHandler, $chunk->getContent());
        }

        return $outputFilePath;
    }
}