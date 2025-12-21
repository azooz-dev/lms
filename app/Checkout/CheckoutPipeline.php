<?php

declare(strict_types=1);

namespace App\Checkout;

class CheckoutPipeline
{
    /**
     * @var callable[]
     */
    private array $pipes = [];

    /**
     * Add a pipe to the pipeline
     */
    public function pipe(callable $pipe): self
    {
        $this->pipes[] = $pipe;

        return $this;
    }

    /**
     * Process the context through all pipes
     * Stops processing if any pipe marks the context as failed
     */
    public function process(CheckoutContext $context): CheckoutContext
    {
        foreach ($this->pipes as $pipe) {
            $context = $pipe($context);

            if ($context->hasFailed()) {
                break;
            }
        }

        return $context;
    }
}

