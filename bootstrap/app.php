<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetTenantContext::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetCurrency::class,
        ]);

        $middleware->alias([ 
            'superadmin' => \App\Http\Middleware\EnsureSuperAdmin::class, 
            'superadmin.company' => \App\Http\Middleware\SetSuperAdminCompanyContext::class, 
            'superadmin.company_query' => \App\Http\Middleware\SetSuperAdminCompanyFromQuery::class, 
            'superadmin.tenant' => \App\Http\Middleware\RequireSuperAdminCompanySelected::class, 
            'company.tenant' => \App\Http\Middleware\EnsureCompanyTenant::class,
            'company.admin' => \App\Http\Middleware\EnsureCompanyAdmin::class,
            'branch.admin' => \App\Http\Middleware\EnsureBranchAdmin::class,
            'subcompany.admin' => \App\Http\Middleware\EnsureSubCompanyAdmin::class,
            'subcompany.branch_admin' => \App\Http\Middleware\EnsureSubCompanyBranchAdmin::class,
        ]); 
    }) 
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
