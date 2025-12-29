<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// 1. RUTE UTAMA (DASHBOARD)
// ====================================================================
$routes->get('/', 'Dashboard::index');           // Jika buka localhost:8080
$routes->get('/dashboard', 'Dashboard::index');


// ====================================================================
// 2. RUTE UNTUK MENU SIDEBAR (Jalur 'dapur')
// ====================================================================
$routes->group('dapur', function ($routes) {
    // Menu COA
    $routes->get('coa', 'Coa::index');
    $routes->get('coa/new', 'Coa::new');
    $routes->post('coa/create', 'Coa::create');
    $routes->get('coa/edit/(:num)', 'Coa::edit/$1');
    $routes->post('coa/update/(:num)', 'Coa::update/$1');
    $routes->get('coa/delete/(:num)', 'Coa::delete/$1');

    // Menu Jurnal
    $routes->get('jurnal', 'Jurnal::index');
    $routes->get('jurnal/new', 'Jurnal::new');
    $routes->get('jurnal/detail/(:num)', 'Jurnal::detail/$1');
    $routes->get('jurnal/edit/(:num)', 'Jurnal::edit/$1');
    $routes->get('jurnal/delete/(:num)', 'Jurnal::delete/$1');

    // RUTE TUTUP BUKU
    $routes->get('tutup-buku', 'TutupBuku::index');
    $routes->post('tutup-buku/proses', 'TutupBuku::proses');

    // MANAJEMEN ASET (BARU)
    $routes->get('aset', 'Aset::index');
    $routes->get('aset/new', 'Aset::new');
    $routes->post('aset/create', 'Aset::create');
    $routes->post('aset/generate', 'Aset::generatePenyusutan');
    $routes->get('aset/delete/(:num)', 'Aset::delete/$1');
    $routes->get('aset/edit/(:num)', 'Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'Aset::update/$1');
    $routes->get('aset/reset-penyusutan/(:any)', 'Aset::deletePenyusutan/$1');
});


// ====================================================================
// 3. RUTE UNTUK PROSES CRUD (Create, Update, Delete)
// ====================================================================
$routes->group('coa', function ($routes) {
    $routes->get('/', 'Coa::index');
    $routes->get('new', 'Coa::new');
    $routes->post('create', 'Coa::create');           // Proses Simpan
    $routes->get('edit/(:num)', 'Coa::edit/$1');
    $routes->post('update/(:num)', 'Coa::update/$1'); // Proses Update
    $routes->get('delete/(:num)', 'Coa::delete/$1');
});

$routes->group('jurnal', function ($routes) {
    $routes->get('/', 'Jurnal::index');
    $routes->get('new', 'Jurnal::new');
    $routes->post('create', 'Jurnal::create');        // Proses Simpan
    $routes->get('detail/(:num)', 'Jurnal::detail/$1');
    $routes->get('edit/(:num)', 'Jurnal::edit/$1');
    $routes->post('update/(:num)', 'Jurnal::update/$1'); // Proses Update
    $routes->get('delete/(:num)', 'Jurnal::delete/$1');
});


// ====================================================================
// 4. RUTE LAPORAN KEUANGAN (FULL SET)
// ====================================================================
$routes->group('laporan', function ($routes) {
    // Laporan Lama
    $routes->get('buku-besar', 'Laporan::bukuBesar');
    $routes->get('cetak-buku-besar', 'Laporan::cetakBukuBesar');
    $routes->get('laba-rugi', 'Laporan::labaRugi');
    $routes->get('cetak-laba-rugi', 'Laporan::cetakLabaRugi');
    $routes->get('neraca-saldo', 'Laporan::neracaSaldo');
    $routes->get('neraca', 'Laporan::neraca');
    $routes->get('cetak-neraca', 'Laporan::cetakNeraca');
    $routes->get('export-laba-rugi', 'Laporan::exportLabaRugiExcel');

    // --- TAMBAHAN BARU (ARUS KAS & EKUITAS) ---
    $routes->get('perubahan-ekuitas', 'Laporan::perubahanEkuitas');
    $routes->get('arus-kas', 'Laporan::arusKas');
    $routes->get('analisis-rasio', 'Analisis::rasio');
    $routes->get('harga-pokok-produksi', 'Laporan::hargaPokokProduksi');
});

// ====================================================================
// AUTH
// ====================================================================
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');
