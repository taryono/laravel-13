<?php

namespace Lib;
// ============================================
// PHP VERSION - GENERATE PERMUTATIONS
// ============================================

class FeePermutationGenerator
{
    /**
     * Generate semua kombinasi parameter
     */
    public static function generatePermutations($params)
    {
        $keys = array_keys($params);
        $count = count($keys);
        $permutations = [];
        
        // 2^n kombinasi
        for ($i = 0; $i < pow(2, $count); $i++) {
            $combination = [];
            foreach ($keys as $index => $key) {
                // Bitwise operation: jika bit ke-$index adalah 1, gunakan nilai asli
                $combination[$key] = ($i & (1 << $index)) ? $params[$key] : null;
            }
            $permutations[] = $combination;
        }
        
        return $permutations;
    }
    
    /**
     * Cari fee yang match dengan semua kombinasi
     */
    public static function findMatchingFees($params)
    {
        $permutations = self::generatePermutations($params);
        $results = [];
        
        // Build query dengan OR conditions
        $query = DB::table('fee_masters as fm')
            ->join('fee_periods as fp', 'fm.id', '=', 'fp.fee_master_id')
            ->where('fm.is_active', 1)
            ->where('fp.is_active', 1);
        
        // Untuk setiap kombinasi, buat OR condition
        $query->where(function($q) use ($permutations) {
            foreach ($permutations as $index => $perm) {
                $q->orWhere(function($subQ) use ($perm) {
                    foreach ($perm as $key => $value) {
                        if ($value !== null) {
                            $subQ->where('fm.' . $key, $value);
                        }
                    }
                });
            }
        });
        
        return $query->get();
    }
    
    /**
     * Optimasi: Gunakan cache untuk hasil
     */
    public static function getCachedResult($params)
    {
        $hash = md5(json_encode($params));
        
        // Cek cache di database
        $cached = DB::table('fee_permutation_cache')
            ->where('param_hash', $hash)
            ->where('expires_at', '>', now())
            ->first();
            
        if ($cached) {
            return json_decode($cached->fee_master_ids, true);
        }
        
        // Hitung dan cache
        $results = self::findMatchingFees($params);
        
        $feeIds = $results->pluck('id')->unique()->values()->toArray();
        $totalAmount = $results->sum('period_amount');
        
        DB::table('fee_permutation_cache')->insert([
            'param_hash' => $hash,
            'fee_master_ids' => json_encode($feeIds),
            'total_amount' => $totalAmount,
            'param_data' => json_encode($params),
            'expires_at' => now()->addDays(30),
            'created_at' => now()
        ]);
        
        return $results;
    }
}

// ============================================
// CONTOH PENGGUNAAN
// ============================================

// 1. Siapkan parameter
$params = [
    'academic_calendar_id' => 'uuid1',
    'job_type_id' => 'uuid2',
    'applicant_category_id' => 'uuid3',
    'class_type_id' => 'uuid4',
    'class_level_id' => 'uuid5',
    'year' => 2026,
    'boarding_status' => 1,
    'sex' => 'MALE',
    'boarding_status_origin' => 0,
    'semester_number' => 1
];

// 2. Cari fee
$fees = FeePermutationGenerator::getCachedResult($params);

// 3. Format hasil
foreach ($fees->groupBy('fee_name') as $feeName => $items) {
    echo "Nama Biaya: " . $feeName . "\n";
    echo "Total: Rp " . number_format($items->sum('period_amount')) . "\n";
    echo "---\n";
}