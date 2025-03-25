<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TicketService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }
    public function getAllTicketExpensesValide(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseTicket');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }

            throw new HttpException($response->getStatusCode(), 'Erreur lors de la récupération des dépenses.');
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }

    public function getAllTicketExpenses(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseTicket/tickets');

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
            $response = $this->httpClient->request('GET', $this->apiUrl . '/api/dashboard/depenseTicket/total');

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
            $response = $this->httpClient->request('PUT', $this->apiUrl . '/api/dashboard/depenseTicket/' . $id, [
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
            $response = $this->httpClient->request('DELETE', $this->apiUrl . '/api/dashboard/depenseTicket/ticket/' . $id);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new HttpException($response->getStatusCode(), 'Erreur lors de la suppression du ticket.');
            }
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur serveur : ' . $e->getMessage());
        }
    }
}