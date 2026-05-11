<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'slug',
    'description',
    'access_scope',
    'can_view',
    'can_create',
    'can_edit',
    'can_delete',
    'can_manage_users',
    'can_manage_system_settings',
    'sidebar_permissions',
    'page_permissions',
    'is_system',
])]
class Role extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const ACCESS_SCOPES = [
        'global' => 'Global',
        'department' => 'Department',
        'district' => 'District',
        'division' => 'Division',
        'route' => 'Route',
    ];

    public const SIDEBAR_GROUPS = [
        'Main Navigation' => [
            'dashboard' => ['label' => 'Dashboard', 'copy' => 'Show the dashboard entry in the sidebar.'],
            'trips' => ['label' => 'Trip Management', 'copy' => 'Show trip listing and trip actions in navigation.'],
            'payments' => ['label' => 'Payments', 'copy' => 'Show payment statuses and payment pages.'],
            'challans' => ['label' => 'Challans', 'copy' => 'Show the challans section.'],
            'transporters' => ['label' => 'Transporters', 'copy' => 'Show transporter pages and shortcuts.'],
            'routes' => ['label' => 'Routes', 'copy' => 'Show route pages and shortcuts.'],
            'vehicles' => ['label' => 'Vehicles', 'copy' => 'Show vehicle pages and vehicle type links.'],
            'fares' => ['label' => 'Fares', 'copy' => 'Show fare pages and shortcuts.'],
            'grants' => ['label' => 'Grants', 'copy' => 'Show grants and grant release navigation.'],
            'reports' => ['label' => 'Reports', 'copy' => 'Show the reports menu item.'],
        ],
        'Settings Navigation' => [
            'settings.profile' => ['label' => 'Edit Profile', 'copy' => 'Show profile settings.'],
            'settings.users' => ['label' => 'Users', 'copy' => 'Show add user and all users links.'],
            'settings.roles' => ['label' => 'Roles', 'copy' => 'Show role management links.'],
            'settings.divisions' => ['label' => 'Divisions', 'copy' => 'Show division settings.'],
            'settings.districts' => ['label' => 'Districts', 'copy' => 'Show district settings.'],
            'settings.departments' => ['label' => 'Departments', 'copy' => 'Show department settings.'],
            'settings.captcha' => ['label' => 'Captcha Settings', 'copy' => 'Show captcha configuration.'],
            'logs.security' => ['label' => 'Security Logs', 'copy' => 'Show the security logs section.'],
            'logs.audit' => ['label' => 'Audit Logs', 'copy' => 'Show the audit logs section.'],
        ],
    ];

    public const PAGE_PERMISSION_GROUPS = [
        'Trips' => [
            'trips.view' => ['label' => 'View Trips', 'copy' => 'Open trip listing and detail pages.'],
            'trips.create' => ['label' => 'Create Trips', 'copy' => 'Create new trip records.'],
            'trips.edit' => ['label' => 'Edit Trips', 'copy' => 'Edit existing trip records.'],
            'trips.delete' => ['label' => 'Delete Trips', 'copy' => 'Delete trip records.'],
        ],
        'Trip Rules' => [
            'trips.change_route' => ['label' => 'Manual Route Change', 'copy' => 'Let users change the auto-filled route on trip forms.'],
            'trips.edit_driver_name' => ['label' => 'Driver Name Editable', 'copy' => 'Let users edit the auto-filled driver name on trip forms.'],
            'trips.edit_total_amount' => ['label' => 'Total Amount Editable', 'copy' => 'Let users manually edit the total amount on trip forms.'],
            'trips.single_vehicle_per_day' => ['label' => 'Allow Multiple Trips Per Vehicle/Day', 'copy' => 'Let this role add more than one trip for the same vehicle on the same date.'],
        ],
        'Payments' => [
            'payments.view' => ['label' => 'View Payments', 'copy' => 'Open payment listing and payment detail pages.'],
            'payments.edit_status' => ['label' => 'Edit Payment Status', 'copy' => 'Allow updating payment status to paid, on hold, rejected, or other payment states.'],
            'payments.manage' => ['label' => 'Manage Payments', 'copy' => 'Approve, hold, reject, and bulk manage payments.'],
        ],
        'Challans' => [
            'challans.view' => ['label' => 'View Challans', 'copy' => 'Open challan listing, images, and detail pages.'],
            'challans.create' => ['label' => 'Create Challans', 'copy' => 'Create new challan records.'],
            'challans.manage' => ['label' => 'Manage Challans', 'copy' => 'Edit and delete challan records.'],
        ],
        'Transporters' => [
            'transporters.view' => ['label' => 'View Transporters', 'copy' => 'Open transporter listing and detail pages.'],
            'transporters.create' => ['label' => 'Create Transporters', 'copy' => 'Create new transporter records.'],
            'transporters.manage' => ['label' => 'Manage Transporters', 'copy' => 'Edit and delete transporter records.'],
        ],
        'Routes' => [
            'routes.view' => ['label' => 'View Routes', 'copy' => 'Open route listing and detail pages.'],
            'routes.create' => ['label' => 'Create Routes', 'copy' => 'Create new route records.'],
            'routes.manage' => ['label' => 'Manage Routes', 'copy' => 'Edit and delete routes.'],
        ],
        'Vehicles' => [
            'vehicles.view' => ['label' => 'View Vehicles', 'copy' => 'Open vehicle listing and detail pages.'],
            'vehicles.create' => ['label' => 'Create Vehicles', 'copy' => 'Create new vehicle records.'],
            'vehicles.manage' => ['label' => 'Manage Vehicles', 'copy' => 'Edit and delete vehicle records.'],
            'vehicle-types.manage' => ['label' => 'Manage Vehicle Types', 'copy' => 'Open and update vehicle types.'],
        ],
        'Fares' => [
            'fares.view' => ['label' => 'View Fares', 'copy' => 'Open fare listing and detail pages.'],
            'fares.create' => ['label' => 'Create Fares', 'copy' => 'Create new fare records.'],
            'fares.manage' => ['label' => 'Manage Fares', 'copy' => 'Edit and delete fares.'],
        ],
        'Grants' => [
            'grants.view' => ['label' => 'View Grants', 'copy' => 'Open grant listing and detail pages.'],
            'grants.create' => ['label' => 'Create Grants', 'copy' => 'Create new grants.'],
            'grants.manage' => ['label' => 'Manage Grants', 'copy' => 'Edit and delete grants.'],
            'grant-releases.view' => ['label' => 'View Grant Releases', 'copy' => 'Open grant release listing and detail pages.'],
            'grant-releases.create' => ['label' => 'Create Grant Releases', 'copy' => 'Create new grant releases.'],
            'grant-releases.manage' => ['label' => 'Manage Grant Releases', 'copy' => 'Edit and delete grant releases.'],
        ],
        'Administration' => [
            'users.manage' => ['label' => 'Manage Users', 'copy' => 'Create, edit, delete, and update user passwords.'],
            'roles.manage' => ['label' => 'Manage Roles', 'copy' => 'Create and update role access settings.'],
            'divisions.manage' => ['label' => 'Manage Divisions', 'copy' => 'Create, edit, and delete divisions.'],
            'districts.manage' => ['label' => 'Manage Districts', 'copy' => 'Create, edit, and delete districts.'],
            'departments.manage' => ['label' => 'Manage Departments', 'copy' => 'Create, edit, and delete departments.'],
            'captcha.manage' => ['label' => 'Manage Captcha Settings', 'copy' => 'Update login captcha settings.'],
            'reports.view' => ['label' => 'View Reports', 'copy' => 'Open reports and export report data.'],
            'logs.security.view' => ['label' => 'View Security Logs', 'copy' => 'Open and clean security logs.'],
            'logs.audit.view' => ['label' => 'View Audit Logs', 'copy' => 'Open and clean audit logs.'],
        ],
    ];

    protected function casts(): array
    {
        return [
            'can_view' => 'boolean',
            'can_create' => 'boolean',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_manage_users' => 'boolean',
            'can_manage_system_settings' => 'boolean',
            'sidebar_permissions' => 'array',
            'page_permissions' => 'array',
            'is_system' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role', 'slug');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name', 'slug'];
    }

    public static function sidebarPermissionKeys(): array
    {
        return array_keys(array_merge(...array_values(self::SIDEBAR_GROUPS)));
    }

    public static function pagePermissionKeys(): array
    {
        return array_keys(array_merge(...array_values(self::PAGE_PERMISSION_GROUPS)));
    }

    public function resolvedSidebarPermissions(): array
    {
        return $this->resolvePermissions($this->sidebar_permissions, $this->defaultSidebarPermissions());
    }

    public function resolvedPagePermissions(): array
    {
        return $this->resolvePermissions($this->page_permissions, $this->defaultPagePermissions());
    }

    public function allowsSidebar(string $key): bool
    {
        return in_array($key, $this->resolvedSidebarPermissions(), true);
    }

    public function allowsPage(string $key): bool
    {
        return in_array($key, $this->resolvedPagePermissions(), true);
    }

    private function resolvePermissions(mixed $storedPermissions, array $defaults): array
    {
        if (! is_array($storedPermissions)) {
            return $defaults;
        }

        return array_values(array_intersect($storedPermissions, array_unique([...self::sidebarPermissionKeys(), ...self::pagePermissionKeys()])));
    }

    private function defaultSidebarPermissions(): array
    {
        return match ($this->slug) {
            'super_admin', 'superadmin', 'admin' => self::sidebarPermissionKeys(),
            'departmental_admin' => [
                'dashboard',
                'payments',
                'challans',
                'settings.profile',
            ],
            'natco_admin' => [
                'dashboard',
                'trips',
                'challans',
                'settings.profile',
            ],
            'district_admin', 'divisional_admin' => [
                'dashboard',
                'trips',
                'payments',
                'challans',
                'transporters',
                'routes',
                'vehicles',
                'fares',
                'grants',
                'settings.profile',
            ],
            'user' => [
                'dashboard',
                'trips',
                'payments',
                'transporters',
                'routes',
                'challans',
                'vehicles',
                'fares',
                'grants',
                'settings.profile',
            ],
            default => ['dashboard', 'settings.profile'],
        };
    }

    private function defaultPagePermissions(): array
    {
        return match ($this->slug) {
            'super_admin', 'superadmin', 'admin' => self::pagePermissionKeys(),
            'departmental_admin' => [
                'payments.view',
                'payments.edit_status',
                'payments.manage',
                'challans.view',
            ],
            'natco_admin' => [
                'trips.view',
                'trips.create',
                'trips.edit',
                'challans.view',
            ],
            'district_admin', 'divisional_admin' => [
                'trips.view',
                'trips.create',
                'payments.view',
                'payments.edit_status',
                'transporters.view',
                'transporters.create',
                'routes.view',
                'routes.create',
                'vehicles.view',
                'vehicles.create',
                'fares.view',
                'fares.create',
                'grants.view',
                'grants.create',
                'grant-releases.view',
                'grant-releases.create',
                'challans.view',
            ],
            'user' => [
                'trips.view',
                'trips.create',
                'payments.view',
                'payments.edit_status',
                'transporters.view',
                'transporters.create',
                'routes.view',
                'routes.create',
                'vehicles.view',
                'vehicles.create',
                'fares.view',
                'fares.create',
                'grants.view',
                'grants.create',
                'grant-releases.view',
                'grant-releases.create',
                'challans.view',
            ],
            default => [],
        };
    }
}
