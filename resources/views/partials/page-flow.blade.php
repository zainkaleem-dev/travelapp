@php
    $routeName = request()->route()?->getName() ?? '';
    $isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System');
    $companyListCrumb = $isSuperAdmin ? 'Organizations' : 'Partner List';

    $pageTitle = 'Page';
    $crumbs = [];

    switch (true) {
        case request()->routeIs('companies.index'):
            $pageTitle = $companyListCrumb;
            $crumbs = [$companyListCrumb];
            break;
        case request()->routeIs('companies.create'):
            $pageTitle = $isSuperAdmin ? 'Add Organization' : 'Add Partner';
            $crumbs = [$companyListCrumb, $isSuperAdmin ? 'Add Organization' : 'Add Partner'];
            break;
        case request()->routeIs('companies.show'):
            $pageTitle = 'Organization Profile';
            $crumbs = [$companyListCrumb, 'Organization Profile'];
            break;
        case request()->routeIs('companies.edit'):
            $pageTitle = $isSuperAdmin ? 'Edit Organization' : 'Edit Partner';
            $crumbs = [$companyListCrumb, $isSuperAdmin ? 'Edit Organization' : 'Edit Partner'];
            break;
        case request()->routeIs('companies.attachments'):
            $pageTitle = 'Organization Attachments';
            $crumbs = [$companyListCrumb, 'Attachments'];
            break;
        case request()->routeIs('companies.branches'):
            $pageTitle = 'Organization Branches';
            $crumbs = [$companyListCrumb, 'Branches'];
            break;
        case request()->routeIs('companies.user-roles'):
            $pageTitle = 'User and Roles';
            $crumbs = [$companyListCrumb, 'User and Roles'];
            break;
        case request()->routeIs('companies.features'):
            $pageTitle = 'Feature Management';
            $crumbs = [$companyListCrumb, 'Feature Management'];
            break;
        case request()->routeIs('companies.roles-permissions'):
            $pageTitle = 'Roles Permissions';
            $crumbs = [$companyListCrumb, 'Roles Permissions'];
            break;
        case request()->routeIs('companies.billing-entity'):
            $pageTitle = 'Billing Entity';
            $crumbs = [$companyListCrumb, 'Billing Entity'];
            break;
        case request()->routeIs('branches.index'):
            $pageTitle = 'Branches';
            $crumbs = ['Branches'];
            break;
        case request()->routeIs('branches.create'):
            $pageTitle = 'Add Branch';
            $crumbs = ['Branches', 'Add Branch'];
            break;
        case request()->routeIs('branches.edit'):
            $pageTitle = 'Edit Branch';
            $crumbs = ['Branches', 'Edit Branch'];
            break;
        case request()->routeIs('users.index'):
            $pageTitle = 'Users';
            $crumbs = ['Users'];
            break;
        case request()->routeIs('users.create'):
            $pageTitle = 'Add User';
            $crumbs = ['Users', 'Add User'];
            break;
        case request()->routeIs('users.edit'):
            $pageTitle = 'Edit User';
            $crumbs = ['Users', 'Edit User'];
            break;
        case request()->routeIs('roles.index'):
            $pageTitle = 'Roles & Permissions';
            $crumbs = ['Roles & Permissions'];
            break;
        case request()->routeIs('features'):
            $pageTitle = 'Feature Management';
            $crumbs = ['Feature Management'];
            break;
        case request()->routeIs('admin.trip-purpose*'):
            $pageTitle = 'Trip Purpose';
            $crumbs = ['Trip Purpose'];
            break;
        case request()->routeIs('admin.audit-logs*'):
            $pageTitle = 'Audit Logs';
            $crumbs = ['Audit Logs'];
            break;
        default:
            $pageTitle = str($routeName !== '' ? $routeName : 'Page')
                ->replace(['.', '-'], ' ')
                ->title()
                ->toString();
            $crumbs = [$pageTitle];
            break;
    }
@endphp

<div class="mb-3 flex justify-end">
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex justify-end bg-gradient-to-r from-white to-[#f2feff] px-4 py-2.5">
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 sm:text-sm">
            <a href="{{ route('root') }}"
                class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-gray-500 transition-colors hover:bg-white hover:text-[#2ab4c0]">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M10.707 1.293a1 1 0 0 0-1.414 0l-8 8A1 1 0 0 0 2 11h1v6a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4h2v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-6h1a1 1 0 0 0 .707-1.707l-8-8Z" />
                </svg>
                <span>Home</span>
            </a>

            @foreach ($crumbs as $crumb)
                <svg class="h-3.5 w-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                </svg>

                <span class="{{ $loop->last ? 'font-bold text-[#2ab4c0]' : 'text-gray-500' }}">
                    {{ $crumb }}
                </span>
            @endforeach
        </div>
    </div>
</div>
</div>
