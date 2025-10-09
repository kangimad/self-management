<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tr_types = [
            ['name' => 'Pemasukan', 'code' => 'income', 'direction' => 'in', 'description' => 'Money received'],
            ['name' => 'Pengeluaran', 'code' => 'expense', 'direction' => 'out', 'description' => 'Money spent'],
            ['name' => 'Transfer', 'code' => 'transfer', 'direction' => 'neutral', 'description' => 'Money moved between sources'],
            ['name' => 'Koreksi Saldo', 'code' => 'adjustment', 'direction' => 'neutral', 'description' => 'Koreksi manual'],
            ['name' => 'Saldo Awal', 'code' => 'opening', 'direction' => 'in', 'description' => 'Setoran awal'],
        ];

        $src_types = [
            ['name' => 'Bank', 'code' => 'bank', 'description' => 'Bank accounts'],
            ['name' => 'Cash', 'code' => 'cash', 'description' => 'Cash on hand'],
            ['name' => 'Credit Card', 'code' => 'credit_card', 'description' => 'Credit card accounts'],
            ['name' => 'E-Wallet', 'code' => 'e_wallet', 'description' => 'E-wallets and online payment systems'],
            ['name' => 'Investment', 'code' => 'investment', 'description' => 'Investment accounts'],
        ];

        $cat_types = [
            ['name' => 'Income', 'code' => 'income', 'description' => 'Pemasukan'],
            ['name' => 'Expense', 'code' => 'expense', 'description' => 'Pengeluaran'],
        ];

        $sources=[
            ['user_id' => 1, 'source_type_id' => 1, 'name' => 'BCA', 'current_balance' => 1000000, 'description' => 'Rekening BCA'],
            ['user_id' => 1, 'source_type_id' => 2, 'name' => 'Dompet', 'current_balance' => 500000, 'description' => 'Dompet harian'],
            ['user_id' => 1, 'source_type_id' => 4, 'name' => 'OVO', 'current_balance' => 300000, 'description' => 'E-Wallet OVO'],
            ['user_id' => 1, 'source_type_id' => 3, 'name' => 'Mandiri Credit Card', 'current_balance' => 2000000, 'description' => 'Kartu kredit Mandiri'],
            ['user_id' => 1, 'source_type_id' => 5, 'name' => 'Reksa Dana', 'current_balance' => 5000000, 'description' => 'Investasi reksa dana'],
        ];

        $categories=[
            ['user_id' => 1, 'category_type_id' => 1, 'name' => 'Gaji', 'description' => 'Pemasukan Utama'],
            ['user_id' => 1, 'category_type_id' => 1, 'name' => 'Proyek', 'description' => 'Pemasukan dari proyek sampingan'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Makanan & Minuman', 'description' => 'Pengeluaran untuk kebutuhan makan dan minum'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Transportasi', 'description' => 'Pengeluaran untuk transportasi sehari-hari'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Sedekah', 'description' => 'Pengeluaran untuk sedekah atau donasi (hak orang lain)'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Hiburan', 'description' => 'Pengeluaran untuk rekreasi dan hiburan'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Tagihan & Utilitas', 'description' => 'Pengeluaran untuk tagihan bulanan dan utilitas'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Kesehatan', 'description' => 'Pengeluaran untuk kebutuhan kesehatan'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Pendidikan', 'description' => 'Pengeluaran untuk kebutuhan pendidikan'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Belanja', 'description' => 'Pengeluaran untuk kebutuhan belanja sehari-hari'],
            ['user_id' => 1, 'category_type_id' => 2, 'name' => 'Investasi', 'description' => 'Pengeluaran untuk investasi dan tabungan'],
        ];

        $allocation = [
            ['user_id' => 1, 'allocation_type' => 'category', 'category_id' => 1, 'percentage' => 10, 'target_category_id' => 5],
            ['user_id' => 1, 'allocation_type' => 'category', 'category_id' => 1, 'percentage' => 20, 'target_category_id' => null],
        ];

        $transaction=[
            ['user_id' => 1, 'transaction_type_id' => 1, 'source_id' => 1, 'target_source_id' => null, 'category_id' => 1, 'amount' => 5000000, 'date' => '2025-10-01', 'description' => 'Gaji Bulan Oktober'],
            ['user_id' => 1, 'transaction_type_id' => 2, 'source_id' => 1, 'target_source_id' => null, 'category_id' => 3, 'amount' => 30000, 'date' => '2025-10-02', 'description' => 'Sarapan'],
            ['user_id' => 1, 'transaction_type_id' => 3, 'source_id' => 1, 'target_source_id' => 2, 'category_id' => null, 'amount' => 1000000, 'date' => '2025-10-02', 'description' => 'TF ke BCA'],
        ];

        $balances=[
            ['user_id' => 1, 'source_id' => 1, 'transaction_id' => 1, 'previous_balance' => 0, 'change_amount' => 5000000, 'current_balance' => 5000000, 'description' => 'Gaji Bulan Oktober'],
            ['user_id' => 1, 'source_id' => 1, 'transaction_id' => 2, 'previous_balance' => 5000000, 'change_amount' => -30000, 'current_balance' => 4970000, 'description' => 'Sarapan'],
            ['user_id' => 1, 'source_id' => 1, 'transaction_id' => 3, 'previous_balance' => 4970000, 'change_amount' => -1000000, 'current_balance' => 3970000, 'description' => 'TF ke BCA'],
        ];

        DB::table('finance_transaction_types')->insert($tr_types);
        DB::table('finance_source_types')->insert($src_types);
        DB::table('finance_category_types')->insert($cat_types);
        DB::table('finance_sources')->insert($sources);
        DB::table('finance_categories')->insert($categories);
        DB::table('finance_allocations')->insert($allocation);
        DB::table('finance_transactions')->insert($transaction);
        DB::table('finance_balances')->insert($balances);

    }
}
