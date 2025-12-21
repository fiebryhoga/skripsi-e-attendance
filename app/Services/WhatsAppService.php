<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:3000';
    }

    // Format Pesan untuk ORANG TUA
    public function formatAttendanceMessage($studentName, $status, $mapel, $hariTanggal, $infoJam)
    {
        return "Halo Bapak/Ibu Wali Murid,\n\n" .
               "Diberitahukan bahwa siswa a.n *$studentName* " .
               "tercatat *$status* pada mata pelajaran *$mapel*.\n\n" .
               "Detail:\n" .
               "🗓 $hariTanggal\n" .
               "⏰ $infoJam\n\n" . // <--- Disini nanti muncul "Jam ke 1 - 3"
               "Mohon konfirmasinya kepada pihak sekolah. Terima kasih.\n" .
               "- Sistem Akademik Sekolah";
    }

    // Format Pesan untuk WALI KELAS
    public function formatTeacherMessage($studentName, $status, $classroomName, $mapel, $hariTanggal, $infoJam)
    {
        return "⚠️ *Laporan Presensi Siswa*\n\n" .
               "Yth. Wali Kelas *$classroomName*,\n" .
               "Siswa a.n *$studentName* tercatat *$status* pada mapel *$mapel*.\n\n" .
               "🗓 $hariTanggal\n" .
               "⏰ $infoJam\n\n" .
               "Mohon ditindaklanjuti.";
    }



    



    /**
     * Format Pesan PELANGGARAN untuk ORANG TUA
     */
    public function formatViolationMessage($studentName, $deskripsi, $kode, $date, $note)
    {
        return "⚠️ *PEMBERITAHUAN PELANGGARAN*\n\n" .
               "Yth. Bapak/Ibu Wali Murid,\n" .
               "Diberitahukan bahwa siswa a.n *$studentName* telah melakukan pelanggaran tata tertib sekolah.\n\n" .
               "Detail Pelanggaran:\n" .
               "⚖️ Poin/Kode: $kode\n" . 
               "⛔ Jenis: $deskripsi\n" .
               "🗓 Tanggal: $date\n" .
               "📝 Catatan: $note\n\n" .
               "Mohon pembinaan dari Bapak/Ibu di rumah. Terima kasih.";
    }

    /**
     * Format Pesan PELANGGARAN untuk WALI KELAS
     * (Sesuai request format Anda)
     */
    public function formatViolationTeacherMessage($studentName, $classroomName, $deskripsi, $kode, $date)
    {
        return "⚠️ *Laporan Pelanggaran Siswa*\n\n" .
               "Yth. Wali Kelas *$classroomName*,\n" .
               "Siswa Anda a.n *$studentName* baru saja tercatat melakukan pelanggaran.\n\n" .
               "⚖️ Poin: $kode\n" .
               "⛔ Jenis: $deskripsi\n" .
               "🗓 Tanggal: $date\n\n" .
               "Mohon perhatian dan tindak lanjutnya.";
    }





    public function send($number, $message)
    {
        if (empty($number) || $number == '-' || strlen($number) < 5) {
            return false;
        }

        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/send-message", [
                'number' => $number,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }
            
            Log::error("WA Fail to $number: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("WA Error: " . $e->getMessage());
            return false;
        }
    }
}