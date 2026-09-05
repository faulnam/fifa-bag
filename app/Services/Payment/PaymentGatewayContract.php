<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGatewayContract
{
    /**
     * Create a payment transaction for the given order and return payment details/tokens.
     *
     * @param Order $order
     * @return array
     */
    public function createTransaction(Order $order): array;

    /**
     * Verify the webhook notification signature.
     *
     * @param array $payload
     * @return bool
     */
    public function verifySignature(array $payload): bool;

    /**
     * Parse notification webhook payload into standardized format.
     *
     * @param array $payload
     * @return array
     */
    public function parseNotification(array $payload): array;
}
