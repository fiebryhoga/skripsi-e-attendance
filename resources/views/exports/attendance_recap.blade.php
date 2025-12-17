<table>
    {{-- BARIS 1-4: KOP SURAT (Dimerge otomatis oleh Export Class) --}}
    <thead>
        <tr><td colspan="5">PEMERINTAH PROVINSI JAWA TIMUR</td></tr>
        <tr><td colspan="5">DINAS PENDIDIKAN</td></tr>
        <tr><td colspan="5">SMA NEGERI 1 MALANG</td></tr>
        <tr><td colspan="5">REKAPITULASI KEHADIRAN SISWA</td></tr>
        <tr><td></td></tr> {{-- Spasi --}}

        {{-- BARIS 6-7: INFORMASI DETAIL --}}
        <tr>
            <td colspan="2"><b>Kelas</b></td>
            <td>: {{ $classroom->name }}</td>
        </tr>
        <tr>
            <td colspan="2"><b>Mata Pelajaran</b></td>
            <td>: {{ $subject->name }}</td>
        </tr>
        <tr>
            <td colspan="2"><b>Periode</b></td>
            <td>: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</td>
        </tr>
        <tr><td></td></tr> {{-- Spasi --}}

        {{-- BARIS 8: HEADER TABEL --}}
        <tr>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">NO</th>
            <th width="30" style="border: 1px solid black; font-weight: bold; text-align: center;">NAMA SISWA</th>
            
            {{-- Loop Tanggal --}}
            @foreach($dates as $date)
                <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">
                    {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                </th>
            @endforeach

            {{-- Header Total --}}
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">H</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">S</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">I</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">A</th>
        </tr>
    </thead>

    {{-- ISI DATA --}}
    <tbody>
        @foreach($students as $student)
            @php
                $h = $s = $i = $a = 0;
            @endphp
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $student->name }}</td>

                {{-- Loop Status Per Tanggal --}}
                @foreach($dates as $date)
                    @php
                        $attn = $student->attendances->firstWhere('date', $date);
                        $status = $attn ? $attn->status : '-';
                        
                        // Konversi Status ke Kode Singkat
                        $code = '-';
                        if($status == 'Hadir') { $code = '.'; $h++; }
                        elseif($status == 'Sakit') { $code = 'S'; $s++; }
                        elseif($status == 'Izin') { $code = 'I'; $i++; }
                        elseif($status == 'Alpha') { $code = 'A'; $a++; }
                    @endphp
                    <td style="border: 1px solid black; text-align: center;">{{ $code }}</td>
                @endforeach

                {{-- Total --}}
                <td style="border: 1px solid black; text-align: center;">{{ $h }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $s }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $i }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $a }}</td>
            </tr>
        @endforeach
    </tbody>
</table>