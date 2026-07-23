<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Guru|\App\Models\Pembimbing $user */
        $user = auth()->user();
        $role = $user->getRole();

        if ($role === 'guru') {
            $siswas = Siswa::where('id_guru', '=', $user->id_guru)
                ->whereNotNull('id_pembimbing')
                ->with(['pembimbing'])
                ->get();
        } elseif ($role === 'pembimbing') {
            $siswas = Siswa::where('id_pembimbing', '=', $user->id_pembimbing)
                ->whereNotNull('id_guru')
                ->with(['guru'])
                ->get();
        } else {
            abort(403);
        }

        // Hitung unread per siswa
        $receiverId = $role === 'guru' ? $user->id_guru : $user->id_pembimbing;
        
        foreach ($siswas as $siswa) {
            $siswa->unread_count = Pesan::where('nisn', '=', $siswa->nisn)
                ->where('receiver_id', '=', $receiverId)
                ->where('is_read', '=', false)
                ->count();
        }

        return view('pesan.index', compact('siswas', 'role'));
    }

    public function show($nisn)
    {
        /** @var \App\Models\Guru|\App\Models\Pembimbing $user */
        $user = auth()->user();
        $role = $user->getRole();

        if ($role === 'guru') {
            $siswa = Siswa::where('id_guru', '=', $user->id_guru)->where('nisn', '=', $nisn)->firstOrFail();
            $lawanBicara = $siswa->pembimbing;
        } elseif ($role === 'pembimbing') {
            $siswa = Siswa::where('id_pembimbing', '=', $user->id_pembimbing)->where('nisn', '=', $nisn)->firstOrFail();
            $lawanBicara = $siswa->guru;
        } else {
            abort(403);
        }

        if (!$lawanBicara) {
            return redirect()->back()->with('error', 'Lawan bicara tidak ditemukan (Siswa belum memiliki guru/pembimbing lengkap).');
        }

        // Tandai sudah dibaca
        $receiverId = $role === 'guru' ? $user->id_guru : $user->id_pembimbing;
        Pesan::where('nisn', '=', $nisn)
            ->where('receiver_id', '=', $receiverId)
            ->where('is_read', '=', false)
            ->update(['is_read' => true]);

        // Ambil pesan
        $pesans = Pesan::where('nisn', '=', $nisn)->orderBy('created_at', 'asc')->get();

        return view('pesan.show', compact('siswa', 'pesans', 'role', 'lawanBicara'));
    }

    public function store(Request $request, $nisn)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        /** @var \App\Models\Guru|\App\Models\Pembimbing $user */
        $user = auth()->user();
        $role = $user->getRole();

        if ($role === 'guru') {
            $siswa = Siswa::where('id_guru', '=', $user->id_guru)->where('nisn', '=', $nisn)->firstOrFail();
            $receiverId = $siswa->id_pembimbing;
            $receiverRole = 'pembimbing';
            $senderId = $user->id_guru;
        } elseif ($role === 'pembimbing') {
            $siswa = Siswa::where('id_pembimbing', '=', $user->id_pembimbing)->where('nisn', '=', $nisn)->firstOrFail();
            $receiverId = $siswa->id_guru;
            $receiverRole = 'guru';
            $senderId = $user->id_pembimbing;
        } else {
            abort(403);
        }

        Pesan::create([
            'nisn' => $nisn,
            'sender_role' => $role,
            'sender_id' => $senderId,
            'receiver_role' => $receiverRole,
            'receiver_id' => $receiverId,
            'pesan' => $request->pesan,
        ]);

        return redirect()->back();
    }
}
