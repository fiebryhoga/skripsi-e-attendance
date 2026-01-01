<table>
    
    <thead>
        <tr><td colspan="5">PEMERINTAH PROVINSI JAWA TIMUR</td></tr>
        <tr><td colspan="5">DINAS PENDIDIKAN</td></tr>
        <tr><td colspan="5">SMA NEGERI 1 MALANG</td></tr>
        <tr><td colspan="5">REKAPITULASI KEHADIRAN SISWA</td></tr>
        <tr><td></td></tr> 

        
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
        <tr><td></td></tr> 

        
        <tr>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">NO</th>
            <th width="30" style="border: 1px solid black; font-weight: bold; text-align: center;">NAMA SISWA</th>
            
            
            @foreach($dates as $date)
                <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">
                    {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                </th>
            @endforeach

            
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">H</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">S</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">I</th>
            <th width="5" style="border: 1px solid black; font-weight: bold; text-align: center;">A</th>
        </tr>
    </thead>

    
    <tbody>
        @foreach($students as $student)
            @php
                $h = $s = $i = $a = 0;
            @endphp
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $student->name }}</td>

                
                @foreach($dates as $date)
                    @php
                        $attn = $student->attendances->firstWhere('date', $date);
                        $status = $attn ? $attn->status : '-';
                        
                        $code = '-';
                        if($status == 'Hadir') { $code = '.'; $h++; }
                        elseif($status == 'Sakit') { $code = 'S'; $s++; }
                        elseif($status == 'Izin') { $code = 'I'; $i++; }
                        elseif($status == 'Alpha') { $code = 'A'; $a++; }
                    @endphp
                    <td style="border: 1px solid black; text-align: center;">{{ $code }}</td>
                @endforeach

                
                <td style="border: 1px solid black; text-align: center;">{{ $h }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $s }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $i }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $a }}</td>
            </tr>
        @endforeach
    </tbody>
</table>