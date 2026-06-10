-- Create Database LibSpace
CREATE DATABASE IF NOT EXISTS `LibSpace` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `LibSpace`;

-- Tabel users
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel books
CREATE TABLE IF NOT EXISTS `books` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_buku` VARCHAR(255) NOT NULL,
    `penulis` VARCHAR(255) NOT NULL,
    `penerbit` VARCHAR(255) NOT NULL,
    `tahun_terbit` YEAR NOT NULL,
    `stok` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel peminjaman
CREATE TABLE IF NOT EXISTS `peminjaman` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `tanggal_pinjam` DATE NOT NULL,
    `tanggal_kembali` DATE NOT NULL,
    `tanggal_dikembalikan` DATE NULL,
    `status` VARCHAR(50) DEFAULT 'dipinjam',
    `denda` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Admin Default
INSERT INTO `users` (`username`, `password`, `role`) VALUES 
('admin', '$2y$10$2c/Cw9Fws7MYHKxBfvYPJe2xJCHp0KfQR9S4pKyqBVQbKnZL3V9l6', 'admin');
-- Password: admin123 (hashed with password_hash)

-- Insert Sample Books
INSERT INTO `books` (`nama_buku`, `penulis`, `penerbit`, `tahun_terbit`, `stok`) VALUES 
('Harry Potter and The Philosopher Stone', 'J.K. Rowling', 'Bloomsbury', 1997, 5),
('The Lord of The Rings', 'J.R.R. Tolkien', 'Allen and Unwin', 1954, 3),
('1984', 'George Orwell', 'Secker and Warburg', 1949, 4),
('To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott', 1960, 2),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Charles Scribners Sons', 1925, 6);

-- Alter tables untuk menambah kolom yang mungkin dibutuhkan
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `full_name` VARCHAR(255) NULL AFTER `username`;
