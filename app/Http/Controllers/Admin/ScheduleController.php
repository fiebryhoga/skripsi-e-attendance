<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Halaman Utama: Menampilkan Daftar Kelas untuk dipilih
    public function index()
    {
        $classrooms = Classroom::withCount('students')->orderBy('name')->get();
        return view('admin.schedules.index', compact('classrooms'));
    }

    public function show(Classroom $classroom)
    {
        // Urutkan berdasarkan Hari, lalu jam mulai terkecil
        $schedules = Schedule::with(['subject', 'teacher'])
                        ->where('classroom_id', $classroom->id)
                        ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                        ->orderBy('jam_mulai')
                        ->get()
                        ->groupBy('day');

        $subjects = Subject::orderBy('name')->get();
        
        // Ambil guru
        $teachers = User::all()->filter(function ($user) {
            return $user->hasRole(UserRole::GURU_MAPEL) || $user->hasRole(UserRole::WALI_KELAS);
        })->sortBy('name')->values(); // values() penting agar index array reset untuk JS

        return view('admin.schedules.show', compact('classroom', 'schedules', 'subjects', 'teachers'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id'    => 'required|exists:users,id',
            'day'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'  => 'required|integer|min:1|max:15',
            'jam_selesai'=> 'required|integer|gte:jam_mulai|max:15', // gte: Greater Than or Equal
        ]);

        // LOGIKA CEK BENTROK GURU (PENTING)
        // Kita cek apakah guru ini sudah mengajar di kelas lain pada rentang jam tersebut?
        $bentrokGuru = Schedule::where('user_id', $request->user_id)
            ->where('day', $request->day)
            ->where(function($q) use ($request) {
                // Logika irisan jadwal (Overlap Logic)
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function($sub) use ($request) {
                      $sub->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })
            ->first();

        if ($bentrokGuru) {
            return back()->with('error', "Gagal! Guru tersebut sedang mengajar di kelas {$bentrokGuru->classroom->name} pada jam ke {$bentrokGuru->jam_mulai}-{$bentrokGuru->jam_selesai}.");
        }

        // LOGIKA CEK BENTROK KELAS (Opsional)
        // Cek apakah kelas INI sudah ada pelajaran di jam tersebut?
        $bentrokKelas = Schedule::where('classroom_id', $classroom->id)
            ->where('day', $request->day)
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })
            ->first();

        if ($bentrokKelas) {
             return back()->with('error', "Gagal! Kelas ini sudah ada pelajaran {$bentrokKelas->subject->name} pada jam tersebut.");
        }

        Schedule::create([
            'classroom_id' => $classroom->id,
            'subject_id'   => $request->subject_id,
            'user_id'      => $request->user_id,
            'day'          => $request->day,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}