<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Notifications\DataChangedNotification; 
use Illuminate\Support\Facades\Notification; 
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    
    private function getNotificationRecipients($schedule)
    {
        
        $recipients = User::whereJsonContains('roles', UserRole::ADMIN->value)->get();
        
        
        if ($schedule->user_id) {
            $teacher = User::find($schedule->user_id);
            if ($teacher) {
                $recipients->push($teacher);
            }
        }

        
        return $recipients->unique('id');
    }

    public function index()
    {
        $classrooms = Classroom::withCount('students')->orderBy('name')->get();
        return view('admin.schedules.index', compact('classrooms'));
    }

    public function show(Classroom $classroom)
    {
        $schedules = Schedule::with(['subject', 'teacher'])
                        ->where('classroom_id', $classroom->id)
                        ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                        ->orderBy('jam_mulai')
                        ->get()
                        ->groupBy('day');

        $subjects = Subject::orderBy('name')->get();
        
        $teachers = User::where(function($q) {
            $q->whereJsonContains('roles', UserRole::GURU_MAPEL->value)
              ->orWhereJsonContains('roles', UserRole::WALI_KELAS->value);
        })->orderBy('name')->get();

        return view('admin.schedules.show', compact('classroom', 'schedules', 'subjects', 'teachers'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id'    => 'required|exists:users,id',
            'day'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'  => 'required|integer|min:1|max:15',
            'jam_selesai'=> 'required|integer|gte:jam_mulai|max:15',
        ]);

        
        $bentrokGuru = $this->checkTeacherClash($request->user_id, $request->day, $request->jam_mulai, $request->jam_selesai);
        if ($bentrokGuru) {
            return back()->with('error', "Gagal! Guru tersebut sedang mengajar di kelas {$bentrokGuru->classroom->name} jam ke {$bentrokGuru->jam_mulai}-{$bentrokGuru->jam_selesai}.");
        }

        
        $bentrokKelas = $this->checkClassClash($classroom->id, $request->day, $request->jam_mulai, $request->jam_selesai);
        if ($bentrokKelas) {
             return back()->with('error', "Gagal! Kelas ini sudah ada pelajaran pada jam tersebut.");
        }

        $schedule = Schedule::create([
            'classroom_id' => $classroom->id,
            'subject_id'   => $request->subject_id,
            'user_id'      => $request->user_id,
            'day'          => $request->day,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        
        $recipients = $this->getNotificationRecipients($schedule);
        Notification::send($recipients, new DataChangedNotification(
            'Jadwal Baru Ditambahkan: Kelas ' . $classroom->name . ' - ' . $schedule->subject->name . ' (' . $schedule->day . ')'
        ));

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id'    => 'required|exists:users,id',
            'day'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'  => 'required|integer|min:1|max:15',
            'jam_selesai'=> 'required|integer|gte:jam_mulai|max:15',
        ]);

        
        $bentrokGuru = $this->checkTeacherClash($request->user_id, $request->day, $request->jam_mulai, $request->jam_selesai, $schedule->id);
        if ($bentrokGuru) {
            return back()->with('error', "Gagal Edit! Guru bentrok dengan kelas {$bentrokGuru->classroom->name}.");
        }

        
        $bentrokKelas = $this->checkClassClash($schedule->classroom_id, $request->day, $request->jam_mulai, $request->jam_selesai, $schedule->id);
        if ($bentrokKelas) {
             return back()->with('error', "Gagal Edit! Jam tersebut sudah terisi di kelas ini.");
        }

        $schedule->update([
            'subject_id'   => $request->subject_id,
            'user_id'      => $request->user_id,
            'day'          => $request->day,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        
        $recipients = $this->getNotificationRecipients($schedule);
        Notification::send($recipients, new DataChangedNotification(
            'Jadwal Diperbarui: Kelas ' . $schedule->classroom->name . ' - ' . $schedule->subject->name
        ));

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $info = $schedule->classroom->name . ' - ' . $schedule->subject->name;
        
        
        $recipients = $this->getNotificationRecipients($schedule);

        $schedule->delete();

        
        Notification::send($recipients, new DataChangedNotification(
            'Jadwal Dihapus: ' . $info,
            'danger'
        ));

        return back()->with('success', 'Jadwal dihapus.');
    }

    
    
    private function checkTeacherClash($userId, $day, $start, $end, $ignoreId = null)
    {
        return Schedule::where('user_id', $userId)
            ->where('day', $day)
            ->when($ignoreId, function($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('jam_mulai', [$start, $end])
                  ->orWhereBetween('jam_selesai', [$start, $end])
                  ->orWhere(function($sub) use ($start, $end) {
                      $sub->where('jam_mulai', '<=', $start)
                          ->where('jam_selesai', '>=', $end);
                  });
            })
            ->first();
    }

    private function checkClassClash($classId, $day, $start, $end, $ignoreId = null)
    {
        return Schedule::where('classroom_id', $classId)
            ->where('day', $day)
            ->when($ignoreId, function($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('jam_mulai', [$start, $end])
                  ->orWhereBetween('jam_selesai', [$start, $end]);
            })
            ->first();
    }
}