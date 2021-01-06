<?php


namespace App\Manager;


use App\Repository\TransactionRepository;

class TransactionManager
{
    private TransactionRepository $transactionRepository;

    public function __construct(
        TransactionRepository $transactionRepository
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @return float
     */
    public function getTotalResult(): float
    {
        $response = $this->transactionRepository->countTotalPricesMinusTaxes();

        return round(floatval($response[0]['result']), 2);
    }
}