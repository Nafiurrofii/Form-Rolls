INSERT INTO form_roll.rolls (
    id,
    urut,
    register,
    barcode,
    tanggal,
    jam,
    roll,
    group_name,
    mesin,
    nama,
    denier,
    panjang,
    lebar,
    anyam,
    berat,
    trace_code,
    keterangan,
    pic,
    status_input
)
SELECT
    UUID() AS id,
    p.urut,
    p.register,
    p.register AS barcode,
    p.tgl AS tanggal,
    p.jam,
    p.urutan AS roll,
    p.shift AS group_name,
    TRIM(p.mesin) AS mesin,
    TRIM(p.nama) AS nama,
    TRIM(p.denier) AS denier,
    CASE
        WHEN p.panjang REGEXP '^[0-9]+$' THEN CAST(p.panjang AS UNSIGNED)
        ELSE NULL
    END AS panjang,
    CASE
        WHEN p.lebar REGEXP '^[0-9]+$' THEN CAST(p.lebar AS UNSIGNED)
        ELSE NULL
    END AS lebar,
    TRIM(p.anyam) AS anyam,
    TRIM(p.berat) AS berat,
    TRIM(p.kodetrace) AS trace_code,
    TRIM(p.keterangan) AS keterangan,
    TRIM(p.pic) AS pic,
    'migrasi' AS status_input
FROM db_loomregister.tbl_produksi p
ORDER BY p.urut ASC;


INSERT INTO users (username, password, role)
VALUES
(
    'admin',
    '$2b$12$4mU.CXdDp81AqtTge9s1QuIUMy5.4u3lqViJ/TR2MuAHb9HXoApxe',
    'administrator'
),
(
    'operator',
    '$2b$12$tYVXJCCSmazci0d/bdUn8emWQIOVlVPMM0xvfDKWiN78C4H1jRrvK',
    'operator'
);