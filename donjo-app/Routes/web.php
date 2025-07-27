<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

// Route::setAutoRoute(true);

// Definisi Rute Default
Route::get('/', 'First@index');
Route::get('/index/{p?}', 'First@index');

// Rute untuk error 404 (Override)
Route::error('404_override', static function (): void {
    show_404();
});

// Rute untuk sitemap.xml dan feed.xml
Route::get('sitemap.xml', 'Sitemap@index');
Route::get('sitemap', 'Sitemap@index');
Route::get('feed.xml', 'Feed@index');
Route::get('feed', 'Feed@index');

// Rute untuk Artikel Lama
Route::group('/first/artikel', static function (): void {
    Route::get('/', 'First@utama');
    Route::get('/{id}', 'First@artikel');
    Route::get('/{thn}/{bln}/{tgl}/{slug}', 'First@artikel');
});

// Rute untuk Artikel Baru
Route::group('/artikel', static function (): void {
    Route::get('/kategori/{id}/{p?}', 'First@kategori');
    Route::get('{id}', 'First@artikel');
    Route::get('{thn}/{bln}/{tgl}/{slug}', 'First@artikel');
});

Route::get('/arsip/{p?}', 'First@arsip');
Route::post('/add_comment/{id?}', 'First@add_comment');
Route::get('/load_apbdes', 'First@load_apbdes');
Route::get('/data-wilayah', 'First@wilayah');
Route::get('/data_analisis', 'First@data_analisis');
Route::get('/jawaban_analisis/{stat?}/{sb?}/{per?}', 'First@jawaban_analisis');
Route::get('/load_aparatur_desa', 'First@load_aparatur_desa');
Route::get('/load_aparatur_wilayah/{id?}/{kd_jabatan?}', 'First@load_aparatur_wilayah');

// Route lama, masih menggunakan first
Route::group('/first', static function (): void {
    Route::get('/unduh_dokumen_artikel/{id}', 'First@unduh_dokumen_artikel')->name('first.unduh_dokumen_artikel');
    Route::get('/gallery/{p?}', 'First@gallery')->name('first.gallery');
    Route::get('/sub_gallery/{parent?}/{p?}', 'First@sub_gallery')->name('first.sub_gallery');
    Route::get('/statistik/{stat?}/{tipe?}', 'First@statistik')->name('first.statistik');
    Route::get('/kelompok/{slug?}', 'First@kelompok')->name('first.kelompok');
    Route::get('/suplemen/{slug?}', 'First@suplemen')->name('first.suplemen');
    Route::get('/kesehatan/{slug?}', 'First@kesehatan')->name('first.kesehatan');
    Route::post('/ajax_peserta_program_bantuan', 'First@ajax_peserta_program_bantuan')->name('first.ajax_peserta_program_bantuan');
    Route::get('/dpt', 'First@dpt')->name('first.dpt');
    Route::get('/get_form_info', 'First@get_form_info')->name('first.get_form_info');
    Route::get('/arsip/{p?}', 'First@arsip')->name('first.arsip');
});

// Captcha
Route::get('captcha', 'Securimage@show');

// Dokumen web
Route::group('/dokumen_web', static function (): void {
    Route::get('/tampil/{slug?}', 'Dokumen_web@tampil');
    Route::get('/unduh/{slug?}', 'Dokumen_web@unduh');
    Route::get('/unduh_berkas/{id_dokumen}', 'Dokumen_web@unduh_berkas');
});

Route::group('/statistik_web', static function (): void {
    Route::get('/load_chart_gis/{lap?}', 'Statistik_web@load_chart_gis');
    Route::get('/get_data_stat/{data?}/{lap?}', 'Statistik_web@get_data_stat');
    Route::get('/dusun/{tipe?}/{lap?}', 'Statistik_web@dusun');
    Route::get('/rw/{tipe?}/{lap?}', 'Statistik_web@rw');
    Route::get('/rt/{tipe?}/{lap?}', 'Statistik_web@rt');
    Route::get('/chart_gis_desa/{lap?}/{desa?}', 'Statistik_web@chart_gis_desa');
    Route::get('/chart_gis_dusun/{tipe?}/{lap?}', 'Statistik_web@chart_gis_dusun');
    Route::get('/chart_gis_rw/{tipe?}/{lap?}', 'Statistik_web@chart_gis_rw');
    Route::get('/chart_gis_rt/{tipe?}/{lap?}', 'Statistik_web@chart_gis_rt');
    Route::get('/chart_gis_kadus/{id_kepala?}', 'Statistik_web@chart_gis_kadus');
    Route::get('/load_kadus/{tipe?}/{lap?}', 'Statistik_web@load_kadus');
});

// Tampil assets
Route::get('/tampil/{slug?}', 'Dokumen_web@tampil');
Route::get('/unduh/{slug?}', 'Dokumen_web@unduh');

