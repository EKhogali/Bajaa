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

    private const ALLOWED_ROUTE_NAMES = [
        'reports.vendor_report',
        'reports.vendor_report_print',
        'reports.debt_statement',
        'reports.debt_statement_print',
    ];

    private const ALLOWED_PATHS = [
        'gl_index', 'l_index', 'gl_exec', 'l_exec', 'tr_index', 'tr_exec',
        'income_report', 'income_report2', 'estimated_expense_report',
        'pulled_from_net_income_report', 'category_percentage_report',
        'treasury_report', 'ledger2', 'partners_accounts_report',
        'partners_accounts_with_income_report', 'account_details_report',
        'daily_report', 'daily_report002',
    ];

    // Needed so a reporter can go through the same company/financial-year
    // picker every other role uses — without it, session('company_id')
    // never gets set, and every report query (which all filter by
    // company_id) comes back empty.
    private const SETUP_PATHS = ['', 'home', 'company_and_financial_year'];

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

        if (in_array($path, self::SETUP_PATHS, true)) {
            return $next($request);
        }

        // A reporter can never add, edit, or delete anything, anywhere.
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            abort(403, 'حسابك للاطلاع على التقارير فقط، ولا يمكن إضافة أو تعديل أي بيانات.');
        }

        $isAllowedPage = ($routeName && in_array($routeName, self::ALLOWED_ROUTE_NAMES, true))
            || in_array($path, self::ALLOWED_PATHS, true);

        if (!$isAllowedPage) {
            abort(403, 'حسابك مخصص للاطلاع على التقارير فقط.');
        }

        return $next($request);
    }
}