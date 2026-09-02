<?php

/**
 * Konfigurasi format nomor dokumen per jenis.
 *
 * reset_period:
 *   - 'yearly'  -> counter reset tiap tahun (period_key = 'YYYY')
 *   - 'monthly' -> counter reset tiap bulan (period_key = 'YYYY-MM')
 *   - 'none'    -> counter tidak pernah reset (period_key = 'ALL')
 *
 * pad_length: jumlah digit angka berjalan, diisi leading zero.
 *
 * format: token yang tersedia:
 *   {prefix}   -> dari kolom 'prefix' di bawah
 *   {org_code} -> kode organisasi (organizations.code)
 *   {year}     -> tahun 4 digit saat generate
 *   {month}    -> bulan 2 digit saat generate
 *   {number}   -> angka berjalan, sudah di-pad sesuai pad_length
 */

return [

    'contract' => [
        'prefix' => 'CTR',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 4,
    ],

    'loan' => [
        'prefix' => 'LN',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 4,
    ],

    'payroll_run' => [
        'prefix' => 'PR',
        'format' => '{prefix}/{org_code}/{year}{month}/{number}',
        'reset_period' => 'monthly',
        'pad_length' => 3,
    ],

    'payslip' => [
        'prefix' => 'PS',
        'format' => '{prefix}/{org_code}/{year}{month}/{number}',
        'reset_period' => 'monthly',
        'pad_length' => 5,
    ],

    'employee_bonus' => [
        'prefix' => 'BNS',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 4,
    ],

    'employee_deduction' => [
        'prefix' => 'DED',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 4,
    ],

    'payroll_payment_batch' => [
        'prefix' => 'PPB',
        'format' => '{prefix}/{org_code}/{year}{month}/{number}',
        'reset_period' => 'monthly',
        'pad_length' => 3,
    ],

    'invoice_payment' => [
        'prefix' => 'INVPAY',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 5,
    ],

    'expense_payment' => [
        'prefix' => 'EXPPAY',
        'format' => '{prefix}/{org_code}/{year}/{number}',
        'reset_period' => 'yearly',
        'pad_length' => 5,
    ],

    'payroll_integration' => [
        'prefix' => 'PINT',
        'format' => '{prefix}/{org_code}/{year}{month}/{number}',
        'reset_period' => 'monthly',
        'pad_length' => 4,
    ],

    // Tabel lain (journal_entries, invoices, expenses, accounting_periods, dst)
    // sudah menggunakan pola unique per-organisasi sejak awal di skema utama,
    // jadi bisa didaftarkan di sini juga dengan pola yang sama bila ingin
    // konsisten memakai generator ini.

];
