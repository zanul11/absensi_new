<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MASTER MENU
    |--------------------------------------------------------------------------
    */

    'menu_super' => [
        [
            'id' => 'pengaturan',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
            'title' => 'pengaturan',
            'url' => 'pengaturan',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'pengguna',
                    'url' => 'pengguna',
                    'title' => 'pengguna'
                ], [
                    'id' => 'lokasi',
                    'url' => 'lokasi',
                    'title' => 'lokasi absen'
                ],
                [
                    'id' => 'jadwal absen',
                    'url' => 'jadwal_absen',
                    'title' => 'jadwal absen'
                ]
            ]
        ],
        [
            'id' => 'data_master',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
            'title' => 'data master',
            'url' => 'data_master',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'pegawai',
                    'url' => 'pegawai',
                    'title' => 'pegawai'
                ],
                [
                    'id' => 'jenis izin',
                    'url' => 'jenis_izin',
                    'title' => 'jenis izin'
                ]
            ]
        ],
        [
            'id' => 'absensi',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
            'title' => 'absensi',
            'url' => 'absensi',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'tanggal libur',
                    'url' => 'tanggal_libur',
                    'title' => 'tanggal libur'
                ],
                [
                    'id' => 'kehadiran',
                    'url' => 'kehadiran',
                    'title' => 'kehadiran'
                ],
                [
                    'id' => 'posting absen',
                    'url' => 'posting_absen',
                    'title' => 'posting absen'
                ],
                [
                    'id' => 'laporan absen',
                    'url' => 'laporan_absen',
                    'title' => 'laporan absen'
                ]
            ]
        ],
        [
            'id' => 'pengajuan',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>',
            'title' => 'pengajuan',
            'url' => 'pengajuan',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'absen',
                    'url' => 'request_absen_pulang',
                    'title' => 'absen'
                ]
            ]
        ],
        [
            'id' => 'informasi',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
            'title' => 'informasi',
            'url' => 'informasi',
            'caret' => false,
        ],

    ],





    'menu_admin' => [
        [
            'id' => 'pengaturan',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
            'title' => 'pengaturan',
            'url' => 'pengaturan',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'lokasi',
                    'url' => 'lokasi',
                    'title' => 'lokasi absen'
                ],
                [
                    'id' => 'jadwal absen',
                    'url' => 'jadwal_absen',
                    'title' => 'jadwal absen'
                ]
            ]
        ],
        [
            'id' => 'data_master',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
            'title' => 'data master',
            'url' => 'data_master',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'pegawai',
                    'url' => 'pegawai',
                    'title' => 'pegawai'
                ],
                [
                    'id' => 'jenis izin',
                    'url' => 'jenis_izin',
                    'title' => 'jenis izin'
                ]
            ]
        ],
        [
            'id' => 'absensi',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
            'title' => 'absensi',
            'url' => 'absensi',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'tanggal libur',
                    'url' => 'tanggal_libur',
                    'title' => 'tanggal libur'
                ],
                [
                    'id' => 'kehadiran',
                    'url' => 'kehadiran',
                    'title' => 'kehadiran'
                ],
                [
                    'id' => 'posting absen',
                    'url' => 'posting_absen',
                    'title' => 'posting absen'
                ],
                [
                    'id' => 'laporan absen',
                    'url' => 'laporan_absen',
                    'title' => 'laporan absen'
                ]
            ]
        ], [
            'id' => 'pengajuan',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>',
            'title' => 'pengajuan',
            'url' => 'pengajuan',
            'caret' => true,
            'sub_menu' => [
                [
                    'id' => 'absen',
                    'url' => 'request_absen_pulang',
                    'title' => 'absen'
                ]
            ]
        ],

    ]
];
