<?php

namespace App\Http\Controllers;

use App\Models\ModulPembelajaran;
use App\Models\ModulSoal;
use App\Models\ModulSiswa;
use App\Models\Siswa;
use App\Models\Pembimbing;
use App\Contracts\HasRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembimbingModulController extends Controller
{
    protected function authorizePembimbing(): Pembimbing
    {
        $user = Auth::user();
        $role = $user instanceof HasRole ? $user->getRole() : null;
        abort_unless($user && $role === 'pembimbing', 403);
        return $user;
    }

    public function index()
    {
        $pembimbing = $this->authorizePembimbing();
        $moduls = ModulPembelajaran::with(['soals', 'modulSiswas.siswa'])
            ->where('id_pembimbing', $pembimbing->id_pembimbing)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembimbing.modul.index', compact('pembimbing', 'moduls'));
    }

    public function create()
    {
        $pembimbing = $this->authorizePembimbing();
        return view('pembimbing.modul.create', compact('pembimbing'));
    }

    public function store(Request $request)
    {
        $pembimbing = $this->authorizePembimbing();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:15360', // max 15MB
            'tipe_video' => 'required|in:youtube,upload',
            'video_youtube' => 'nullable|required_if:tipe_video,youtube|string|max:255',
            'video_file' => 'nullable|required_if:tipe_video,upload|file|mimes:mp4,mov,avi,webm|max:51200', // max 50MB
            'durasi_kuis' => 'required|integer|min:1|max:180',
            
            // Soal Quiz
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi_a' => 'required|string',
            'soal.*.opsi_b' => 'required|string',
            'soal.*.opsi_c' => 'required|string',
            'soal.*.opsi_d' => 'required|string',
            'soal.*.kunci' => 'required|in:a,b,c,d',
        ]);

        // Upload PDF
        $pdfPath = $request->file('file_pdf')->store('modul_pdf', 'public');

        // Handle Video
        $videoUrl = null;
        if ($validated['tipe_video'] === 'upload' && $request->hasFile('video_file')) {
            $videoUrl = $request->file('video_file')->store('modul_video', 'public');
        } else {
            $videoUrl = $request->input('video_youtube');
        }

        // Simpan Modul
        $modul = ModulPembelajaran::create([
            'id_pembimbing' => $pembimbing->id_pembimbing,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_pdf' => $pdfPath,
            'tipe_video' => $validated['tipe_video'],
            'video_url' => $videoUrl,
            'durasi_kuis' => $validated['durasi_kuis'],
        ]);

        // Simpan Soal
        foreach ($validated['soal'] as $item) {
            ModulSoal::create([
                'id_modul' => $modul->id_modul,
                'pertanyaan' => $item['pertanyaan'],
                'opsi_a' => $item['opsi_a'],
                'opsi_b' => $item['opsi_b'],
                'opsi_c' => $item['opsi_c'],
                'opsi_d' => $item['opsi_d'],
                'kunci_jawaban' => $item['kunci'],
            ]);
        }

        return redirect()->route('pembimbing.modul.index')->with('success', 'Modul pembelajaran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pembimbing = $this->authorizePembimbing();
        $modul = ModulPembelajaran::with(['soals', 'modulSiswas.siswa'])
            ->where('id_pembimbing', $pembimbing->id_pembimbing)
            ->where('id_modul', $id)
            ->firstOrFail();

        return view('pembimbing.modul.show', compact('pembimbing', 'modul'));
    }

    public function edit($id)
    {
        $pembimbing = $this->authorizePembimbing();
        $modul = ModulPembelajaran::with('soals')
            ->where('id_pembimbing', $pembimbing->id_pembimbing)
            ->where('id_modul', $id)
            ->firstOrFail();

        return view('pembimbing.modul.edit', compact('pembimbing', 'modul'));
    }

    public function update(Request $request, $id)
    {
        $pembimbing = $this->authorizePembimbing();
        $modul = ModulPembelajaran::where('id_pembimbing', $pembimbing->id_pembimbing)
            ->where('id_modul', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:15360', // opsional saat revisi
            'tipe_video' => 'required|in:youtube,upload',
            'video_youtube' => 'nullable|required_if:tipe_video,youtube|string|max:255',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'durasi_kuis' => 'required|integer|min:1|max:180',
            
            // Soal Quiz
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi_a' => 'required|string',
            'soal.*.opsi_b' => 'required|string',
            'soal.*.opsi_c' => 'required|string',
            'soal.*.opsi_d' => 'required|string',
            'soal.*.kunci' => 'required|in:a,b,c,d',
        ]);

        // Upload PDF baru jika ada
        if ($request->hasFile('file_pdf')) {
            if ($modul->file_pdf && Storage::disk('public')->exists($modul->file_pdf)) {
                Storage::disk('public')->delete($modul->file_pdf);
            }
            $modul->file_pdf = $request->file('file_pdf')->store('modul_pdf', 'public');
        }

        // Handle Video
        if ($validated['tipe_video'] === 'upload') {
            if ($request->hasFile('video_file')) {
                if ($modul->tipe_video === 'upload' && $modul->video_url && Storage::disk('public')->exists($modul->video_url)) {
                    Storage::disk('public')->delete($modul->video_url);
                }
                $modul->video_url = $request->file('video_file')->store('modul_video', 'public');
            }
        } else {
            $modul->video_url = $request->input('video_youtube');
        }

        $modul->judul = $validated['judul'];
        $modul->deskripsi = $validated['deskripsi'] ?? null;
        $modul->tipe_video = $validated['tipe_video'];
        $modul->durasi_kuis = $validated['durasi_kuis'];
        $modul->save();

        // Update / Re-sync Soal
        $modul->soals()->delete();
        foreach ($validated['soal'] as $item) {
            ModulSoal::create([
                'id_modul' => $modul->id_modul,
                'pertanyaan' => $item['pertanyaan'],
                'opsi_a' => $item['opsi_a'],
                'opsi_b' => $item['opsi_b'],
                'opsi_c' => $item['opsi_c'],
                'opsi_d' => $item['opsi_d'],
                'kunci_jawaban' => $item['kunci'],
            ]);
        }

        return redirect()->route('pembimbing.modul.index')->with('success', 'Modul pembelajaran berhasil direvisi!');
    }

    public function viewPdf($id)
    {
        $pembimbing = $this->authorizePembimbing();
        $modul = ModulPembelajaran::where('id_pembimbing', $pembimbing->id_pembimbing)
            ->where('id_modul', $id)
            ->firstOrFail();

        $path = storage_path('app/public/' . $modul->file_pdf);

        if (!file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }

    public function destroy($id)
    {
        $pembimbing = $this->authorizePembimbing();
        $modul = ModulPembelajaran::where('id_pembimbing', $pembimbing->id_pembimbing)
            ->where('id_modul', $id)
            ->firstOrFail();

        if ($modul->file_pdf && Storage::disk('public')->exists($modul->file_pdf)) {
            Storage::disk('public')->delete($modul->file_pdf);
        }
        if ($modul->tipe_video === 'upload' && $modul->video_url && Storage::disk('public')->exists($modul->video_url)) {
            Storage::disk('public')->delete($modul->video_url);
        }

        $modul->delete();

        return back()->with('success', 'Modul berhasil dihapus.');
    }
}
