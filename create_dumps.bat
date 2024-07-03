@echo off
setlocal

:: Database credentials
set "USER=root"
set "PASSWORD=your_password"
set "DB_NAME=accdb"
set "CHARSET=utf8"

:: List of tables to export
set tables=(
    companies
    financial_years
    categories
    accounts
    treasuries
    treasury_transactions
    treasury_transaction_details
    sittings
)

:: Export each table
for %%t in %tables% do (
    set "TABLE_NAME=%%t"
    set "DUMP_NAME=0%%~nt_data"

    echo Exporting table %%t to %DUMP_NAME%.sql...
    mysqldump --column-statistics=0 --no-create-info --default-character-set=%CHARSET% -u %USER% -p%PASSWORD% %DB_NAME% %%t > %DUMP_NAME%.sql
    if %errorlevel% neq 0 (
        echo An error occurred while exporting table %%t.
        exit /b 1
    )
)

echo All tables exported successfully.
endlocal
pause
