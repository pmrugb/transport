<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

#[Fillable([
    'user_id',
    'action',
    'auditable_type',
    'auditable_id',
    'auditable_label',
    'ip_address',
    'user_agent',
    'url',
    'old_values',
    'new_values',
    'meta',
])]
class AuditLog extends Model
{
    public const ACTION_LABELS = [
        'payment.status_updated' => 'Payment Status Updated',
        'payment.approved' => 'Payment Approved',
        'payment.held' => 'Payment Put On Hold',
        'payment.rejected' => 'Payment Rejected',
        'payment.bulk_approved' => 'Payments Bulk Approved',
        'role.created' => 'Role Created',
        'role.updated' => 'Role Updated',
        'role.details_updated' => 'Role Details Updated',
        'role.access_updated' => 'Role Access Updated',
        'role.deleted' => 'Role Deleted',
        'recovery.restored' => 'Recovery Restore',
        'user.deleted' => 'User Deleted',
        'user.restored' => 'User Restored',
        'trip.updated' => 'Trip Updated',
        'trip.deleted' => 'Trip Deleted',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'auditable_id' => 'integer',
            'old_values' => 'array',
            'new_values' => 'array',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function recordEvent(
        string $action,
        Request $request,
        ?Model $subject = null,
        array $oldValues = [],
        array $newValues = [],
        array $meta = []
    ): self {
        ['old' => $changedOldValues, 'new' => $changedNewValues] = static::extractChangedValues($oldValues, $newValues);

        return static::create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'auditable_type' => $subject ? $subject::class : null,
            'auditable_id' => $subject?->getKey(),
            'auditable_label' => static::resolveAuditableLabel($subject),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'old_values' => $changedOldValues !== [] ? $changedOldValues : null,
            'new_values' => $changedNewValues !== [] ? $changedNewValues : null,
            'meta' => $meta !== [] ? $meta : null,
        ]);
    }

    public function subjectTypeLabel(): string
    {
        return $this->auditable_type ? class_basename($this->auditable_type) : 'System';
    }

    public function changedOldValues(): array
    {
        return static::extractChangedValues($this->old_values ?? [], $this->new_values ?? [])['old'];
    }

    public function changedNewValues(): array
    {
        return static::extractChangedValues($this->old_values ?? [], $this->new_values ?? [])['new'];
    }

    public function hasValueChanges(): bool
    {
        return $this->changedOldValues() !== [] || $this->changedNewValues() !== [];
    }

    private static function extractChangedValues(array $oldValues, array $newValues): array
    {
        $changedOldValues = [];
        $changedNewValues = [];

        foreach (array_unique([...array_keys($oldValues), ...array_keys($newValues)]) as $key) {
            $oldValue = $oldValues[$key] ?? null;
            $newValue = $newValues[$key] ?? null;

            if (static::valuesAreEquivalent($oldValue, $newValue)) {
                continue;
            }

            if (array_key_exists($key, $oldValues)) {
                $changedOldValues[$key] = $oldValue;
            }

            if (array_key_exists($key, $newValues)) {
                $changedNewValues[$key] = $newValue;
            }
        }

        return [
            'old' => $changedOldValues,
            'new' => $changedNewValues,
        ];
    }

    private static function valuesAreEquivalent(mixed $left, mixed $right): bool
    {
        if (is_array($left) || is_array($right)) {
            return static::normalizeArrayValue($left) === static::normalizeArrayValue($right);
        }

        return $left === $right;
    }

    private static function normalizeArrayValue(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (Arr::isList($value)) {
            return array_map(static fn (mixed $item): mixed => static::normalizeArrayValue($item), $value);
        }

        ksort($value);

        foreach ($value as $key => $item) {
            $value[$key] = static::normalizeArrayValue($item);
        }

        return $value;
    }

    private static function resolveAuditableLabel(?Model $subject): ?string
    {
        if (! $subject) {
            return null;
        }

        return match (true) {
            $subject instanceof Role => $subject->name,
            $subject instanceof TripCost => 'Payment #'.$subject->id,
            $subject instanceof TripDetail => 'Trip #'.$subject->id,
            $subject instanceof User => $subject->name,
            default => class_basename($subject).' #'.$subject->getKey(),
        };
    }
}
