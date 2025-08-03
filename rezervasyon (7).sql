-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Haz 2025, 19:29:48
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `rezervasyon`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `parola` varchar(150) NOT NULL,
  `kullanici_tipi` enum('admin','kullanici') NOT NULL DEFAULT 'kullanici'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `email`, `parola`, `kullanici_tipi`) VALUES
(1, 'zeynepm', 'zynpoztrk183@gmail.com', '$2y$10$hIyiYi3iUtP6dypRWlEFY.Rmez/uvvdbVTu0oXg6mVhkzUZzIxKEi', 'kullanici'),
(2, 'test24', 'c4393460@gmail.com', '$2y$10$PnGevHo8bwJnPkrncVmQjei36TTGnFfezTDAMW2UujYEuzGLdsz.a', 'kullanici'),
(3, 'test', '', '$2y$10$L9HswbHnTdAmioiqgHz/4.k/9dpjCiEB9JDR0075C6iyFLgdHu7PO', 'kullanici'),
(4, 'test1234', 't@gmail.com', '$2y$10$Ugn1rMBvlUoXULiOPuxL8.OTbFf2f4GBSAINYuGFUJHq4fiZ4GjiO', 'kullanici'),
(5, 'zeynep1', 'zynpoztrk183@gmail.com', '$2y$10$C9GY/c.ajQpdFkfDDtNo3eLZRVQvUPTTqMd4Mh7hodjQNuakzWkTG', 'kullanici'),
(6, 'testtttt', 't@gmail.com', '$2y$10$0/EqYpbwrzSyBxPSHmW/kePN6DIMO0ZqepJga4X0Mp.umDYgPNXIe', 'kullanici'),
(7, 'zeynepp', 't@gmail.com', '$2y$10$ZOoNBa/EwzWLkm8/3kS9..je0DgfsuESJJV7wuJZPpw1c/HtLRVbW', 'kullanici'),
(8, 'melih123', 'melihbey461@gmail.com', '$2y$10$hcMvjdQtInx71ZJrZUfP2OXYtXyyXURo4DAezb1h1SfoIq/6tSxOG', 'kullanici'),
(10, 'suleO1234', 'sule1234@gmail.com', '$2y$10$RRAj0iXOq5nHkn32DhH27e/JQ8ZLWQDlhu55RcvMTYVGi8.trXiV2', 'kullanici'),
(11, 'zumra123', 'zumra123@gmail.com', '$2y$10$f5UBvp82Sq5Iba93936vMuk5xZGvHoKRTnqIKWLSteZOP/07z0pl2', 'kullanici'),
(12, 'test12345', 't@gmail.com', '$2y$10$9ultwNOX1w4v9JIYrHCVm.rFw6gKTpHyHdzcu2kUPun5DKsTm/P5i', 'kullanici');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seats`
--

CREATE TABLE `seats` (
  `id` int(6) UNSIGNED NOT NULL,
  `room` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `seats`
--

INSERT INTO `seats` (`id`, `room`, `status`, `created_at`) VALUES
(1, 'M-1', 0, '2025-04-19 19:22:40'),
(2, 'M-2', 0, '2025-02-22 17:50:49'),
(3, 'M-3', 0, '2025-02-22 17:50:49'),
(4, 'M-4', 0, '2025-02-22 18:07:01'),
(5, 'M-5', 0, '2025-02-22 18:07:01'),
(6, 'M-6', 1, '2025-02-22 18:07:01'),
(7, 'M-7', 0, '2025-02-22 18:07:01'),
(8, 'M-8', 1, '2025-02-22 18:07:01'),
(9, 'M-9', 0, '2025-02-22 18:07:01'),
(10, 'M-10', 1, '2025-02-22 18:07:01'),
(11, 'M-11', 1, '2025-02-25 13:42:24'),
(12, 'M-12', 1, '2025-02-25 13:42:24'),
(13, 'M-13', 1, '2025-02-25 13:43:39'),
(14, 'M-14', 1, '2025-02-25 13:43:40'),
(15, 'M-15', 1, '2025-02-25 13:43:40'),
(16, 'M-16', 1, '2025-02-25 13:43:40'),
(17, 'M-17', 1, '2025-02-25 13:43:40'),
(18, 'M-18', 1, '2025-02-25 13:44:17'),
(19, 'M-19', 1, '2025-02-25 13:44:17'),
(20, 'M-20', 1, '2025-02-25 13:44:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seatselection`
--

CREATE TABLE `seatselection` (
  `id` int(11) NOT NULL,
  `seat` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  `salon` varchar(200) NOT NULL,
  `tarih` varchar(20) NOT NULL,
  `saat` varchar(100) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `seatselection`
--

INSERT INTO `seatselection` (`id`, `seat`, `status`, `salon`, `tarih`, `saat`, `room_type`, `userid`) VALUES
(1, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(2, 'M-15', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(3, 'M-15', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-05-06', '08:00 - 12:00', 'bireysel', 7),
(4, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat', '2025-06-06', '16:00 - 20:00', 'grup', 1),
(5, 'M-1', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'grup', 7),
(6, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'grup', 7),
(7, 'M-1', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-05-05', '08:00 - 12:00', 'grup', 7),
(8, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-05-05', '08:00 - 12:00', 'grup', 7),
(9, 'M-2', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(10, 'M-13', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(11, 'M-12', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(12, 'M-5', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-05', '08:00 - 12:00', 'bireysel', 7),
(13, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat', '2025-06-07', '12:00 - 16:00', 'grup', 7),
(28, 'M-3', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat', '2025-05-28', '08:00 - 12:00', 'bireysel', 2),
(36, 'M-10', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-05-29', '08:00 - 12:00', 'grup', 7),
(40, 'M-5', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-06-04', '08:00 - 12:00', 'grup', 7),
(41, 'M-8', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-06-08', '12:00 - 16:00', 'grup', 7),
(42, 'M-4', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel', '2025-06-15', '08:00 - 12:00', 'grup', 7),
(43, 'M-7', 1, 'ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat', '2025-06-15', '08:00 - 12:00', 'grup', 7);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`);

--
-- Tablo için indeksler `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `seatselection`
--
ALTER TABLE `seatselection`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `seatselection`
--
ALTER TABLE `seatselection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
