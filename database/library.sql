-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-03-11 08:02:44
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `library`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

CREATE TABLE `admin` (
  `AdminID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Account` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Role` enum('Admin','SuperAdmin','Librarian') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `admin`
--

INSERT INTO `admin` (`AdminID`, `Name`, `Account`, `Password`, `Role`) VALUES
('A0001', '高柏昇', 'gaopb', 'pb123456', 'Admin'),
('A0002', '陳姿均', 'chenadmin', 'cjpass789', 'SuperAdmin'),
('A0003', '何彥寧', 'yanlib', 'ynadmin88', 'Librarian'),
('A0004', '曾品柔', 'rolib88', 'rp987654', 'Admin'),
('A0005', 'Marinka', 'marinka', '940517', 'SuperAdmin');

-- --------------------------------------------------------

--
-- 資料表結構 `book`
--

CREATE TABLE `book` (
  `BookID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Title` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Category` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Status` enum('可借','已借出','遺失') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '可借'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `book`
--

INSERT INTO `book` (`BookID`, `Title`, `Author`, `Category`, `Status`) VALUES
('B0001', 'C語言入門', '陳大明', '程式設計', '已借出'),
('B0002', 'Python基礎	', '李大華	', '程式設計', '可借'),
('B0003', '世界名著	', '王美麗	', '文學', '已借出'),
('B0004', '資料結構	', '張國榮	', '電腦科學	', '已借出'),
('B0005', '人工智慧	', '林淑芬	', '科技', '已借出'),
('B0006', '時間的皺摺', '瑪德琳．蘭格爾', '科幻', '可借'),
('B0007', '解憂雜貨店', '東野圭吾', '小說', '已借出'),
('B0008', '被討厭的勇氣', '岸見一郎', '心理', '可借'),
('B0009', '人類大歷史', '尤瓦爾．赫拉利', '歷史', '已借出'),
('B0010', '我輩孤雛', '村上春樹', '文學', '可借'),
('B0011', '紅樓夢', '曹雪芹', '古典', '可借'),
('B0012', '原子習慣', '詹姆斯．克利爾', '勵志', '已借出'),
('B0013', '鬼滅之刃', '吾峠呼世晴', '漫畫', '可借'),
('B0014', 'Python進階', '李大華', '程式設計', '已借出'),
('B0015', '未來之眼', '林淑芬', '科技', '已借出'),
('B0016', '數據結構與演算法', '張國榮', '電腦科學', '可借'),
('B0017', '孤獨的行星', '林上春樹', '文學', '可借'),
('B0018', '紅樓夢選讀', '曹雪琴', '古典', '可借'),
('B0019', '歷史的碎片', '尤瓦爾．赫拉利', '歷史', '可借'),
('B0020', '克服焦慮的勇氣', '岸見一郎', '心理', '可借'),
('B0021', 'AI與未來社會', '林淑芬', '科技', '已借出'),
('B0022', '漫畫之心', '吾峠呼世晴', '漫畫', '已借出'),
('B0023', '世界散文選', '王美麗', '文學', '可借'),
('B0024', '小紅帽', '大野狼', '童話故事', '可借');

-- --------------------------------------------------------

--
-- 資料表結構 `loan`
--

CREATE TABLE `loan` (
  `LoanID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `BookID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ReaderID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `AdminID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `BorrowDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `DueDate` date NOT NULL,
  `Fine` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `loan`
--

INSERT INTO `loan` (`LoanID`, `BookID`, `ReaderID`, `AdminID`, `BorrowDate`, `ReturnDate`, `DueDate`, `Fine`) VALUES
('L0002', 'B0002', 'R0002', 'A0002', '2025-05-02', '2025-06-09', '2025-05-09', 310),
('L0003', 'B0003', 'R0003', 'A0003', '2025-05-03', '2025-05-10', '2025-05-10', 0),
('L0004', 'B0004', 'R0004', 'A0004', '2025-05-04', '2025-05-11', '2025-05-11', 0),
('L0005', 'B0003', 'R0002', 'A0001', '2025-06-04', '2025-06-04', '2025-06-11', 0),
('L0006', 'B0001', 'R0002', 'A0001', '2025-06-04', '2025-06-04', '2025-06-11', 0),
('L0007', 'B0003', 'R0002', 'A0001', '2025-06-04', '2025-06-04', '2025-06-11', 0),
('L0010', 'B0005', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0011', 'B0010', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0012', 'B0003', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0013', 'B0006', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0014', 'B0013', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0015', 'B0001', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0016', 'B0009', 'R0002', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0017', 'B0011', 'R0003', 'A0005', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0018', 'B0003', 'R0004', 'A0001', '2025-06-09', '2025-06-09', '2025-06-16', 0),
('L0019', 'B0004', 'R0002', 'A0001', '2025-06-09', NULL, '2025-06-16', 0),
('L0020', 'B0015', 'R0002', 'A0001', '2025-06-09', NULL, '2025-06-16', 0),
('L0023', 'B0005', 'R0004', 'A0001', '2025-06-10', NULL, '2025-06-17', 0),
('L0024', 'B0007', 'R0004', 'A0001', '2025-06-10', NULL, '2025-06-17', 0),
('L0025', 'B0014', 'R0004', 'A0001', '2025-06-10', NULL, '2025-06-17', 0),
('L0026', 'B0009', 'R0003', 'A0001', '2025-06-10', NULL, '2025-06-17', 0),
('L0027', 'B0019', 'R0003', 'A0001', '2025-06-10', '2025-06-10', '2025-06-17', 0),
('L0028', 'B0022', 'R0003', 'A0001', '2025-06-10', NULL, '2025-06-17', 0),
('L0029', 'B0001', 'R0007', 'A0001', '2025-06-10', NULL, '2025-06-17', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `reader`
--

CREATE TABLE `reader` (
  `ReaderID` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Account` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `reader`
--

INSERT INTO `reader` (`ReaderID`, `Name`, `Account`, `Password`, `Email`, `Phone`) VALUES
('R0002', '李志偉	', 'zhiwei', 'abcd5678', 'mailto:zhiwei@mail.com', '0922333444'),
('R0003', '黃小芳	', 'xiaofang', 'xiaopass', 'fang@mail.com', '0987654321'),
('R0004', '林建宏	', 'jianhong', 'lin2024', 'jian@mail.com', '0966888999'),
('R0005', '張婷	', 'tingting', 'tingpass	', 'ting@mail.com', '0977123456'),
('R0007', 'ABC', 'abcdef', '123456', 'abcdef@gmail.com', '0938736212');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- 資料表索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookID`);

--
-- 資料表索引 `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`LoanID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `BookID` (`BookID`),
  ADD KEY `ReaderID` (`ReaderID`);

--
-- 資料表索引 `reader`
--
ALTER TABLE `reader`
  ADD PRIMARY KEY (`ReaderID`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_3` FOREIGN KEY (`ReaderID`) REFERENCES `reader` (`ReaderID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
