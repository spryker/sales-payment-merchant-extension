<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransmissionResponseCollectionTransfer;

/**
 * Implement this plugin to replace the default HTTP-based PSP App transfer endpoint
 * with a direct PSP integration (e.g. Stripe Connect transfers).
 *
 * When registered, SalesPaymentMerchant will delegate payment transmission execution
 * to this plugin instead of sending an HTTP request to the PSP App's /transfers endpoint.
 * All business logic (expense handling, amount calculation, grouping) remains in SalesPaymentMerchant.
 *
 * Items with positive amounts represent forward payouts.
 * Items with negative amounts and a transferId represent reversals of previous payouts.
 */
interface MerchantPayoutTransmissionPluginInterface
{
    /**
     * Specification:
     * - Executes payment transmission for the prepared `PaymentTransmissionItemTransfer` objects.
     * - Items contain pre-calculated amounts, merchant references, order references, and item references.
     * - Forward payouts have positive amounts, reversals have negative amounts with a transferId set.
     * - Returns a `PaymentTransmissionResponseCollectionTransfer` with transmission results.
     * - Each response must include transferId, merchantReference, orderReference, and isSuccessful flag.
     * - SalesPaymentMerchant persists responses to `spy_sales_payment_merchant_payout` DB table after this call.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     */
    public function executePayoutTransmission(
        array $paymentTransmissionItemTransfers,
        OrderTransfer $orderTransfer,
    ): PaymentTransmissionResponseCollectionTransfer;
}
