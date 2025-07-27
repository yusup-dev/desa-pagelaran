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

use App\Models\Aset;
use App\Models\InventarisTanah;
use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_tanah extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['inventaris_tanah_model', 'pamong_model', 'aset_model']);
    }

    public function index()
    {
        $data['tip'] = 1;

        return view('admin.inventaris.tanah.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u') && ! $row->mutasi) {
                        $aksi .= '<a href="' . ci_route('inventaris_tanah_mutasi.form/') . $row->id . '/tambah' . '" title="Mutasi Data" class="btn bg-olive btn-sm"><i class="fa fa-external-link-square"></i></a> ';
                    }

                    $aksi .= '<a href="' . ci_route('inventaris_tanah.form') . '/' . $row->id . '/' . 1 . '" class="btn btn-info btn-sm"  title="Lihat Data"><i class="fa fa-eye"></i></a> ';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('inventaris_tanah.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('inventaris_tanah.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('kode_barang_register', static fn ($row): string => $row->kode_barang . '<br>' . $row->register)
                ->editColumn('harga', static fn ($row): string => number_format($row->harga, 0, ',', '.'))
                ->rawColumns(['aksi', 'kode_barang_register'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        return InventarisTanah::visible();
    }

    public function form($id = '', $view = false)
    {
        isCan('u');

        if ($id) {
            $data['action']      = $view ? 'Rincian' : 'Ubah';
            $data['form_action'] = ci_route('inventaris_tanah.update', $id);
            $data['main']        = InventarisTanah::findOrFail($id);
            $data['view_mark']   = $view ? 1 : 0;
            $data['kd_reg']      = InventarisTanah::select('register')->get();
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('inventaris_tanah.create');
            $data['main']        = null;
            $data['view_mark']   = null;
            $data['kd_reg']      = null;
        }

        $data['tip']      = 1;
        $data['get_kode'] = $this->header['desa'];
        $data['aset']     = Aset::golongan(2)->get()->toArray();
        $data['hasil']    = sprintf('%06s', InventarisTanah::count() + 1);

        return view('admin.inventaris.tanah.form', $data);
    }

    public function create(): void
    {
        isCan('u');

        if (InventarisTanah::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $update = InventarisTanah::findOrFail($id);

        $data = $this->validate($this->request);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        if (InventarisTanah::findOrFail($id)->update(['visible' => 0])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate(array $data): array
    {
        $data['nama_barang']        = strip_tags((string) $data['nama_barang_save']);
        $data['kode_barang']        = strip_tags((string) $data['kode_barang']);
        $data['register']           = strip_tags((string) $data['register']);
        $data['luas']               = bilangan($data['luas']);
        $data['tahun_pengadaan']    = bilangan($data['tahun_pengadaan']);
        $data['letak']              = strip_tags((string) $data['letak']);
        $data['hak']                = strip_tags((string) $data['hak']);
        $data['tanggal_sertifikat'] = date('Y-m-d', strtotime((string) $this->input->post('tanggal_sertifikat')));
        $data['no_sertifikat']      = strip_tags((string) $data['no_sertifikat']);
        $data['penggunaan']         = strip_tags((string) $data['penggunaan']);
        $data['asal']               = strip_tags((string) $data['asal']);
        $data['harga']              = bilangan($data['harga']);
        $data['keterangan']         = strip_tags((string) $data['keterangan']);
        $data['visible']            = 1;
        unset($data['nama_barang_save']);

        return $data;
    }

    public function dialog($aksi = 'cetak')
    {
        $data               = $this->modal_penandatangan();
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('inventaris_tanah.cetak', $aksi);

        return view('admin.inventaris.dialog_cetak', $data);
    }

    public function cetak($aksi = '')
    {
        $query          = $this->sumberData();
        $data           = $this->modal_penandatangan();
        $data['aksi']   = $aksi;
        $data['main']   = $query->orderBy('tahun_pengadaan', 'asc')->get();
        $data['pamong'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong')])->first()->toArray();
        if ($tahun = $this->input->post('tahun')) {
            $data['main'] = $query->where('tahun_pengadaan', $tahun)->get();
        }

        $data['total'] = total_jumlah($data['main'], 'harga');

        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=inventaris_tanah_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        return view('admin.inventaris.tanah.cetak', $data);
    }
}
