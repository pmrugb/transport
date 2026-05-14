<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'district_id', 'division_id', 'route_id', 'all_routes_access'])]
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
            'route_id' => 'integer',
            'all_routes_access' => 'boolean',
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
        return $this->accessRole?->access_scope === 'department'
            || (! $this->accessRole && $this->isLegacyNatcoDepartmentUser());
    }

    public function canManagePayments(): bool
    {
        return $this->canAccessPage('payments.manage')
            || (! $this->accessRole && ($this->isSuperadmin() || $this->isLegacyNatcoDepartmentUser()));
    }

    public function canEditPaymentStatus(): bool
    {
        return $this->canAccessPage('payments.edit_status')
            || (! $this->accessRole && ($this->isSuperadmin() || $this->isLegacyNatcoDepartmentUser()));
    }

    public function canAccessPaymentsModule(): bool
    {
        return $this->canAccessPage('payments.view')
            || (! $this->accessRole && ! $this->isLegacyNatcoAdminUser());
    }

    public function canSeePaymentsNav(): bool
    {
        return $this->canAccessSidebar('payments');
    }

    public function isNatcoDepartmentUser(): bool
    {
        return $this->hasPaymentsOnlySidebar();
    }

    public function isNatcoAdminUser(): bool
    {
        return (! $this->accessRole && $this->isLegacyNatcoAdminUser())
            || ($this->hasPaymentsOnlySidebar() && $this->canAccessPage('trips.edit') && ! $this->canAccessPage('payments.view'));
    }

    public function canViewTripsModule(): bool
    {
        return $this->canAccessPage('trips.view')
            || (! $this->accessRole && ($this->isSuperadmin() || $this->isLegacyNatcoAdminUser()));
    }

    public function canCreateTrips(): bool
    {
        return $this->canAccessPage('trips.create')
            || (! $this->accessRole && (! $this->isLegacyNatcoDepartmentUser() || $this->isLegacyNatcoAdminUser()));
    }

    public function canEditTrips(): bool
    {
        return $this->canAccessPage('trips.edit')
            || (! $this->accessRole && $this->canViewTripsModule());
    }

    public function canDeleteTrips(): bool
    {
        return $this->canAccessPage('trips.delete')
            || (! $this->accessRole && $this->isSuperadmin());
    }

    public function canChangeTripRoute(): bool
    {
        return $this->canAccessPage('trips.change_route')
            || (! $this->accessRole && $this->isSuperadmin());
    }

    public function canEditTripDriverName(): bool
    {
        return $this->canAccessPage('trips.edit_driver_name')
            || (! $this->accessRole && $this->isSuperadmin());
    }

    public function canEditTripTotalAmount(): bool
    {
        return $this->canAccessPage('trips.edit_total_amount')
            || (! $this->accessRole && $this->isSuperadmin());
    }

    public function canAddMultipleTripsPerVehiclePerDay(): bool
    {
        return $this->canAccessPage('trips.single_vehicle_per_day');
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

    public function accessRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'slug');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(TransportRoute::class, 'route_user', 'user_id', 'route_id')
            ->withTimestamps()
            ->orderBy('route_name');
    }

    public function isRouteScoped(): bool
    {
        return $this->accessRole?->access_scope === 'route';
    }

    public function scopedRouteIds(): ?array
    {
        if (! $this->isRouteScoped()) {
            return null;
        }

        if ($this->all_routes_access) {
            return null;
        }

        $routeIds = $this->relationLoaded('routes')
            ? $this->routes->pluck('id')->all()
            : $this->routes()->pluck('transport_routes.id')->all();

        if ($routeIds === [] && $this->route_id !== null) {
            $routeIds = [$this->route_id];
        }

        return array_values(array_unique(array_map('intval', $routeIds)));
    }

    public function scopedRouteId(): ?int
    {
        $routeIds = $this->scopedRouteIds();

        if ($routeIds === null) {
            return null;
        }

        return count($routeIds) === 1 ? $routeIds[0] : null;
    }

    public function hasRouteAccess(int $routeId): bool
    {
        $scopedRouteIds = $this->scopedRouteIds();

        return $scopedRouteIds === null || in_array($routeId, $scopedRouteIds, true);
    }

    public function canAccessSidebar(string $key): bool
    {
        if ($key === 'dashboard') {
            return true;
        }

        if ($this->accessRole) {
            return $this->accessRole->allowsSidebar($key);
        }

        return match ($key) {
            'trips' => true,
            'payments' => $this->canAccessPaymentsModule(),
            'challans' => true,
            'transporters' => ! $this->hasPaymentsOnlySidebar(),
            'routes' => ! $this->hasPaymentsOnlySidebar(),
            'vehicles' => ! $this->hasPaymentsOnlySidebar(),
            'fares' => ! $this->hasPaymentsOnlySidebar(),
            'grants' => ! $this->hasPaymentsOnlySidebar(),
            'reports' => $this->isSuperadmin(),
            'settings.profile' => true,
            'settings.users', 'settings.roles', 'settings.divisions', 'settings.districts', 'settings.departments', 'settings.captcha', 'logs.security', 'logs.audit' => ! $this->hasPaymentsOnlySidebar(),
            default => false,
        };
    }

    public function canAccessPage(string $key): bool
    {
        if ($this->accessRole) {
            return $this->accessRole->allowsPage($key);
        }

        return match ($key) {
            'trips.view' => true,
            'trips.create' => ! $this->isLegacyNatcoDepartmentUser() || $this->isLegacyNatcoAdminUser(),
            'trips.edit' => $this->isSuperadmin() || $this->isLegacyNatcoAdminUser(),
            'trips.delete' => $this->isSuperadmin(),
            'payments.view' => ! $this->isLegacyNatcoAdminUser(),
            'payments.manage' => $this->isSuperadmin() || $this->isLegacyNatcoDepartmentUser(),
            'challans.view' => $this->isSuperadmin() || $this->isLegacyNatcoDepartmentUser() || ! $this->hasPaymentsOnlySidebar(),
            'challans.create', 'challans.manage' => $this->isSuperadmin(),
            'transporters.view', 'transporters.create', 'routes.view', 'routes.create', 'vehicles.view', 'vehicles.create', 'fares.view', 'fares.create', 'grants.view', 'grants.create', 'grant-releases.view', 'grant-releases.create' => ! $this->hasPaymentsOnlySidebar(),
            'transporters.manage', 'routes.manage', 'vehicles.manage', 'vehicle-types.manage', 'fares.manage', 'grants.manage', 'grant-releases.manage', 'users.manage', 'roles.manage', 'divisions.manage', 'districts.manage', 'departments.manage', 'captcha.manage', 'reports.view', 'logs.security.view', 'logs.audit.view' => $this->isSuperadmin(),
            default => false,
        };
    }

    public function canManageUsers(): bool
    {
        return $this->canAccessPage('users.manage');
    }

    public function canManageRoles(): bool
    {
        return $this->canAccessPage('roles.manage');
    }

    public function canManageDivisions(): bool
    {
        return $this->canAccessPage('divisions.manage');
    }

    public function canManageDistricts(): bool
    {
        return $this->canAccessPage('districts.manage');
    }

    public function canManageDepartments(): bool
    {
        return $this->canAccessPage('departments.manage');
    }

    public function canManageCaptcha(): bool
    {
        return $this->canAccessPage('captcha.manage');
    }

    public function canViewSecurityLogs(): bool
    {
        return $this->canAccessPage('logs.security.view');
    }

    public function canViewAuditLogs(): bool
    {
        return $this->canAccessPage('logs.audit.view');
    }

    public function canViewReports(): bool
    {
        return $this->canAccessPage('reports.view');
    }

    public function canViewTransporters(): bool
    {
        return $this->canAccessPage('transporters.view');
    }

    public function canCreateTransporters(): bool
    {
        return $this->canAccessPage('transporters.create');
    }

    public function canManageTransporters(): bool
    {
        return $this->canAccessPage('transporters.manage');
    }

    public function canViewRoutes(): bool
    {
        return $this->canAccessPage('routes.view');
    }

    public function canCreateRoutes(): bool
    {
        return $this->canAccessPage('routes.create');
    }

    public function canManageRoutes(): bool
    {
        return $this->canAccessPage('routes.manage');
    }

    public function canViewVehicles(): bool
    {
        return $this->canAccessPage('vehicles.view');
    }

    public function canCreateVehicles(): bool
    {
        return $this->canAccessPage('vehicles.create');
    }

    public function canManageVehicles(): bool
    {
        return $this->canAccessPage('vehicles.manage');
    }

    public function canManageVehicleTypes(): bool
    {
        return $this->canAccessPage('vehicle-types.manage');
    }

    public function canViewFares(): bool
    {
        return $this->canAccessPage('fares.view');
    }

    public function canCreateFares(): bool
    {
        return $this->canAccessPage('fares.create');
    }

    public function canManageFares(): bool
    {
        return $this->canAccessPage('fares.manage');
    }

    public function canViewGrants(): bool
    {
        return $this->canAccessPage('grants.view');
    }

    public function canCreateGrants(): bool
    {
        return $this->canAccessPage('grants.create');
    }

    public function canManageGrants(): bool
    {
        return $this->canAccessPage('grants.manage');
    }

    public function canViewGrantReleases(): bool
    {
        return $this->canAccessPage('grant-releases.view');
    }

    public function canCreateGrantReleases(): bool
    {
        return $this->canAccessPage('grant-releases.create');
    }

    public function canManageGrantReleases(): bool
    {
        return $this->canAccessPage('grant-releases.manage');
    }

    public function canViewChallans(): bool
    {
        return $this->canAccessPage('challans.view');
    }

    public function canCreateChallans(): bool
    {
        return $this->canAccessPage('challans.create');
    }

    public function canManageChallans(): bool
    {
        return $this->canAccessPage('challans.manage');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['email'];
    }

    public function softDeletedOriginalEmail(): ?string
    {
        return $this->softDeletedOriginalValueForField('email');
    }

    public function canRestoreOriginalEmail(): bool
    {
        $originalEmail = $this->softDeletedOriginalEmail();

        return $originalEmail !== null && $this->canRestoreSoftDeletedUniqueFields();
    }

    private function isLegacyNatcoDepartmentUser(): bool
    {
        return in_array(strtolower((string) $this->email), self::NATCO_DEPARTMENT_EMAIL_ALIASES, true);
    }

    private function isLegacyNatcoAdminUser(): bool
    {
        return in_array(strtolower((string) $this->email), self::NATCO_ADMIN_EMAIL_ALIASES, true);
    }
}
