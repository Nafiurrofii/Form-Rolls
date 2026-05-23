-- Migration: import legacy data from db_loomregister.tbl_produksi into form_roll.rolls
-- Created: 2026-05-23
-- Source rows checked locally: 15,212 rows
-- Notes:
-- 1. Assumes database db_loomregister and table tbl_produksi already exist.
-- 2. Safe to run on an empty form_roll.rolls table.
-- 3. If rolls already contains data, use the duplicate-safe variant below.

USE `form_roll`;

INSERT INTO `rolls` (
    `id`,
    `urut`,
    `register`,
    `barcode`,
    `tanggal`,
    `jam`,
    `roll`,
    `group_name`,
    `mesin`,
    `nama`,
    `denier`,
    `panjang`,
    `lebar`,
    `anyam`,
    `berat`,
    `trace_code`,
    `keterangan`,
    `pic`,
    `status_input`
)
SELECT
    UUID() AS `id`,
    p.`urut`,
    p.`register`,
    p.`register` AS `barcode`,
    p.`tgl` AS `tanggal`,
    p.`jam`,
    p.`urutan` AS `roll`,
    p.`shift` AS `group_name`,
    TRIM(p.`mesin`) AS `mesin`,
    TRIM(p.`nama`) AS `nama`,
    TRIM(p.`denier`) AS `denier`,
    CASE
        WHEN p.`panjang` REGEXP '^[0-9]+$' THEN CAST(p.`panjang` AS UNSIGNED)
        ELSE NULL
    END AS `panjang`,
    CASE
        WHEN p.`lebar` REGEXP '^[0-9]+$' THEN CAST(p.`lebar` AS UNSIGNED)
        ELSE NULL
    END AS `lebar`,
    TRIM(p.`anyam`) AS `anyam`,
    TRIM(p.`berat`) AS `berat`,
    TRIM(p.`kodetrace`) AS `trace_code`,
    TRIM(p.`keterangan`) AS `keterangan`,
    TRIM(p.`pic`) AS `pic`,
    'migrasi' AS `status_input`
FROM `db_loomregister`.`tbl_produksi` p
ORDER BY p.`urut` ASC;

-- Duplicate-safe variant:
-- INSERT INTO `rolls` (
--     `id`, `urut`, `register`, `barcode`, `tanggal`, `jam`, `roll`,
--     `group_name`, `mesin`, `nama`, `denier`, `panjang`, `lebar`,
--     `anyam`, `berat`, `trace_code`, `keterangan`, `pic`, `status_input`
-- )
-- SELECT
--     UUID(),
--     p.`urut`,
--     p.`register`,
--     p.`register`,
--     p.`tgl`,
--     p.`jam`,
--     p.`urutan`,
--     p.`shift`,
--     TRIM(p.`mesin`),
--     TRIM(p.`nama`),
--     TRIM(p.`denier`),
--     CASE WHEN p.`panjang` REGEXP '^[0-9]+$' THEN CAST(p.`panjang` AS UNSIGNED) ELSE NULL END,
--     CASE WHEN p.`lebar` REGEXP '^[0-9]+$' THEN CAST(p.`lebar` AS UNSIGNED) ELSE NULL END,
--     TRIM(p.`anyam`),
--     TRIM(p.`berat`),
--     TRIM(p.`kodetrace`),
--     TRIM(p.`keterangan`),
--     TRIM(p.`pic`),
--     'migrasi'
-- FROM `db_loomregister`.`tbl_produksi` p
-- LEFT JOIN `rolls` r ON r.`urut` = p.`urut`
-- WHERE r.`urut` IS NULL
-- ORDER BY p.`urut` ASC;
