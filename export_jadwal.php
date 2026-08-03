<?php

require_once "koneksi.php";
require_once "vendor/autoload.php";

use Dompdf\Dompdf;


$html = '

<h2 style="text-align:center;">
Jadwal Perkuliahan SIKULIAH
</h2>

<table border="1" width="100%" cellpadding="8" cellspacing="0">

<tr>
    <th>Hari</th>
    <th>Waktu</th>
    <th>Ruangan</th>
    <th>Mata Kuliah</th>
    <th>Dosen</th>
    <th>Kelas</th>
</tr>
';



$query = mysqli_query($conn, "

SELECT

hari.nama_hari,
jam_kuliah.jam_mulai,
jam_kuliah.jam_selesai,
ruangan.nama_ruangan,
mata_kuliah.nama_mk,
dosen.nama_dosen,
kelas.nama_kelas

FROM jadwal


JOIN hari
ON jadwal.id_hari = hari.id_hari


JOIN jam_kuliah
ON jadwal.id_jam = jam_kuliah.id_jam


JOIN ruangan
ON jadwal.id_ruangan = ruangan.id_ruangan


JOIN dosen_mk
ON jadwal.id_dosen_mk = dosen_mk.id


JOIN dosen
ON dosen_mk.id_dosen = dosen.id_dosen


JOIN mata_kuliah
ON dosen_mk.id_mk = mata_kuliah.id_mk


JOIN kelas
ON dosen_mk.id_kelas = kelas.id_kelas


ORDER BY
hari.id_hari ASC,
jam_kuliah.jam_mulai ASC

");



while($data = mysqli_fetch_assoc($query)){


$html .= '

<tr>

<td>
'.$data['nama_hari'].'
</td>


<td>
'.$data['jam_mulai'].' -
'.$data['jam_selesai'].'
</td>


<td>
'.$data['nama_ruangan'].'
</td>


<td>
'.$data['nama_mk'].'
</td>


<td>
'.$data['nama_dosen'].'
</td>


<td>
'.$data['nama_kelas'].'
</td>


</tr>

';


}



$html .= '

</table>

';



$pdf = new Dompdf();


$pdf->loadHtml($html);


$pdf->setPaper(
    'A4',
    'landscape'
);


$pdf->render();


$pdf->stream(
    "jadwal-kuliah.pdf",
    [
        "Attachment"=>true
    ]
);


?>