<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'district_id', 'division_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const NATCO_EMAIL = 'natco@pmrugb.gov.pk';
    public const NATCO_ADMIN_EMAIL = 'natcoadmin@pmrugb.gov.pk';
    public const NATCO_ADMIN_EMAIL_ALIASES = [
        self::NATCO_ADMIN_EMAIL,
        'jalal@pmrugb.gov.pk',
        'ehsan@pmrugb.gov.pk',
    ];
    public const NATCO_DEPARTMENT_EMAIL_ALIASES = [
        self::NATCO_EMAIL,
        ...self::NATCO_ADMIN_EMAIL_ALIASES,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'district_id' => 'integer',
            'division_id' => 'integer',
        ];
    }

    public function isSuperadmin(): bool
    {
        return strtolower((string) $this->role) === 'super_admin'
            || strtolower((string) $this->role) === 'superadmin'
            || strtolower((string) $this->email) === 'superadmin@pmrugb.gov.pk';
    }

    public function hasPaymentsOnlySidebar(): bool
    {
        return $this->isNatcoDepartmentUser();
    }

    public function canManagePayments(): bool
    {
        return $this->isSuperadmin() || $this->isNatcoDepartmentUser();
    }

    public function canAccessPaymentsModule(): bool
    {
        return ! $this->isNatcoAdminUser();
    }

    public function canSeePaymentsNav(): bool
    {
        return $this->canAccessPaymentsModule();
    }

    public function isNatcoDepartmentUser(): bool
    {
        return in_array(strtolower((string) $this->email), self::NATCO_DEPARTMENT_EMAIL_ALIASES, true);
    }

    public function isNatcoAdminUser(): bool
    {
        return in_array(strtolower((string) $this->email), self::NATCO_ADMIN_EMAIL_ALIASES, true);
    }

    public function canViewTripsModule(): bool
    {
        return $this->isSuperadmin() || $this->isNatcoAdminUser();
    }

    public function canCreateTrips(): bool
    {
        return ! $this->isNatcoDepartmentUser() || $this->isNatcoAdminUser();
    }

    public function canEditTrips(): bool
    {
        return $this->canViewTripsModule();
    }

    public function canDeleteTrips(): bool
    {
        return $this->isSuperadmin();
    }

    public function departmentalNavLabel(): ?string
    {
        return $this->isNatcoDepartmentUser()
            ? 'NATCO Departmental Login'
            : null;
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['email'];
    }
}
