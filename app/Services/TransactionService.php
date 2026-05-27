<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\ServiceType;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransactionService
{
    public function store(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $serviceType = ServiceType::findOrFail($data['service_type_id']);
            $isWashBased = $this->isWashBasedService($serviceType->service_name);

            $this->validateTransactionInput($data, $isWashBased);

            $transaction = new Transaction();

            $transaction->fill([
                'customer_id' => $data['customer_id'],
                'service_type_id' => $data['service_type_id'],
                'staff_id' => $data['staff_id'],
                'status_id' => $data['status_id'],
                'weight_kg' => $isWashBased
                    ? (float) $data['weight_kg']
                    : (isset($data['weight_kg']) && $data['weight_kg'] !== '' ? (float) $data['weight_kg'] : null),
                'number_of_loads' => $isWashBased
                    ? null
                    : (int) $data['number_of_loads'],
                'remarks' => $data['remarks'] ?? null,
                'total_amount' => null,
            ]);

            $transaction->save();
            $transaction->refresh();

            $extraTotal = 0;

            foreach ($data['extra_items'] ?? [] as $item) {
                $extraItemId = $item['extra_item_id'] ?? null;
                $quantity = (int) ($item['quantity'] ?? 0);

                if (! $extraItemId || $quantity <= 0) {
                    continue;
                }

                $extraRecord = $transaction->transactionExtraItems()->create([
                    'extra_item_id' => $extraItemId,
                    'quantity' => $quantity,
                    'subtotal' => null,
                ]);

                $extraRecord->refresh();
                $extraTotal += (float) $extraRecord->subtotal;
            }

            $baseTotal = (float) $transaction->total_amount;
            $finalAmount = $baseTotal + $extraTotal;

            $transaction->update([
                'total_amount' => $finalAmount,
            ]);

            Payment::create([
                'transaction_id' => $transaction->transaction_id,
                'payment_amount' => null,
                'payment_method' => null,
                'payment_status' => 'Unpaid',
                'paid_at' => null,
            ]);

            return $transaction->fresh([
                'customer',
                'serviceType',
                'staff',
                'status',
                'transactionExtraItems.extraItem',
                'payment',
            ]);
        });
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            $serviceType = ServiceType::findOrFail($data['service_type_id']);
            $isWashBased = $this->isWashBasedService($serviceType->service_name);

            $this->validateTransactionInput($data, $isWashBased);

            $weightKg = null;
            $numberOfLoads = null;

            if ($isWashBased) {
                $weightKg = (float) $data['weight_kg'];
                $numberOfLoads = (int) ceil($weightKg / 6);
            } else {
                $numberOfLoads = (int) $data['number_of_loads'];
                $weightKg = isset($data['weight_kg']) && $data['weight_kg'] !== ''
                    ? (float) $data['weight_kg']
                    : null;
            }

            $baseAmount = (float) $serviceType->price * $numberOfLoads;

            $transaction->update([
                'customer_id' => $data['customer_id'],
                'service_type_id' => $data['service_type_id'],
                'staff_id' => $data['staff_id'],
                'status_id' => $data['status_id'],
                'weight_kg' => $weightKg,
                'number_of_loads' => $numberOfLoads,
                'remarks' => $data['remarks'] ?? null,
                'total_amount' => $baseAmount,
            ]);

            $transaction->transactionExtraItems()->delete();

            $extraTotal = 0;

            foreach ($data['extra_items'] ?? [] as $item) {
                $extraItemId = $item['extra_item_id'] ?? null;
                $quantity = (int) ($item['quantity'] ?? 0);

                if (! $extraItemId || $quantity <= 0) {
                    continue;
                }

                $extraItem = \App\Models\ExtraItem::findOrFail($extraItemId);
                $subtotal = (float) $extraItem->price * $quantity;

                $transaction->transactionExtraItems()->create([
                    'extra_item_id' => $extraItemId,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $extraTotal += $subtotal;
            }

            $finalAmount = $baseAmount + $extraTotal;

            $transaction->update([
                'total_amount' => $finalAmount,
            ]);

            if ($transaction->payment && $transaction->payment->payment_status === 'Unpaid') {
                $transaction->payment->update([
                    'payment_amount' => $finalAmount,
                    'payment_method' => null,
                    'paid_at' => null,
                ]);
            }

            return $transaction->fresh([
                'customer',
                'serviceType',
                'staff',
                'status',
                'transactionExtraItems.extraItem',
                'payment',
            ]);
        });
    }

    protected function validateTransactionInput(array $data, bool $isWashBased): void
    {
        if ($isWashBased) {
            if (! isset($data['weight_kg']) || $data['weight_kg'] === null || $data['weight_kg'] === '') {
                throw new InvalidArgumentException('Weight is required for wash-based services.');
            }

            if ((float) $data['weight_kg'] <= 0) {
                throw new InvalidArgumentException('Weight must be greater than 0 for wash-based services.');
            }

            return;
        }

        if (! isset($data['number_of_loads']) || $data['number_of_loads'] === null || $data['number_of_loads'] === '') {
            throw new InvalidArgumentException('Number of loads is required for non-wash services.');
        }

        if ((int) $data['number_of_loads'] < 1) {
            throw new InvalidArgumentException('Number of loads must be at least 1.');
        }
    }

    protected function isWashBasedService(string $serviceName): bool
    {
        return str_contains(strtolower($serviceName), 'wash');
    }
}