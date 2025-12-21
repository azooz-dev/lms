<?php

declare(strict_types=1);

namespace App\Payment;

use App\Payment\Contracts\PaymentProcessorInterface;
use App\Payment\Processors\CashPaymentProcessor;
use App\Payment\Processors\StripePaymentProcessor;
use InvalidArgumentException;

class PaymentProcessorFactory
{
    /**
     * @var PaymentProcessorInterface[]
     */
    private array $processors = [];

    public function __construct()
    {
        // Register available processors
        $this->register(new StripePaymentProcessor());
        $this->register(new CashPaymentProcessor());
    }

    /**
     * Register a payment processor
     */
    public function register(PaymentProcessorInterface $processor): void
    {
        $this->processors[$processor->getName()] = $processor;
    }

    /**
     * Get a processor that supports the given payment type
     *
     * @throws InvalidArgumentException
     */
    public function make(string $type): PaymentProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supports($type)) {
                return $processor;
            }
        }

        throw new InvalidArgumentException("Unsupported payment type: {$type}");
    }

    /**
     * Get all available payment types
     *
     * @return string[]
     */
    public function getAvailableTypes(): array
    {
        return array_keys($this->processors);
    }
}

