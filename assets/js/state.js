/* ─────────────────────────────────────────────────────
   STATE - Global State Management
   ──────────────────────────────────────────────────── */

// Semua data baku (20 records, virtual 1250)
export const rawData = [
  {tgl:'2026-05-06',jam:'06:09:55',roll:4,  shift:'B',mesin:16,  nama:'PP PUTIH JUMBO',dnr:1600,pj:500, lb:180,anyam:197,    br:'78/6',   trace:'06-0609-C-16-4',  reg:263381,user:'UTM'},
  {tgl:'2026-05-06',jam:'06:00:40',roll:36, shift:'B',mesin:10,  nama:'PP PUTIH',     dnr:600, pj:2912,lb:70, anyam:'K10/52.5',br:'20/6',  trace:'-0600-C-10-36',    reg:263380,user:'UTM'},
  {tgl:'2026-05-06',jam:'05:55:10',roll:38, shift:'B',mesin:38,  nama:'PP PUTIH SB',  dnr:600, pj:2950,lb:56, anyam:'K10/52.5',br:'74/3',  trace:'-0555-C-38-38',    reg:263379,user:'UTM'},
  {tgl:'2026-05-06',jam:'05:47:31',roll:2192,shift:'B',mesin:36, nama:'PP MONTY',     dnr:650, pj:2800,lb:56, anyam:'K10/52.5',br:'79/3',  trace:'547-C-12-2192',    reg:263378,user:'UTM'},
  {tgl:'2026-05-06',jam:'05:44:14',roll:38, shift:'B',mesin:'04',nama:'PP PUTIH SB',  dnr:600, pj:3010,lb:56, anyam:'K10/52.5',br:'30/3',  trace:'6-0544-B-39-38',   reg:263377,user:'UTM'},
  {tgl:'2026-05-06',jam:'05:41:53',roll:2195,shift:'B',mesin:'01',nama:'PP MONTY',    dnr:600, pj:2925,lb:50, anyam:'K10/52.5',br:'63/6',  trace:'541-C-01-2195',    reg:263376,user:'UTM'},
  {tgl:'2026-05-06',jam:'05:35:48',roll:2164,shift:'B',mesin:17, nama:'PP PUTIH',     dnr:650, pj:3000,lb:180,anyam:157,      br:'63/6',   trace:'535-A-26-2164',    reg:263375,user:'UTM'},
  {tgl:'2026-05-06',jam:'03:46:99',roll:1919,shift:'B',mesin:17, nama:'PP HITAM',     dnr:1500,pj:300, lb:200,anyam:250,      br:'59/6',   trace:'251-A-18-2650',    reg:263374,user:'UTM'},
  {tgl:'2026-05-06',jam:'02:51:44',roll:2650,shift:'B',mesin:18, nama:'PP GEOTEX 250',dnr:2600,pj:150, lb:180,anyam:179,      br:'30/6',   trace:'6-0206-B-22-7',    reg:263372,user:'UTM'},
  {tgl:'2026-05-06',jam:'02:04:49',roll:7,   shift:'B',mesin:22, nama:'PP PUTIH JUMBO',dnr:1600,pj:500,lb:180,anyam:157,      br:'75/6',   trace:'126-B-23-1908',    reg:263371,user:'UTM'},
  {tgl:'2026-05-05',jam:'23:58:12',roll:12,  shift:'A',mesin:14, nama:'PP HITAM',     dnr:1500,pj:400, lb:150,anyam:200,      br:'66/4',   trace:'158-A-23-1400',    reg:263370,user:'UTM'},
  {tgl:'2026-05-05',jam:'22:30:44',roll:5,   shift:'A',mesin:20, nama:'PP MONTY',     dnr:700, pj:2800,lb:60, anyam:'K10/52.5',br:'72/3',  trace:'530-A-20-700',     reg:263369,user:'SBY'},
  {tgl:'2026-05-05',jam:'21:15:30',roll:9,   shift:'A',mesin:11, nama:'PP GEOTEX 250',dnr:2500,pj:200, lb:200,anyam:170,      br:'28/6',   trace:'215-A-11-2500',    reg:263368,user:'SBY'},
  {tgl:'2026-05-05',jam:'20:05:18',roll:3,   shift:'A',mesin:33, nama:'PP PUTIH',     dnr:600, pj:3000,lb:70, anyam:'K10/52.5',br:'22/6',  trace:'005-A-33-600',     reg:263367,user:'SBY'},
  {tgl:'2026-05-05',jam:'19:44:05',roll:21,  shift:'A',mesin:7,  nama:'PP PUTIH SB',  dnr:600, pj:2900,lb:56, anyam:'K10/52.5',br:'44/3',  trace:'744-A-07-600',     reg:263366,user:'UTM'},
  {tgl:'2026-05-05',jam:'18:22:55',roll:15,  shift:'C',mesin:29, nama:'PP HITAM',     dnr:1200,pj:350, lb:180,anyam:220,      br:'55/5',   trace:'322-C-29-1200',    reg:263365,user:'UTM'},
  {tgl:'2026-05-05',jam:'17:10:00',roll:8,   shift:'C',mesin:42, nama:'PP MONTY',     dnr:650, pj:2750,lb:56, anyam:'K10/52.5',br:'61/3',  trace:'510-C-42-650',     reg:263364,user:'SBY'},
  {tgl:'2026-05-05',jam:'16:05:33',roll:2,   shift:'C',mesin:6,  nama:'PP PUTIH JUMBO',dnr:1600,pj:500,lb:180,anyam:160,      br:'77/6',   trace:'105-C-06-1600',    reg:263363,user:'SBY'},
  {tgl:'2026-05-05',jam:'14:50:10',roll:44,  shift:'C',mesin:9,  nama:'PP GEOTEX 250',dnr:2600,pj:180, lb:180,anyam:185,      br:'32/6',   trace:'250-C-09-2600',    reg:263362,user:'UTM'},
  {tgl:'2026-05-05',jam:'13:30:20',roll:30,  shift:'C',mesin:15, nama:'PP PUTIH',     dnr:600, pj:2950,lb:70, anyam:'K10/52.5',br:'19/6',  trace:'330-C-15-600',     reg:263361,user:'UTM'},
];

// Jumlah virtual record (untuk simulasi data besar)
export const totalVirtualRecords = 1250;

// State global aplikasi
export const appState = {
  currentPage: 1,
  filteredData: [],
  selectedRow: null,
  isEditMode: false,
  currentUser: null,
};

/* ─── GETTER / SETTER ────────────────────────────– */

export function setCurrentPage(page) {
  appState.currentPage = page;
}

export function getCurrentPage() {
  return appState.currentPage;
}

export function setFilteredData(data) {
  appState.filteredData = data;
}

export function getFilteredData() {
  return appState.filteredData;
}

export function setSelectedRow(row) {
  appState.selectedRow = row;
}

export function getSelectedRow() {
  return appState.selectedRow;
}

export function setEditMode(mode) {
  appState.isEditMode = mode;
}

export function isEditMode() {
  return appState.isEditMode;
}

export function setCurrentUser(user) {
  appState.currentUser = user;
}

export function getCurrentUser() {
  return appState.currentUser;
}

/* ─── RESET STATE ────────────────────────────────– */
export function resetState() {
  appState.currentPage = 1;
  appState.filteredData = [];
  appState.selectedRow = null;
  appState.isEditMode = false;
}
