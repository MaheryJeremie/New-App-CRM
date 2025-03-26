<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTime;
use DateTimeZone;

class SeuilLimiteService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function getSeuilLimite(): ?array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . '/api/seuil');

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        return null;
    }

    public function getAllSeuilLimite(): ?array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . '/api/seuil/all');

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        return null;
    }

    public function updateSeuilLimite(int $id, float $pourcentage): ?array
    {
        $seuilLimite = [
            'pourcentage' => $pourcentage,
            'dateModif' => (new DateTime('now', new DateTimeZone('+03:00')))->format('Y-m-d\TH:i:s'),
        ];

        $response = $this->httpClient->request('PUT', $this->apiUrl . '/api/seuil/'.$id, [
            'json' => $seuilLimite
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        
        return null;
    }
    public function saveSeuilLimite(float $pourcentage): ?array
    {
        $seuilLimite = [
            'pourcentage' => $pourcentage,
            'dateModif' => (new DateTime('now', new DateTimeZone('+03:00')))->format('Y-m-d\TH:i:s'),
        ];

        $response = $this->httpClient->request('POST', $this->apiUrl . '/api/seuil', [
            'json' => $seuilLimite
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
        
        return null;
    }
}