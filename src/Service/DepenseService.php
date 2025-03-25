<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DepenseService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function getAllExpenses(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/depenses');

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
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/depenses/total');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return (float) $response->getContent();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération du total des dépenses.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function createExpense(array $data): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->apiUrl . '/api/depenses', [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la création de la dépense.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function updateExpense(int $id, array $data): array
    {
        try {
            $response = $this->httpClient->request('PUT', $this->apiUrl . '/api/depenses/' . $id, [
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
            $response = $this->httpClient->request('DELETE', $this->apiUrl . '/api/depenses/' . $id);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la suppression de la dépense.');
            }
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getExpensesByLeadId(int $leadId): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/depenses/lead/' . $leadId);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des dépenses pour le lead.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getExpensesByTicketId(int $ticketId): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/depenses/ticket/' . $ticketId);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des dépenses pour le ticket.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
}