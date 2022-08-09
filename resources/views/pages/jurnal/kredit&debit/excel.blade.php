<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Currency</th>
            <th>Jumlah Tukar</th>
            <th>Kurs</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1;
            $total_debit = 0; 
            $total_kredit = 0;
            foreach ($jurnal as $key => $item) {
                $total_debit = $total_debit + $item->total_tukar;
                $total_kredit = $total_kredit + $item->jumlah_modal;
            }
            $grand = $total_kredit - $total_debit;
         
        @endphp
        @foreach ($jurnal as $item)
        <tr>
            <th>{{ $i++ }}.</th>
            <td>{{ date('d-M-Y H:i:s', strtotime($item->updated_at)) }}</td>
            @if ($item->jenis_jurnal == 'Debit')
                <td>Jual</td>
                <td>{{ $item->Currency->nama_currency }}</td>
                <td>{{ $item->jumlah_tukar }}</td>
                <td>Rp. {{ number_format($item->kurs) }}</td>
                <td>Rp. {{ number_format($item->total_tukar) }}</td>
                <td>-</td>
            @else
                <td>Modal</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>Rp. {{ number_format($item->jumlah_modal) }}</td>
            @endif
        </tr>
        @endforeach 
    </tbody>
    <tr>
        <th colspan="1"></th>
        <th colspan="5">Total Debit dan Kredit</th>
        <th colspan="1">Rp. {{ number_format($total_debit) }}</th>
        <th colspan="1">Rp. {{ number_format($total_kredit) }}</th>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Currency</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1;
            
        @endphp
        @foreach ($kurs as $item)
        <tr>
            <th>{{ $i++ }}.</th>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach 
    </tbody>
    <tr></tr>
    <tr>
        <th colspan="2">Sisa Modal</th>
        <th colspan="1">Rp. {{ number_format($grand) }}</th>
    </tr>
</table>