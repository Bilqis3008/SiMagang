<?php

namespace App\Http\Controllers;

use App\Contracts\HasRole;
use App\Mail\AccountCreatedMail;
use App\Models\Admin;
use App\Models\Pembimbing;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\InformasiDashboard;
use App\Models\ProgramStudi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        $periodeOptions = \App\Models\TahunAjaran::getOtomatis();
        $pembimbings = \App\Models\Pembimbing::orderBy('nama')->get();
        return view('auth.register', compact('periodeOptions', 'pembimbings'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas' => ['required', 'string', 'max:50'],
            'jurusan' => ['required', 'string', 'max:100'],
            'sekolah' => ['required', 'string', 'max:150'],
            'id_tahun_ajaran' => ['required', 'exists:tahun_ajaran,id_tahun_ajaran'],
            'tipe_magang' => ['required', 'in:individu,kelompok'],
            'nisn_ketua' => ['nullable', 'string', 'max:20'],
            'id_pembimbing' => ['nullable', 'exists:pembimbing,id_pembimbing'],
            'tgl_mulai_magang' => ['nullable', 'date'],
            'tgl_selesai_magang' => ['nullable', 'date'],
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $validated['perusahaan'] = 'BPS Kabupaten Muara Enim';
        $validated['status'] = 'aktif';

        \App\Models\Siswa::create($validated);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk dengan email dan kata sandi Anda.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is verified (aktif)
            // Admins, Pimpinan, or Pembimbing might have different status names or no status column
            // We only check for Siswa and Guru according to request
            if (method_exists($user, 'getRole')) {
                $role = $user->getRole();
                if ($role === 'siswa') {
                    if (!in_array($user->status, ['aktif', 'selesai'])) {
                        Auth::logout();
                        return back()->withErrors(['email' => 'Akun Anda belum diverifikasi atau dinonaktifkan.']);
                    }
                }
            }

            $request->session()->regenerate();
            $role = $user instanceof HasRole ? $user->getRole() : null;

            return match ($role) {
                'siswa' => redirect()->route('siswa.siswa'),
                'pembimbing' => redirect()->route('pembimbing.pembimbing'),
                'admin' => redirect()->route('admin.admin'),
                default => redirect()->route('login'),
            };
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Redirect ke halaman sesuai peran setelah login.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user instanceof HasRole ? $user->getRole() : null;
        return match ($role) {
            'siswa' => redirect()->route('siswa.siswa'),
            'pembimbing' => redirect()->route('pembimbing.pembimbing'),
            'admin' => redirect()->route('admin.admin'),
            default => redirect()->route('login'),
        };
    }

    public function siswa()
    {
        $user = Auth::user();
        $role = $user instanceof HasRole ? $user->getRole() : null;
        abort_unless($user && $role === 'siswa', 403);
        return view('siswa.siswa', ['user' => $user]);
    }



    public function pembimbing()
    {
        $user = Auth::user();
        $role = $user instanceof HasRole ? $user->getRole() : null;
        abort_unless($user && $role === 'pembimbing', 403);
        
        $informasi = InformasiDashboard::getInstance();
        $programStudis = ProgramStudi::where('aktif', true)->orderBy('urutan')->get();

        $today = Carbon::today()->toDateString();
        $allSiswas = Siswa::where('id_pembimbing', $user->id_pembimbing)
            ->with([
                'modulSiswas',
                'absensis' => fn($q) => $q->whereDate('tanggal', $today),
                'penilaians' => fn($q) => $q->where('pemberi_nilai', 'Dosen Pembimbing'),
            ])
            ->get();

        $totalSiswa = $allSiswas->count();
        $siswasActive = $allSiswas->filter(function($s) use ($today) {
            $rawStatus = $s->getRawOriginal('status');
            $isSelesai = $rawStatus === 'selesai' || ($s->penilaians->isNotEmpty() && $s->tgl_selesai_magang && $s->tgl_selesai_magang < $today);
            return !$isSelesai;
        });
        $totalSiswaAktif = $siswasActive->count();
        $totalSiswaSelesai = $totalSiswa - $totalSiswaAktif;

        // Hitung progres magang siswa aktif berdasarkan rentang tanggal magang
        $now = Carbon::now();
        $totalProgressSum = 0;
        foreach ($siswasActive as $s) {
            $prog = 0;
            if ($s->tgl_mulai_magang && $s->tgl_selesai_magang) {
                $start = Carbon::parse($s->tgl_mulai_magang);
                $end = Carbon::parse($s->tgl_selesai_magang);
                if ($now->lt($start)) {
                    $prog = 0;
                } elseif ($now->gt($end)) {
                    $prog = 100;
                } else {
                    $totalDays = max(1, $start->diffInDays($end));
                    $daysPassed = (int) $start->diffInDays($now);
                    $prog = min(100, round(($daysPassed / $totalDays) * 100));
                }
            }
            $s->progress_percent = $prog;
            $totalProgressSum += $prog;
        }

        $avgProgress = $totalSiswaAktif > 0 ? round($totalProgressSum / $totalSiswaAktif) : 0;

        // Statistik Modul Pembelajaran
        $nisnList = $allSiswas->pluck('nisn')->toArray();
        $totalModulDibuat = \App\Models\ModulPembelajaran::where('id_pembimbing', $user->id_pembimbing)->count();
        $totalModulDitugaskan = \App\Models\ModulSiswa::where('id_pembimbing', $user->id_pembimbing)->count();
        $modulSelesai = \App\Models\ModulSiswa::where('id_pembimbing', $user->id_pembimbing)->where('kuis_selesai', true)->count();
        $modulBelumSelesai = max(0, $totalModulDitugaskan - $modulSelesai);
        $modulProgressRate = $totalModulDitugaskan > 0 ? round(($modulSelesai / $totalModulDitugaskan) * 100) : 0;
        $avgNilaiKuis = \App\Models\ModulSiswa::where('id_pembimbing', $user->id_pembimbing)
            ->where('kuis_selesai', true)
            ->whereNotNull('nilai_kuis')
            ->avg('nilai_kuis');
        $avgNilaiKuis = $avgNilaiKuis ? round($avgNilaiKuis, 1) : 0;

        // Logbook & Absensi
        $logbookPending = \App\Models\Logbook::whereIn('nisn', $nisnList)->where('status', 'pending')->count();
        $logbookVerified = \App\Models\Logbook::whereIn('nisn', $nisnList)->where('status', 'verified')->count();
        $absensiPending = \App\Models\Absensi::whereIn('nisn', $nisnList)->where('verifikasi', 'pending')->count();
        $hadirHariIni = \App\Models\Absensi::whereIn('nisn', $nisnList)
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        // Tren Absensi 7 Hari Terakhir
        $grafikAbsensi = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $label = $date->translatedFormat('d M');
            $hadirCount = \App\Models\Absensi::whereIn('nisn', $nisnList)
                ->whereDate('tanggal', $dateStr)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();
            $izinCount = \App\Models\Absensi::whereIn('nisn', $nisnList)
                ->whereDate('tanggal', $dateStr)
                ->whereIn('status', ['izin', 'sakit'])
                ->count();
            $grafikAbsensi[] = [
                'tanggal' => $dateStr,
                'label'   => $label,
                'hadir'   => $hadirCount,
                'izin'    => $izinCount,
            ];
        }

        $stats = [
            'total_siswa'            => $totalSiswa,
            'siswa_aktif'            => $totalSiswaAktif,
            'siswa_selesai'          => $totalSiswaSelesai,
            'avg_progress'           => $avgProgress,
            'total_modul_dibuat'     => $totalModulDibuat,
            'total_modul_ditugaskan' => $totalModulDitugaskan,
            'modul_selesai'          => $modulSelesai,
            'modul_belum_selesai'    => $modulBelumSelesai,
            'modul_progress_rate'    => $modulProgressRate,
            'avg_nilai_kuis'         => $avgNilaiKuis,
            'logbook_pending'        => $logbookPending,
            'logbook_verified'       => $logbookVerified,
            'absensi_pending'        => $absensiPending,
            'hadir_hari_ini'         => $hadirHariIni,
            'grafik_absensi'         => $grafikAbsensi,
            'siswas_top'             => $siswasActive->sortByDesc('progress_percent')->take(5),
        ];

        return view('pembimbing.pembimbing', compact('user', 'informasi', 'programStudis', 'stats'));
    }

    public function admin()
    {
        $user = Auth::user();
        $role = $user instanceof HasRole ? $user->getRole() : null;
        abort_unless($user && $role === 'admin', 403);

        $informasi = InformasiDashboard::getInstance();
        $programStudis = ProgramStudi::where('aktif', true)->orderBy('urutan')->get();

        return view('admin.admin', compact('user', 'informasi', 'programStudis'));
    }

    public function adminProfil()
    {
        $user = Auth::user();
        abort_unless($user && $user->getRole() === 'admin', 403);
        return view('admin.profil', compact('user'));
    }

    public function updateAdminProfil(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->getRole() === 'admin', 403);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $user->id_admin . ',id_admin',
        ]);

        \App\Models\Admin::query()->where('id_admin', $user->id_admin)->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAdminPassword(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->getRole() === 'admin', 403);
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        \App\Models\Admin::query()->where('id_admin', $user->id_admin)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
