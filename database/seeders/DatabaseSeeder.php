<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Lecturer; // <--- JANGAN LUPA IMPORT INI
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ==========================================
        // 1. Seed Admin User
        // ==========================================
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        // ==========================================
        // 2. Data Master Fakultas & Prodi
        // ==========================================
        $dataKampus = [
            [
                'code' => 'FT',
                'name' => 'Fakultas Teknik',
                'departments' => [
                    ['code' => 'TI', 'name' => 'Teknik Informatika', 'degree' => 'S1'],
                    ['code' => 'SI', 'name' => 'Sistem Informasi', 'degree' => 'S1'],
                    ['code' => 'TS', 'name' => 'Teknik Sipil', 'degree' => 'S1'],
                ]
            ],
            [
                'code' => 'FEB',
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'departments' => [
                    ['code' => 'MN', 'name' => 'Manajemen', 'degree' => 'S1'],
                    ['code' => 'AK', 'name' => 'Akuntansi', 'degree' => 'S1'],
                ]
            ],
            // ... (Fakultas lain bisa ditambah di sini)
        ];

        // 3. Eksekusi Looping Fakultas & Prodi
        foreach ($dataKampus as $fakultas) {
            $prodiList = $fakultas['departments'];
            unset($fakultas['departments']); 

            $newFaculty = Faculty::updateOrCreate(
                ['code' => $fakultas['code']], 
                ['name' => $fakultas['name']]
            );

            foreach ($prodiList as $prodi) {
                $newFaculty->departments()->updateOrCreate(
                    ['code' => $prodi['code']], 
                    [
                        'name' => $prodi['name'],
                        'degree' => $prodi['degree']
                    ]
                );
            }
        }

        // ==========================================
        // 4. SEED DATA DOSEN (LECTURER)
        // ==========================================
        $dataDosen = [
            [
                'nidn' => '00101010',
                'name' => 'Dr. Budi Santoso, M.Kom',
                'phone' => '081234567890',
                'dept_code' => 'TI', // Dosen TI
            ],
            [
                'nidn' => '00202020',
                'name' => 'Siti Aminah, M.T.',
                'phone' => '081298765432',
                'dept_code' => 'TI', // Dosen TI juga
            ],
            [
                'nidn' => '00303030',
                'name' => 'Rudi Hartono, S.E., M.M.',
                'phone' => '081333444555',
                'dept_code' => 'MN', // Dosen Manajemen
            ],
            [
                'nidn' => '00404040',
                'name' => 'Prof. Andi Wijaya, Ph.D.',
                'phone' => '081555666777',
                'dept_code' => 'SI', // Dosen SI
            ],
        ];

        foreach ($dataDosen as $dosen) {
            // a. Cari Department ID berdasarkan Kode Prodi (TI, MN, dll)
            $dept = Department::where('code', $dosen['dept_code'])->first();

            // Hanya proses jika prodi ditemukan
            if ($dept) {
                // b. Buat User Login untuk Dosen (Username pakai NIDN)
                $newUser = User::updateOrCreate(
                    ['username' => $dosen['nidn']], 
                    [
                        'email' => $dosen['nidn'] . '@campus.ac.id', // Fake Email
                        'password' => Hash::make('12345678'),
                        'role' => 'lecturer', // Pastikan kolom role support value ini
                    ]
                );

                // c. Buat Data Lecturer yang terhubung ke User & Department
                Lecturer::updateOrCreate(
                    ['nidn' => $dosen['nidn']], // Cek unik NIDN
                    [
                        'user_id' => $newUser->id,      // Relasi ke User
                        'department_id' => $dept->id,   // Relasi ke Prodi
                        'name' => $dosen['name'],
                        'phone' => $dosen['phone'],
                        'status' => 'active',
                    ]
                );
            }
        }

        $dataMataKuliah = [
            // Mata Kuliah Teknik Informatika (TI)
            [
                'code' => 'TI101',
                'name' => 'Algoritma dan Pemrograman',
                'sks' => 3,
                'semester' => 1,
                'is_active' => 1,
                'dept_code' => 'TI',
            ],
            [
                'code' => 'TI102',
                'name' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2,
                'is_active' => 1,
                'dept_code' => 'TI',
            ],
            // Mata Kuliah Sistem Informasi (SI)
            [
                'code' => 'SI101',
                'name' => 'Pengantar Sistem Informasi',
                'sks' => 2,
                'semester' => 1,
                'is_active' => 1,
                'dept_code' => 'SI',
            ],
            // Mata Kuliah Manajemen (MN)
            [
                'code' => 'MN101',
                'name' => 'Pengantar Manajemen',
                'sks' => 3,
                'semester' => 1,
                'is_active' => 1,
                'dept_code' => 'MN',
            ],
            [
                'code' => 'MN102',
                'name' => 'Matematika Ekonomi',
                'sks' => 2,
                'semester' => 1,
                'is_active' => 0, // Contoh data yang tidak aktif
                'dept_code' => 'MN',
            ],
        ];

        foreach ($dataMataKuliah as $mk) {
            // Cari Department ID berdasarkan Kode Prodi
            $dept = Department::where('code', $mk['dept_code'])->first();

            // Hanya proses jika prodi ditemukan
            if ($dept) {
                Subject::updateOrCreate(
                    ['code' => $mk['code']], // Patokan cek unik adalah kode mata kuliah
                    [
                        'department_id' => $dept->id, // Relasi ke tabel departments
                        'name' => $mk['name'],
                        'sks' => $mk['sks'],
                        'semester' => $mk['semester'],
                        'is_active' => $mk['is_active'],
                    ]
                );
            }
        }
    }
}