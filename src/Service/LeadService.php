<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LeadService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function getAllLeadExpenses(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseLead/leads');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des dépenses.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
    public function getAllLeadExpensesValide(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseLead');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des dépenses.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getTotalExpenses(): float
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseLead/total');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return (float) $response->getContent();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération du total des dépenses.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function updateExpense(int $id, array $data): array
    {
        try {
            $response = $this->httpClient->request('PUT', $this->apiUrl . '/api/dashboard/depenseLead/' . $id, [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la mise à jour de la dépense.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function deleteExpense(int $id): void
    {
        try {
            $response = $this->httpClient->request('DELETE', $this->apiUrl . '/api/dashboard/depenseLead/lead/' . $id);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la suppression du lead.');
            }
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
}