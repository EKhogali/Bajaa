<?php

namespace App\Http\Middleware;

use Closure;

class RestrictReporterAccess
{
    private const REPORTER_TYPE = 4;

    private const AUTH_ROUTE_NAMES = [
        'login', 'logout', 'register',
        'password.request', 'password.email', 'password.reset', 'password.update',
        'verification.notice', 'verification.verify', 'verification.resend',
    ];

    private const SETUP_PATHS = ['', 'home', 'company_and_financial_year'];

    // Internal helper endpoints behind some reports (print/execute actions).
    // Always allowed for a reporter, not individually permission-gated.
    private const SUPPORT_PATHS = ['gl_index', 'l_index', 'gl_exec', 'l_exec', 'tr_index', 'tr_exec'];

    // route name => module key (must match config/access_modules.php)
    private const ROUTE_NAME_MODULES = [
        'reports.vendor_report'        => 'vendor_report',
        'reports.vendor_report_print'  => 'vendor_report',
        'reports.debt_statement'       => 'debt_statement_report',
        'reports.debt_statement_print' => 'debt_statement_report',
    ];

    // url path => module key
    private const PATH_MODULES = [
        'income_report'                        => 'income_report',
        'income_report2'                       => 'income_report2',
        'estimated_expense_report'             => 'estimated_expense_report',
        'treasury_report'                      => 'treasury_report',
        'ledger2'                               => 'ledger_report',
        'account_details_report'               => 'account_details_report',
        'category_percentage_report'           => 'category_percentage_report',
        'pulled_from_net_income_report'        => 'pulled_from_net_income_report',
        'partners_accounts_report'             => 'partners_accounts_report',
        'partners_accounts_with_income_report' => 'partners_accounts_with_income_report',
        'daily_report'                         => 'daily_report',
        'daily_report002'                      => 'daily_report002',
    ];

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || (int) $user->type !== self::REPORTER_TYPE) {
            return $next($request);
        }

        $routeName = optional($request->route())->getName();

        if ($routeName && in_array($routeName, self::AUTH_ROUTE_NAMES, true)) {
            return $next($request);
        }

        $path = trim($request->path(), '/');

        if (in_array($path, self::SETUP_PATHS, true) || in_array($path, self::SUPPORT_PATHS, true)) {
            return $next($request);
        }

        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            abort(403, 'حسابك للاطلاع على التقارير فقط، ولا يمكن إضافة أو تعديل أي بيانات.');
        }

        $moduleKey = ($routeName && isset(self::ROUTE_NAME_MODULES[$routeName]))
            ? self::ROUTE_NAME_MODULES[$routeName]
            : (self::PATH_MODULES[$path] ?? null);

        if (!$moduleKey || !$user->hasModuleAccess($moduleKey)) {
            abort(403, 'لا تملك صلاحية الاطلاع على هذا التقرير.');
        }

        return $next($request);
    }
}