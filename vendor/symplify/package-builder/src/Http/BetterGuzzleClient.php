<?php

declare (strict_types=1);
namespace RectorPrefix20210106\Symplify\PackageBuilder\Http;

use RectorPrefix20210106\GuzzleHttp\ClientInterface;
use RectorPrefix20210106\GuzzleHttp\Exception\BadResponseException;
use RectorPrefix20210106\GuzzleHttp\Psr7\Request;
use RectorPrefix20210106\Nette\Utils\Json;
use RectorPrefix20210106\Nette\Utils\JsonException;
use RectorPrefix20210106\Psr\Http\Message\ResponseInterface;
final class BetterGuzzleClient
{
    /**
     * @var ClientInterface
     */
    private $client;
    public function __construct(\RectorPrefix20210106\GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * @api
     * @return mixed[]|mixed|void
     */
    public function requestToJson(string $url) : array
    {
        $request = new \RectorPrefix20210106\GuzzleHttp\Psr7\Request('GET', $url);
        $response = $this->client->send($request);
        if (!$this->isSuccessCode($response)) {
            throw \RectorPrefix20210106\GuzzleHttp\Exception\BadResponseException::create($request, $response);
        }
        $content = (string) $response->getBody();
        if ($content === '') {
            return [];
        }
        try {
            return \RectorPrefix20210106\Nette\Utils\Json::decode($content, \RectorPrefix20210106\Nette\Utils\Json::FORCE_ARRAY);
        } catch (\RectorPrefix20210106\Nette\Utils\JsonException $jsonException) {
            throw new \RectorPrefix20210106\Nette\Utils\JsonException('Syntax error while decoding:' . $content, $jsonException->getLine(), $jsonException);
        }
    }
    private function isSuccessCode(\RectorPrefix20210106\Psr\Http\Message\ResponseInterface $response) : bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }
}
