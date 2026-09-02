<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Ambil Department
            |--------------------------------------------------------------------------
            |
            | Sesuaikan nama department dengan data yang ada di database Anda.
            |
            */

            $itDepartment = DB::table('departments')
                ->where('code', 'IT-PST')
                ->value('id');

            $hrDepartment = DB::table('departments')
                ->where('code', 'HR-PST')
                ->value('id');

            $financeDepartment = DB::table('departments')
                ->where('code', 'FIN-PST')
                ->value('id');


            /*
            |--------------------------------------------------------------------------
            | Ambil Position
            |--------------------------------------------------------------------------
            */

            $managerPosition = DB::table('positions')
                ->where('code', 'supervisor')
                ->value('id');

            $staffPosition = DB::table('positions')
                ->where('code', 'school_principal')
                ->value('id');

            $developerPosition = DB::table('positions')
                ->where('code', 'teacher')
                ->value('id');


            /*
            |--------------------------------------------------------------------------
            | Employee #1
            | Manager / Supervisor
            |--------------------------------------------------------------------------
            */

            $managerId = DB::table('employees')->insertGetId([
                'nik' => '3273010101900001',
                'employee_number' => 'EMP-0001',

                'user_id' => null,

                'department_id' => $itDepartment,
                'position_id' => $managerPosition,

                // Karena dia atasan tertinggi
                'supervisor_id' => null,

                'name' => 'Budi Santoso',
                'nick_name' => 'Budi',

                'gender' => 'L',

                'birth_place' => 'Bandung',
                'birth_date' => '1990-01-01',

                'address' => 'Jl. Merdeka No. 10, Bandung',

                'phone' => '081234567890',
                'email' => 'budi@example.com',

                'join_date' => '2020-01-10',
                'resign_date' => null,

                'employment_status' => 'tetap',

                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Budi Santoso',

                'npwp' => '12.345.678.9-123.000',

                'bpjs_ketenagakerjaan' => 'BPJSTK000001',
                'bpjs_kesehatan' => 'BPJSKES000001',

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Employee #2
            | Developer di bawah Manager
            |--------------------------------------------------------------------------
            */

            DB::table('employees')->insert([
                'nik' => '3273010201920002',
                'employee_number' => 'EMP-0002',

                'user_id' => null,

                'department_id' => $itDepartment,
                'position_id' => $developerPosition,

                'supervisor_id' => $managerId,

                'name' => 'Andi Wijaya',
                'nick_name' => 'Andi',

                'gender' => 'L',

                'birth_place' => 'Jakarta',
                'birth_date' => '1992-05-15',

                'address' => 'Jl. Sudirman No. 20, Jakarta',

                'phone' => '081234567891',
                'email' => 'andi@example.com',

                'join_date' => '2021-03-01',
                'resign_date' => null,

                'employment_status' => 'tetap',

                'bank_name' => 'Mandiri',
                'bank_account_number' => '1234567891',
                'bank_account_name' => 'Andi Wijaya',

                'npwp' => '12.345.678.9-124.000',

                'bpjs_ketenagakerjaan' => 'BPJSTK000002',
                'bpjs_kesehatan' => 'BPJSKES000002',

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Employee #3
            | HR
            |--------------------------------------------------------------------------
            */

            DB::table('employees')->insert([
                'nik' => '3273014301940003',
                'employee_number' => 'EMP-0003',

                'user_id' => null,

                'department_id' => $hrDepartment,
                'position_id' => $staffPosition,

                'supervisor_id' => null,

                'name' => 'Siti Rahmawati',
                'nick_name' => 'Siti',

                'gender' => 'P',

                'birth_place' => 'Yogyakarta',
                'birth_date' => '1994-08-20',

                'address' => 'Jl. Malioboro No. 5, Yogyakarta',

                'phone' => '081234567892',
                'email' => 'siti@example.com',

                'join_date' => '2022-06-15',
                'resign_date' => null,

                'employment_status' => 'kontrak',

                'bank_name' => 'BNI',
                'bank_account_number' => '1234567892',
                'bank_account_name' => 'Siti Rahmawati',

                'npwp' => null,

                'bpjs_ketenagakerjaan' => 'BPJSTK000003',
                'bpjs_kesehatan' => 'BPJSKES000003',

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Employee #4
            | Finance
            |--------------------------------------------------------------------------
            */

            DB::table('employees')->insert([
                'nik' => '3273010101960004',
                'employee_number' => 'EMP-0004',

                'user_id' => null,

                'department_id' => $financeDepartment,
                'position_id' => $staffPosition,

                'supervisor_id' => null,

                'name' => 'Rizky Pratama',
                'nick_name' => 'Rizky',

                'gender' => 'L',

                'birth_place' => 'Semarang',
                'birth_date' => '1996-11-12',

                'address' => 'Jl. Pemuda No. 8, Semarang',

                'phone' => '081234567893',
                'email' => 'rizky@example.com',

                'join_date' => '2023-01-05',
                'resign_date' => null,

                'employment_status' => 'probation',

                'bank_name' => 'BCA',
                'bank_account_number' => '1234567893',
                'bank_account_name' => 'Rizky Pratama',

                'npwp' => null,

                'bpjs_ketenagakerjaan' => null,
                'bpjs_kesehatan' => null,

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}