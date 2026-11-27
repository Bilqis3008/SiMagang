<?php

namespace App\Http\Controllers;

use App\Models\ModulPembelajaran;
use App\Models\ModulSiswa;
use App\Models\ModulSoal;
use App\Models\Siswa;
use App\Contracts\HasRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaModulController extends Controller
{
    protected function authorizeSiswa(): Siswa
    {
        $user = Auth::user();
        $role = $user instanceof HasRole ? $user->getRole() : null;
        abort_unless($user && $role === 'siswa', 403);
        return $user;
    }

    /**
     * Memeriksa apakah tanggal saat ini berada dalam rentang akses modul.
     */
    protected function checkModulDateAccess(ModulSiswa $modulSiswa): ?string
    {
        $today = Carbon::today()->toDateString();

        if ($modulSiswa->tgl_mulai && $today < $modulSiswa->tgl_mulai) {
            $formatted = Carbon::parse($modulSiswa->tgl_mulai)->format('d/m/Y');
            return "Modul pembelajaran ini belum dapat dikerjakan. Akses baru dibuka pada tanggal {$formatted}.";
        }

        if ($modulSiswa->tgl_selesai && $today > $modulSiswa->tgl_selesai) {
            if (!$modulSiswa->kuis_selesai) {
                $formatted = Carbon::parse($modulSiswa->tgl_selesai)->format('d/m/Y');
                return "Batas waktu pengerjaan modul pembelajaran ini telah berakhir pada tanggal {$formatted}. Anda tidak dapat lagi mengerjakan modul ini.";
            }
        }

        return null;
    }

    public function index()
    {
        $siswa = $this->authorizeSiswa();
        
        // Ambil semua modul yang ditugaskan kepada siswa ini
        $modulSiswas = ModulSiswa::with(['modul.pembimbing', 'modul.soals'])
            ->where('nisn', $siswa->nisn)
            ->orderBy('id_modul_siswa', 'desc')
            ->get();

        return view('siswa.modul.index', compact('siswa', 'modulSiswas'));
    }

    public function belajar($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::with(['modul.pembimbing', 'modul.soals'])
            ->where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError && !$modulSiswa->kuis_selesai) {
            return redirect()->route('siswa.modul.index')->with('warning', $dateError);
        }

        return view('siswa.modul.belajar', compact('siswa', 'modulSiswa'));
    }

    public function viewPdf($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::with('modul')
            ->where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError && !$modulSiswa->kuis_selesai) {
            abort(403, $dateError);
        }

        $path = storage_path('app/public/' . $modulSiswa->modul->file_pdf);

        if (!file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }

    public function selesaikanPdf($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError) {
            return redirect()->route('siswa.modul.index')->with('warning', $dateError);
        }

        $modulSiswa->update(['pdf_selesai' => true]);

        return redirect()->route('siswa.modul.belajar', $id_modul_siswa)->with('success', 'Materi PDF telah diselesaikan! Silakan lanjutkan menonton Video Modul.');
    }

    public function selesaikanVideo($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError) {
            return redirect()->route('siswa.modul.index')->with('warning', $dateError);
        }

        $modulSiswa->update(['video_selesai' => true]);

        return redirect()->route('siswa.modul.belajar', $id_modul_siswa)->with('success', 'Video modul telah diselesaikan! Tahap terakhir adalah Kuis Pemahaman.');
    }

    public function kuis($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::with(['modul.soals'])
            ->where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError && !$modulSiswa->kuis_selesai) {
            return redirect()->route('siswa.modul.index')->with('warning', $dateError);
        }

        // Pastikan tahap PDF dan Video sudah selesai
        if (!$modulSiswa->pdf_selesai || !$modulSiswa->video_selesai) {
            return redirect()->route('siswa.modul.belajar', $id_modul_siswa)->with('warning', 'Harap selesaikan materi PDF dan Video terlebih dahulu!');
        }

        // Catat waktu mulai kuis jika belum pernah dicatat
        if (!$modulSiswa->kuis_dimulai_pada) {
            $modulSiswa->update(['kuis_dimulai_pada' => Carbon::now()]);
        }

        $durasiMenit = $modulSiswa->modul->durasi_kuis ?? 15;
        $waktuMulai = Carbon::parse($modulSiswa->kuis_dimulai_pada);
        $waktuBerakhir = $waktuMulai->copy()->addMinutes($durasiMenit);
        $sisaDetik = max(0, Carbon::now()->diffInSeconds($waktuBerakhir, false));

        // Jika kuis sudah selesai, redirect ke halaman hasil
        if ($modulSiswa->kuis_selesai) {
            return redirect()->route('siswa.modul.hasil', $id_modul_siswa);
        }

        $soals = $modulSiswa->modul->soals;

        return view('siswa.modul.kuis', compact('siswa', 'modulSiswa', 'soals', 'sisaDetik', 'durasiMenit'));
    }

    public function submitKuis(Request $request, $id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::with(['modul.soals'])
            ->where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        $dateError = $this->checkModulDateAccess($modulSiswa);
        if ($dateError && !$modulSiswa->kuis_selesai) {
            return redirect()->route('siswa.modul.index')->with('warning', $dateError);
        }

        if ($modulSiswa->kuis_selesai) {
            return redirect()->route('siswa.modul.hasil', $id_modul_siswa);
        }

        $soals = $modulSiswa->modul->soals;
        $totalSoal = $soals->count();
        $jawabanUser = $request->input('jawaban', []); // [id_soal => 'a']

        $benar = 0;
        foreach ($soals as $soal) {
            $userAns = $jawabanUser[$soal->id_soal] ?? null;
            if ($userAns && strtolower($userAns) === strtolower($soal->kunci_jawaban)) {
                $benar++;
            }
        }

        $skor = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 2) : 100;

        $modulSiswa->update([
            'kuis_selesai' => true,
            'nilai_kuis' => $skor,
            'kuis_selesai_pada' => Carbon::now(),
        ]);

        return redirect()->route('siswa.modul.hasil', $id_modul_siswa)->with('success', 'Kuis berhasil diselesaikan!');
    }

    public function hasilKuis($id_modul_siswa)
    {
        $siswa = $this->authorizeSiswa();
        $modulSiswa = ModulSiswa::with(['modul.soals'])
            ->where('nisn', $siswa->nisn)
            ->where('id_modul_siswa', $id_modul_siswa)
            ->firstOrFail();

        return view('siswa.modul.hasil', compact('siswa', 'modulSiswa'));
    }
}
