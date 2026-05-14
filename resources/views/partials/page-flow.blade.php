@php
    $routeName = request()->route()?->getName() ?? '';
    $isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System');
    $companyListCrumb = $isSuperAdmin ? 'Organizations' : 'Partner List';
    $companyId = request()->route('id') ?? request()->route('company');

    $pageTitle = 'Page';
    $crumbs = []; // Array of [label, url]

    // Default: Start with Organization List if it's a company-related route
    if (str_starts_with($routeName, 'companies.')) {
        $crumbs[] = [$companyListCrumb, route('companies.index')];
    }

    switch (true) {
        case request()->routeIs('companies.index'):
            $pageTitle = $companyListCrumb;
            $crumbs = [[$companyListCrumb, route('companies.index')]];
            break;
            
        case request()->routeIs('companies.create'):
            $pageTitle = $isSuperAdmin ? 'Add Organization' : 'Add Partner';
            $crumbs[] = [$pageTitle, route('companies.create')];
            break;
            
        case request()->routeIs('companies.show'):
            $pageTitle = 'Organization Profile';
            $crumbs[] = ['Organization Profile', route('companies.show', $companyId)];
            break;
            
        case request()->routeIs('companies.edit'):
            $pageTitle = $isSuperAdmin ? 'Edit Organization' : 'Edit Partner';
            $crumbs[] = [$pageTitle, route('companies.edit', $companyId)];
            break;
            
        case request()->routeIs('companies.attachments'):
            $pageTitle = 'Organization Attachments';
            $crumbs[] = ['Attachments', route('companies.attachments', $companyId)];
            break;
            
        case request()->routeIs('companies.branches'):
            $pageTitle = 'Organization Branches';
            $crumbs[] = ['Branches', route('companies.branches', $companyId)];
            break;
            
        case request()->routeIs('companies.user-roles'):
            $pageTitle = 'User and Roles';
            $crumbs[] = ['User and Roles', route('companies.user-roles', $companyId)];
            break;
            
        case request()->routeIs('companies.features'):
            $pageTitle = 'Feature Management';
            $crumbs[] = ['Feature Management', route('companies.features', $companyId)];
            break;
            
        case request()->routeIs('companies.roles-permissions'):
            $pageTitle = 'Roles Permissions';
            $crumbs[] = ['Roles Permissions', route('companies.roles-permissions', $companyId)];
            break;
            
        case request()->routeIs('companies.travel-policy'):
            $pageTitle = 'Travel Policy';
            $crumbs[] = ['Travel Policy', route('companies.travel-policy', $companyId)];
            break;
            
        case request()->routeIs('companies.billing-entity'):
            $pageTitle = 'Billing Entity';
            $crumbs[] = ['Billing Entity', route('companies.billing-entity', $companyId)];
            break;
            
        case request()->routeIs('branches.index'):
            $pageTitle = 'Branches';
            $crumbs = [['Branches', route('branches.index')]];
            break;
            
        case request()->routeIs('branches.create'):
            $pageTitle = 'Add Branch';
            $crumbs = [['Branches', route('branches.index')], ['Add Branch', route('branches.create')]];
            break;
            
        case request()->routeIs('branches.edit'):
            $pageTitle = 'Edit Branch';
            $crumbs = [['Branches', route('branches.index')], ['Edit Branch', '#']];
            break;
            
        case request()->routeIs('users.index'):
            $pageTitle = 'Users';
            $crumbs = [['Users', route('users.index', ['companyId' => request()->route('companyId')])]];
            break;
            
        case request()->routeIs('users.create'):
            $pageTitle = 'Add User';
            $crumbs = [['Users', route('users.index', ['companyId' => request()->route('companyId')])], ['Add User', route('users.create', ['companyId' => request()->route('companyId')])]];
            break;
            
        case request()->routeIs('users.edit'):
            $pageTitle = 'Edit User';
            $crumbs = [['Users', route('users.index', ['companyId' => request()->route('companyId')])], ['Edit User', '#']];
            break;

        case request()->routeIs('grades.index'):
            $pageTitle = 'Grades';
            $crumbs = [['Grades', route('grades.index', ['companyId' => request()->route('companyId')])]];
            break;
            
        case request()->routeIs('grades.create'):
            $pageTitle = 'Add Grade';
            $crumbs = [['Grades', route('grades.index', ['companyId' => request()->route('companyId')])], ['Add Grade', '#']];
            break;
            
        case request()->routeIs('grades.edit'):
            $pageTitle = 'Edit Grade';
            $crumbs = [['Grades', route('grades.index', ['companyId' => request()->route('companyId')])], ['Edit Grade', '#']];
            break;

        case request()->routeIs('divisions.index'):
            $pageTitle = 'Divisions';
            $crumbs = [['Divisions', route('divisions.index', ['companyId' => request()->route('companyId')])]];
            break;
            
        case request()->routeIs('divisions.create'):
            $pageTitle = 'Add Division';
            $crumbs = [['Divisions', route('divisions.index', ['companyId' => request()->route('companyId')])], ['Add Division', '#']];
            break;
            
        case request()->routeIs('divisions.edit'):
            $pageTitle = 'Edit Division';
            $crumbs = [['Divisions', route('divisions.index', ['companyId' => request()->route('companyId')])], ['Edit Division', '#']];
            break;

        case request()->routeIs('departments.index'):
            $pageTitle = 'Departments';
            $crumbs = [['Departments', route('departments.index', ['companyId' => request()->route('companyId')])]];
            break;
            
        case request()->routeIs('departments.create'):
            $pageTitle = 'Add Department';
            $crumbs = [['Departments', route('departments.index', ['companyId' => request()->route('companyId')])], ['Add Department', '#']];
            break;
            
        case request()->routeIs('departments.edit'):
            $pageTitle = 'Edit Department';
            $crumbs = [['Departments', route('departments.index', ['companyId' => request()->route('companyId')])], ['Edit Department', '#']];
            break;
            
        case request()->routeIs('roles.index'):
            $pageTitle = 'Roles & Permissions';
            $crumbs = [['Roles & Permissions', route('roles.index')]];
            break;
            
        case request()->routeIs('features'):
            $pageTitle = 'Feature Management';
            $crumbs = [['Feature Management', route('features')]];
            break;
            
        case request()->routeIs('admin.trip-purpose*'):
            $pageTitle = 'Trip Purpose';
            $crumbs = [['Trip Purpose', '#']];
            break;
            
        case request()->routeIs('admin.system-settings*'):
            $pageTitle = 'System Settings';
            $crumbs = [['System Settings', route('admin.system-settings')]];
            break;

        case request()->routeIs('admin.travel-policy.create'):
            $pageTitle = 'Create Travel Policy';
            $crumbs = [['System Settings', route('admin.system-settings')], ['Create Travel Policy', '#']];
            break;

        case request()->routeIs('admin.travel-policy.view'):
            $pageTitle = 'Travel Policy View';
            $crumbs = [['System Settings', route('admin.system-settings')], ['Travel Policy View', '#']];
            break;

        case request()->routeIs('admin.travel-policy.edit'):
            $pageTitle = 'Edit Travel Policy';
            $crumbs = [['System Settings', route('admin.system-settings')], ['Edit Travel Policy', '#']];
            break;
            
        case request()->routeIs('admin.audit-logs'):
            $pageTitle = 'Audit Logs';
            $crumbs = [['Audit Logs', route('admin.audit-logs')]];
            break;

        case request()->routeIs('admin.audit-logs.view'):
            $pageTitle = 'View Activity';
            $crumbs = [['Audit Logs', route('admin.audit-logs')], ['View Activity', '#']];
            break;
            
        case request()->routeIs('subscriptions.index'):
            $pageTitle = 'Subscriptions';
            $crumbs = [['Subscriptions', route('subscriptions.index')]];
            break;

        case request()->routeIs('subscriptions.create'):
            $pageTitle = 'Add Subscription';
            $crumbs = [['Subscriptions', route('subscriptions.index')], ['Add Subscription', '#']];
            break;

        case request()->routeIs('subscriptions.view'):
            $pageTitle = 'View Subscription';
            $crumbs = [['Subscriptions', route('subscriptions.index')], ['View Subscription', '#']];
            break;

        case request()->routeIs('subscriptions.edit'):
            $pageTitle = 'Edit Subscription';
            $crumbs = [['Subscriptions', route('subscriptions.index')], ['Edit Subscription', '#']];
            break;
            
        default:
            $pageTitle = str($routeName !== '' ? $routeName : 'Page')
                ->replace(['.', '-'], ' ')
                ->title()
                ->toString();
            $crumbs = [[$pageTitle, '#']];
            break;
    }
@endphp

<div class="mb-3 flex justify-end">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="flex justify-end bg-gradient-to-r from-white to-[#f2feff] px-4 py-2">
            <div class="flex items-center gap-2 text-[11px] font-semibold text-gray-500">
                @foreach ($crumbs as $index => $crumb)
                    @if($index > 0)
                        <svg class="h-3.5 w-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                        </svg>
                    @endif

                    @if($loop->last)
                        <span class="font-bold text-[#2ab4c0]">
                            {{ $crumb[0] }}
                        </span>
                    @else
                        <a href="{{ $crumb[1] }}" class="text-gray-500 hover:text-[#2ab4c0] transition-colors">
                            {{ $crumb[0] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
