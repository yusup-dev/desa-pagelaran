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

use App\Models\Pemilihan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Dpt extends Web_Controller
{
    public function index(): void
    {
        $cekMenu = $this->web_menu_model->menu_aktif('dpt');

        $this->load->model(['penduduk_model', 'dpt_model']);
        $data                      = $this->includes;
        $data['title']             = 'Daftar Calon Pemilih Berdasarkan Wilayah';
        $data['main']              = $this->dpt_model->statistik_wilayah();
        $data['total']             = $this->dpt_model->statistik_total();
        $data['tanggal_pemilihan'] = Schema::hasTable('pemilihan') ? Pemilihan::tanggalPemilihan() : Carbon::now()->format('Y-m-d');
        $data['tipe']              = 4;
        $data['slug_aktif']        = 'dpt';
        $data['tampil']            = $cekMenu;

        $this->_get_common_data($data);

        $statistik       = getStatistikLabel(4, 'per ' . ucwords(setting('sebutan_dusun')), $data['desa']['nama_desa']);
        $data['heading'] = $statistik['label'];

        $this->set_template('layouts/stat.tpl.php');
        theme_view($this->template, $data);
    }
}
