<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\PenggajianController;

class SalaryCalculationTest extends TestCase
{
    /**
     * UNIT TESTING: Menguji fungsi hitungGaji() dengan input angka normal.
     */
    public function test_hitung_gaji_input_normal(): void
    {
        $controller = new PenggajianController();

        // Gaji Pokok: 5.000.000, Tunjangan: 1.000.000, Potongan: 500.000
        // Expected: (5.000.000 + 1.000.000) - 500.000 = 5.500.000
        $hasil = $controller->hitungGaji(5000000, 1000000, 500000);

        $this->assertEquals(5500000, $hasil);
    }

    /**
     * UNIT TESTING: Menguji fungsi hitungGaji() dengan input angka nol.
     */
    public function test_hitung_gaji_input_nol(): void
    {
        $controller = new PenggajianController();

        // Gaji Pokok: 0, Tunjangan: 0, Potongan: 0
        // Expected: (0 + 0) - 0 = 0
        $hasil = $controller->hitungGaji(0, 0, 0);

        $this->assertEquals(0, $hasil);
    }

    /**
     * UNIT TESTING: Menguji batas bawah perhitungan agar gaji bersih tidak bernilai negatif.
     */
    public function test_hitung_gaji_potongan_melebihi_total(): void
    {
        $controller = new PenggajianController();

        // Gaji Pokok: 2.000.000, Tunjangan: 500.000, Potongan: 3.000.000 (Melebihi total)
        // Expected: 0 (bukan -500.000)
        $hasil = $controller->hitungGaji(2000000, 500000, 3000000);

        $this->assertEquals(0, $hasil);
    }
}
