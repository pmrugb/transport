<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

#[Fillable([
    'user_id',
    'event_type',
    'email_attempted',
    'attempts',
    'request_count',
    'ip_address',
    'user_agent',
    'url',
    'risk_level',
    'meta',
])]
class SecurityLog extends Model
{
    public const EVENT_LABELS = [
        'login_success' => 'login_success',
        'failed_login' => 'failed_login',
        'captcha_failed' => 'captcha_failed',
        'logout' => 'logout',
    ];

    public const RISK_LABELS = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'attempts' => 'integer',
            'request_count' => 'integer',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function recordEvent(
        string $eventType,
        Request $request,
        ?User $user = null,
        ?string $emailAttempted = null,
        array $meta = []
    ): self {
        $ipAddress = $request->ip();
        $todayQuery = static::query()
            ->when($ipAddress, fn ($query, $ip) => $query->where('ip_address', $ip))
            ->whereDate('created_at', today());

        $attempts = null;

        if (in_array($eventType, ['failed_login', 'captcha_failed'], true)) {
            $attempts = (clone $todayQuery)
                ->when($emailAttempted, fn ($query, $email) => $query->where('email_attempted', $email))
                ->whereIn('event_type', ['failed_login', 'captcha_failed'])
                ->count() + 1;
        }

        $requestCount = (clone $todayQuery)->count() + 1;

        return static::create([
            'user_id' => $user?->id,
            'event_type' => $eventType,
            'email_attempted' => $emailAttempted,
            'attempts' => $attempts,
            'request_count' => $requestCount,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'risk_level' => $attempts >= 5 ? 'high' : ($attempts >= 3 ? 'medium' : 'low'),
            'meta' => $meta ?: null,
        ]);
    }
}
