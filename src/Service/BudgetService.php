<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BudgetService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl.'/api/dashboard/budgets';
    }

    public function getAllBudgetsByclient(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des budgets.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
    public function getAllBudgets(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl."/all");

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des budgets.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getBudgetById(int $id): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/' . $id);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération du budget.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getTotalBudget(): float
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/total');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return (float) $response->getContent();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération du total des budgets.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function updateBudget(int $id, float $valeur): array
    {
        $data = [
            'valeur' => $valeur 
        ];
        try {
            $response = $this->httpClient->request('PUT', $this->apiUrl . '/' . $id, [
                'json' => $data,
            ]);
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la mise à jour du budget.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function deleteBudget(int $id): void
    {
        try {
            $response = $this->httpClient->request('DELETE', $this->apiUrl . '/' . $id);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la suppression du budget.');
            }
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
}