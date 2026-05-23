-- Migration: initial schema for form_roll
-- Created: 2026-05-23
-- Notes:
-- 1. This migration creates the target database and tables used by the app.
-- 2. Default auth users are not inserted here because the app expects hashed passwords.
--    After running this migration, execute: php backend/setup_auth.php

CREATE DATABASE IF NOT EXISTS `form_roll`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `form_roll`;

CREATE TABLE IF NOT EXISTS `rolls` (
  `id` char(36) NOT NULL,
  `urut` bigint NOT NULL AUTO_INCREMENT,
  `register` bigint DEFAULT NULL,
  `barcode` bigint DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `roll` int NOT NULL,
  `group_name` varchar(10) DEFAULT NULL,
  `mesin` varchar(50) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `denier` varchar(50) DEFAULT NULL,
  `panjang` int DEFAULT NULL,
  `lebar` int DEFAULT NULL,
  `anyam` varchar(50) DEFAULT NULL,
  `berat` varchar(50) DEFAULT NULL,
  `trace_code` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pic` varchar(50) DEFAULT NULL,
  `status_input` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urut` (`urut`),
  KEY `tanggal` (`tanggal`),
  KEY `mesin` (`mesin`),
  KEY `nama` (`nama`),
  KEY `trace_code` (`trace_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('administrator','operator') NOT NULL DEFAULT 'operator',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
