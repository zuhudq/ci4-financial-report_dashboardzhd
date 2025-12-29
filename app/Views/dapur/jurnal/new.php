<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Input Jurnal
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Input Jurnal Umum (Smart Entry)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-lg border-top-danger">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold text-danger"><i class="fas fa-magic mr-2"></i> Form Input Cerdas</h3>
    </div>
    <div class="card-body">
        <form action="/jurnal/create" method="post" id="formJurnal">

            <div class="row mb-4 bg-light p-3 rounded">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Deskripsi Transaksi <span class="badge badge-success ml-2">AI Detect Active</span></label>
                        <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Contoh: Bayar Listrik, Beli Mesin Kredit, Terima Pendapatan..." required autocomplete="off">
                        <small class="text-muted font-italic">Ketik kata kunci (misal: "bayar", "beli", "terima") untuk mengisi akun otomatis.</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="tabelJurnal">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th style="width: 40%">Nama Akun</th>
                            <th style="width: 25%">Debit (Rp)</th>
                            <th style="width: 25%">Kredit (Rp)</th>
                            <th style="width: 10%" class="text-center"><i class="fas fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody id="bodyJurnal">
                        <tr>
                            <td>
                                <select name="akun_id[]" class="form-control select2 akun-select" required>
                                    <option value="">-- Pilih Akun --</option>
                                    <?php foreach ($accounts as $acc) : ?>
                                        <option value="<?= $acc['id_akun'] ?>" data-name="<?= strtolower($acc['nama_akun']) ?>"><?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" name="debit[]" class="form-control uang debit-input" placeholder="0" onkeyup="hitungTotal()"></td>
                            <td><input type="text" name="kredit[]" class="form-control uang kredit-input" placeholder="0" onkeyup="hitungTotal()"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-light" disabled><i class="fas fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="akun_id[]" class="form-control select2 akun-select" required>
                                    <option value="">-- Pilih Akun --</option>
                                    <?php foreach ($accounts as $acc) : ?>
                                        <option value="<?= $acc['id_akun'] ?>" data-name="<?= strtolower($acc['nama_akun']) ?>"><?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" name="debit[]" class="form-control uang debit-input" placeholder="0" onkeyup="hitungTotal()"></td>
                            <td><input type="text" name="kredit[]" class="form-control uang kredit-input" placeholder="0" onkeyup="hitungTotal()"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger hapus-baris"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-info btn-sm" id="tambahBaris"><i class="fas fa-plus mr-1"></i> Tambah Baris (Split Payment)</button>
                            </td>
                        </tr>
                        <tr class="font-weight-bold bg-light">
                            <td class="text-right">TOTAL</td>
                            <td>Rp <span id="totalDebit">0</span></td>
                            <td>Rp <span id="totalKredit">0</span></td>
                            <td class="text-center"><span id="indicator" class="badge badge-secondary">...</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-success btn-lg" id="btnSimpan" disabled><i class="fas fa-save mr-2"></i> Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<div style="display:none;" id="templateRow">
    <table>
        <tr>
            <td>
                <select name="akun_id[]" class="form-control akun-select">
                    <option value="">-- Pilih Akun --</option>
                    <?php foreach ($accounts as $acc) : ?>
                        <option value="<?= $acc['id_akun'] ?>" data-name="<?= strtolower($acc['nama_akun']) ?>"><?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="text" name="debit[]" class="form-control uang debit-input" placeholder="0" onkeyup="hitungTotal()"></td>
            <td><input type="text" name="kredit[]" class="form-control uang kredit-input" placeholder="0" onkeyup="hitungTotal()"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger hapus-baris"><i class="fas fa-trash"></i></button></td>
        </tr>
    </table>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });

        // Tambah Baris
        $('#tambahBaris').click(function() {
            var row = $('#templateRow tr').clone();
            $('#bodyJurnal').append(row);
            $('.uang').mask('000.000.000.000.000', {
                reverse: true
            });
        });

        // Hapus Baris
        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
            hitungTotal();
        });

        // --- OTAK AI SEDERHANA ---
        $('#deskripsi').on('keyup', function() {
            var text = $(this).val().toLowerCase();

            // Logic 1: Pembelian (Beli -> Debit Aset/Beban, Kredit Kas/Utang)
            if (text.includes('beli') || text.includes('bayar')) {
                // Menebak Debit (Baris 1)
                if (text.includes('listrik')) setAkun(0, 'listrik');
                else if (text.includes('gaji')) setAkun(0, 'gaji');
                else if (text.includes('perlengkapan')) setAkun(0, 'perlengkapan');
                else if (text.includes('mesin')) setAkun(0, 'mesin');
                else if (text.includes('sewa')) setAkun(0, 'sewa');

                // Menebak Kredit (Baris 2)
                if (text.includes('kredit') || text.includes('utang')) setAkun(1, 'utang');
                else setAkun(1, 'kas'); // Default tunai
            }

            // Logic 2: Pendapatan (Terima/Jual -> Debit Kas/Piutang, Kredit Pendapatan)
            else if (text.includes('terima') || text.includes('jual') || text.includes('pendapatan')) {
                // Menebak Debit (Baris 1)
                if (text.includes('kredit') || text.includes('nanti')) setAkun(0, 'piutang');
                else setAkun(0, 'kas');

                // Menebak Kredit (Baris 2)
                setAkun(1, 'penjualan');
            }
        });

        function setAkun(rowIndex, keyword) {
            var row = $('#bodyJurnal tr').eq(rowIndex);
            if (row.length > 0) {
                row.find('select option').each(function() {
                    if ($(this).data('name') && $(this).data('name').includes(keyword)) {
                        row.find('select').val($(this).val());
                        return false;
                    }
                });
            }
        }
    });

    window.hitungTotal = function() {
        var totD = 0;
        var totK = 0;
        $('.debit-input').each(function() {
            totD += parseFloat($(this).val().replace(/\./g, '') || 0);
        });
        $('.kredit-input').each(function() {
            totK += parseFloat($(this).val().replace(/\./g, '') || 0);
        });

        $('#totalDebit').text(totD.toLocaleString('id-ID'));
        $('#totalKredit').text(totK.toLocaleString('id-ID'));

        var btn = $('#btnSimpan');
        var ind = $('#indicator');

        if (totD > 0 && totD === totK) {
            btn.prop('disabled', false);
            ind.removeClass('badge-secondary badge-danger').addClass('badge-success').html('BALANCE');
        } else {
            btn.prop('disabled', true);
            ind.removeClass('badge-secondary badge-success').addClass('badge-danger').html('TIDAK BALANCE');
        }
    }
</script>
<?= $this->endSection() ?>