// Koneksi database
Route::get('koneksi-database', 'Koneksi_database@index');
Route::group('koneksi_database', static function (): void {
    Route::get('/', 'Koneksi_database@index');
    Route::get('config', 'Koneksi_database@config');
    Route::get('updateKey', 'Koneksi_database@updateKey');
    Route::get('encryptPassword', 'Koneksi_database@encryptPassword');
});

Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::group('galeri', static function (): void {
        Route::get('/{parent?}/index/{p?}', 'Galeri@detail')->name('fweb.galeri.detail');
        // Route::get('/{parent?}/index', 'Galeri@detail')->name('fweb.galeri.detail');
        Route::get('/index/{p?}', 'Galeri@index')->name('fweb.galeri.index-page');
        Route::get('/', 'Galeri@index')->name('fweb.galeri.index');
    });

    Route::get('/status-idm/{tahun?}', 'Idm@index')->name('fweb.idm.index');
    Route::group('informasi-publik', static function (): void {
        Route::get('/', 'Informasi_publik@index')->name('fweb.informasi_publik.index');
        Route::post('/data', 'Informasi_publik@ajax_informasi_publik')->name('fweb.informasi_publik.ajax_informasi_publik');
        Route::get('/tampilkan/{id_dokumen?}/{id_pend?}', 'Informasi_publik@tampilkan')->name('fweb.informasi_publik.tampilkan');
        Route::get('/aksi/{aksi}/{id_dokumen?}', 'Informasi_publik@aksi')->name('fweb.informasi_publik.aksi');
    });

    Route::get('/data-kelompok/{slug?}', 'Kelompok@detail')->name('fweb.kelompok.detail');
    Route::get('/lapak/{p?}', 'Lapak@index')->name('fweb.lapak.index');
    Route::get('/data-lembaga/{slug?}', 'Lembaga@detail')->name('fweb.lembaga.detail');
    Route::get('/pemerintah', 'Pemerintah@index')->name('fweb.pemerintah.index');
    Route::get('/struktur-organisasi-dan-tata-kerja', 'Sotk@index')->name('fweb.sotk.index');

    Route::group('pembangunan', static function (): void {
        Route::get('/', 'Pembangunan@index')->name('fweb.pembangunan.index');
        Route::get('/index/{p?}', 'Pembangunan@index')->name('fweb.pembangunan.index-page');
        Route::get('/{slug}', 'Pembangunan@detail')->name('fweb.pembangunan.detail');
    });

    Route::group('inventaris', static function (): void {
        Route::get('/', 'Inventaris@index')->name('fweb.inventaris.index');
        Route::get('/{slug}', 'Inventaris@detail')->name('fweb.inventaris.detail');
    });

    Route::group('pengaduan', static function (): void {
        Route::post('/kirim', 'Pengaduan@kirim')->name('fweb.pengaduan.kirim');
        Route::get('/{p?}', 'Pengaduan@index')->name('fweb.pengaduan.index');
    });
    Route::get('/fweb/peraturan/datatables', 'Peraturan@datatables')->name('fweb.peraturan.datatables');
    Route::group('peraturan-desa', static function (): void {
        Route::get('/', 'Peraturan@index')->name('fweb.peraturan.index');
        Route::get('/datatables', 'Peraturan@datatables')->name('fweb.peraturan.datatables-alias');
    });

    Route::get('/status-sdgs', 'Sdgs@index')->name('fweb.sdgs.index');
    Route::get('/peta', 'Peta@index')->name('fweb.peta.index');
    Route::get('/data-statistik/{slug}/cetak/{aksi}', 'Statistik@cetak')->name('fweb.statistik.cetak');
    Route::get('/data-statistik/{slug?}', 'Statistik@index')->name('fweb.statistik.index');
    Route::get('/data-suplemen/{slug?}', 'Suplemen@detail')->name('fweb.suplemen.detail');
    Route::get('/data-kesehatan/cetak/{aksi?}', 'Kesehatan@cetak')->name('fweb.kesehatan.cetak');
    Route::get('/data-kesehatan/{slug?}', 'Kesehatan@detail')->name('fweb.kesehatan.detail');
    Route::get('/data-vaksinasi', 'Vaksin@index')->name('fweb.vaksin.index');
    Route::get('/data-dpt', 'Dpt@index')->name('fweb.dpt');
    Route::get('/v/{alias?}', 'Verifikasi_surat@cek')->name('fweb.verifikasi_surat.cek');
    Route::get('/c1/{id_dokumen?}/{tipe?}', 'Verifikasi_surat@encode')->name('fweb.verifikasi_surat.encode');
    Route::get('/verifikasi-surat/{id_encoded?}', 'Verifikasi_surat@decode')->name('fweb.verifikasi_surat.decode');
    Route::get('/verifikasi-surat-dinas/{id_encoded?}', 'Verifikasi_surat@decodeSuratDinas')->name('fweb.verifikasi_surat.decode-surat-dinas');

    // Embed
    Route::get('/embed', 'Embed@index');
});

Route::group('install', static function (): void {
    Route::match(['GET', 'POST'], '/', 'Install@index');
    Route::match(['GET', 'POST'], '/index', 'Install@index');
    Route::match(['GET', 'POST'], '/server', 'Install@server');
    Route::match(['GET', 'POST'], '/folders', 'Install@folders');
    Route::match(['GET', 'POST'], '/database', 'Install@database');
    Route::match(['GET', 'POST'], '/migrations', 'Install@migrations');
    Route::match(['GET', 'POST'], '/user', 'Install@user');
    Route::match(['GET', 'POST'], '/finish', 'Install@finish');
    Route::match(['GET', 'POST'], '/syarat_sandi/{password?}', 'Install@syarat_sandi');
});

Route::group('notif_web', static function (): void {
    Route::get('inbox', 'Notif_web@inbox')->name('fweb.notif_web.inbox');
    Route::get('surat_perlu_perhatian', 'Notif_web@surat_perlu_perhatian')->name('fweb.notif_web.surat_perlu_perhatian');
});

// Include all routes in folder Web
foreach (glob(APPPATH . 'Routes/Web/*.php') as $file) {
    require_once $file;
}
