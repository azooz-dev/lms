<?php

declare(strict_types=1);

namespace App\Checkout;

use App\Models\Payment;

class CheckoutContext
{
    public array $requestData;

    public int $userId;

    public string $paymentType;

    public float $totalAmount = 0;

    public ?Payment $payment = null;

    public array $courses = [];

    public ?string $transactionId = null;

    private bool $failed = false;

    private string $errorMessage = '';

    private string $successMessage = '';

    public function __construct(array $requestData, int $userId, string $paymentType)
    {
        $this->requestData = $requestData;
        $this->userId = $userId;
        $this->paymentType = $paymentType;
    }

    /**
     * Mark the context as failed with an error message
     */
    public function fail(string $message): self
    {
        $this->failed = true;
        $this->errorMessage = $message;

        return $this;
    }

    /**
     * Check if the context has failed
     */
    public function hasFailed(): bool
    {
        return $this->failed;
    }

    /**
     * Get the error message
     */
    public function getError(): string
    {
        return $this->errorMessage;
    }

    /**
     * Set the success message
     */
    public function setSuccessMessage(string $message): self
    {
        $this->successMessage = $message;

        return $this;
    }

    /**
     * Get the success message
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage ?: 'Payment successful.';
    }
}

