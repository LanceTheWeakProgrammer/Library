-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2023 at 12:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getBooks` ()  SQL SECURITY INVOKER BEGIN
  SELECT
    *
  FROM bookinventory;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getDashboardData` ()  SQL SECURITY INVOKER BEGIN
  DECLARE total_students INT;
  DECLARE total_books_requested INT;
  DECLARE total_books_approved INT;
  DECLARE overall_total_books INT;
  DECLARE total_pending_requests INT;  -- New variable

  SELECT COUNT(*) INTO total_students FROM student;

  SELECT SUM(Qty_requested) INTO total_books_requested FROM bookrequest;

  SELECT COUNT(*) INTO total_books_approved FROM bookrequest WHERE Requeststatus = 'Approved';

  SELECT SUM(Qty_stock) INTO overall_total_books FROM bookinventory;

  -- Calculate total pending requests
  SELECT COUNT(*) INTO total_pending_requests FROM bookrequest WHERE Requeststatus = 'Pending For Approval';

  -- Return all values
  SELECT total_students, total_books_requested, total_books_approved, overall_total_books, total_pending_requests;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRequestBook` (IN `_book_id` INT(11))  SQL SECURITY INVOKER BEGIN
  SELECT
    *
  FROM bookinventory
  WHERE Book_id = _book_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getStudents` ()  SQL SECURITY INVOKER BEGIN
  SELECT
    *
  FROM student;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUser` ()  SQL SECURITY INVOKER BEGIN
  SELECT
    *
  FROM user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserById` (IN `userId` INT)   BEGIN
    SELECT * FROM library.user WHERE User_id = userId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBook` (IN `name` VARCHAR(200), IN `stock` INT(50), IN `pub_date` DATE)  SQL SECURITY INVOKER BEGIN
  INSERT INTO library.bookinventory (Title
  , Qty_stock
  , Qty_issued
  , Total
  , Pub_date)
    VALUES (name, stock, 0, stock, pub_date);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBookRequest` (IN `_student_id` INT(11), IN `_book_id` INT(11), IN `_requestedBy` VARCHAR(100), IN `_requestedFor` VARCHAR(100), IN `qty_requested` INT(50), IN `requeststatus` VARCHAR(100), IN `updatedby` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  INSERT INTO library.bookrequest (Student_id
  , Book_id
  , Requestdttm
  , RequestedBy
  , RequestedFor
  , Qty_requested
  , Requeststatus
  , Updatedby
  , Updatedttm)
    VALUES (_student_id, _book_id, NOW(), _requestedBy, _requestedFor, qty_requested, requeststatus, updatedby, NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertStudent` (IN `_first_name` VARCHAR(100), IN `_last_name` VARCHAR(100), IN `_birthday` DATE, IN `_gender` VARCHAR(10), IN `_contact_number` VARCHAR(100), IN `_email` VARCHAR(100), IN `_year` VARCHAR(100), IN `_section` VARCHAR(100), IN `_course` VARCHAR(100), IN `_address` VARCHAR(255))  SQL SECURITY INVOKER BEGIN
  INSERT INTO student (
    First_name
  , Last_name
  , Birthday
  , Gender
  , Contact_number
  , Email
  , Year
  , Section
  , Course
  , Address
  , Active_ind)
    VALUES (_first_name, _last_name, _birthday, _gender, _contact_number, _email, _year, _section, _course, _address, 1);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUser` (IN `username` VARCHAR(100), IN `password` VARCHAR(100), IN `_role` VARCHAR(100), IN `_first_name` VARCHAR(10), IN `_last_name` VARCHAR(10), IN `_email` VARCHAR(100), IN `_contact_number` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  INSERT INTO library.user (
    Username
  , Password
  , role
  , First_name
  , Last_name
  , Email
  , Contact_number
  , Active_ind)
    VALUES (username, password, _role, _first_name, _last_name, _email, _contact_number, 1);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `resetPassword` (IN `_user_id` INT(11), IN `password` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  UPDATE library.user
  SET Password = password
  WHERE User_id = _user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchBookRequest` (IN `_requested_by` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  SELECT
    br.*,
    bi.Title AS BookTitle
  FROM bookrequest br
    LEFT JOIN bookinventory bi
      ON br.Book_id = bi.Book_id
  WHERE _requested_by IS NULL OR br.RequestedBy LIKE CONCAT('%', _requested_by, '%');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchStudent` (IN `_first_name` VARCHAR(100), IN `_last_name` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  SELECT *
  FROM student
  WHERE (_first_name IS NULL OR First_name LIKE CONCAT('%', _first_name, '%'))
    AND (_last_name IS NULL OR Last_name LIKE CONCAT('%', _last_name, '%'));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBook` (IN `_book_id` INT(11), IN `name` VARCHAR(200), IN `qty_stock` INT(50), IN `pub_date` DATE)  SQL SECURITY INVOKER BEGIN
  UPDATE library.bookinventory
  SET Title = name,
      Qty_stock = qty_stock,
      Total = qty_stock,
      Pub_date = pub_date
  WHERE Book_id = _book_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBookRequest` (IN `_request_id` INT(11), IN `requeststatus` VARCHAR(100), IN `updatedby` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  UPDATE library.bookrequest
  SET Requeststatus = requeststatus,
      Updatedby = updatedby,
      Updatedttm = NOW()
  WHERE Request_id = _request_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBookStock` (IN `_book_id` INT(11), IN `qty_stock` INT(50), IN `qty_issued` INT(50))  SQL SECURITY INVOKER BEGIN
  UPDATE library.bookinventory
  SET Qty_stock = qty_stock,
      Qty_issued = qty_issued
  WHERE Book_id = _book_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateStudent` (IN `_student_id` INT(11), IN `_first_name` VARCHAR(100), IN `_last_name` VARCHAR(100), IN `_birthday` DATE, IN `_gender` VARCHAR(10), IN `_contact_number` VARCHAR(100), IN `_email` VARCHAR(100), IN `_year` VARCHAR(100), IN `_section` VARCHAR(100), IN `_course` VARCHAR(100), IN `_address` VARCHAR(255))  SQL SECURITY INVOKER BEGIN
  UPDATE library.student
  SET First_name = _first_name,
      Last_name = _last_name,
      Birthday = _birthday,
      Gender = _gender,
      Contact_number = _contact_number,
      Email = _email,
      Year = _year,
      Section = _section,
      Course = _course,
      Address = _address
  WHERE Student_id = _student_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateStudentStatus` (IN `_student_id` INT(11), IN `active_ind` INT(1))  SQL SECURITY INVOKER BEGIN
  UPDATE library.student
  SET Active_ind = active_ind
  WHERE Student_id = _student_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUser` (IN `_user_id` VARCHAR(100), IN `_role` VARCHAR(100), IN `_first_name` VARCHAR(10), IN `_last_name` VARCHAR(10), IN `_email` VARCHAR(100), IN `_contact_number` VARCHAR(100))  SQL SECURITY INVOKER BEGIN
  UPDATE library.user
  SET role = _role,
      First_name = _first_name,
      Last_name = _last_name,
      Email = _email,
      Contact_number = _contact_number
  WHERE User_id = _user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserStatus` (IN `_user_id` INT(11), IN `active_ind` INT(1))  SQL SECURITY INVOKER BEGIN
  UPDATE library.user
  SET Active_ind = active_ind
  WHERE User_id = _user_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookinventory`
--

CREATE TABLE `bookinventory` (
  `Book_id` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Qty_stock` int(50) NOT NULL,
  `Qty_issued` int(50) NOT NULL,
  `Total` int(50) NOT NULL,
  `Pub_date` date NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bookinventory`
--

INSERT INTO `bookinventory` (`Book_id`, `Title`, `Qty_stock`, `Qty_issued`, `Total`, `Pub_date`) VALUES
(9, 'Hello Math', 198, 2, 198, '2006-12-02'),
(10, 'Hello History', 100, 0, 100, '2003-12-05'),
(11, 'New Book!', 1950, 0, 1950, '2020-12-20');

--
-- Triggers `bookinventory`
--
DELIMITER $$
CREATE TRIGGER `before_insert_bookinventory` BEFORE INSERT ON `bookinventory` FOR EACH ROW BEGIN
    IF NEW.Qty_stock < 0 THEN
        SET NEW.Qty_stock = 0;
    END IF;

    IF NEW.Qty_issued < 0 THEN
        SET NEW.Qty_issued = 0;
    END IF;

    IF NEW.Total < 0 THEN
        SET NEW.Total = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_bookinventory` BEFORE UPDATE ON `bookinventory` FOR EACH ROW BEGIN
    IF NEW.Qty_stock < 0 THEN
        SET NEW.Qty_stock = 0;
    END IF;

    IF NEW.Qty_issued < 0 THEN
        SET NEW.Qty_issued = 0;
    END IF;

    IF NEW.Total < 0 THEN
        SET NEW.Total = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookrequest`
--

CREATE TABLE `bookrequest` (
  `Request_id` int(11) NOT NULL,
  `Student_id` int(11) NOT NULL,
  `Book_id` int(11) NOT NULL,
  `Requestdttm` datetime NOT NULL,
  `RequestedBy` varchar(100) NOT NULL,
  `RequestedFor` varchar(100) NOT NULL,
  `Qty_requested` int(50) NOT NULL,
  `Requeststatus` varchar(100) NOT NULL,
  `Updatedby` varchar(100) NOT NULL,
  `Updatedttm` datetime NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1092 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bookrequest`
--

INSERT INTO `bookrequest` (`Request_id`, `Student_id`, `Book_id`, `Requestdttm`, `RequestedBy`, `RequestedFor`, `Qty_requested`, `Requeststatus`, `Updatedby`, `Updatedttm`) VALUES
(8, 7, 9, '2023-12-29 17:17:28', 'Lai Babbers', 'Lancelot Javate', 10, 'Approved', 'Lancery Javate', '2023-12-29 17:17:40'),
(9, 7, 9, '2023-12-29 17:23:06', 'Lai Babbers', 'Lancelot Javate', 10, 'Approved', 'Lancery Javate', '2023-12-29 17:23:13'),
(10, 7, 9, '2023-12-29 17:39:05', 'Lai Babbers', 'Lancelot Javate', 1, 'Declined', 'Lancery Javate', '2023-12-29 17:42:53'),
(11, 8, 9, '2023-12-29 17:42:41', 'Lai Babbers', 'Joshua Junny', 1, 'Approved', 'Lancery Javate', '2023-12-29 17:42:47'),
(12, 8, 9, '2023-12-29 17:48:45', 'Lai Babbers', 'Joshua Junny', 1, 'Approved', 'Lancery Javate', '2023-12-29 17:48:48'),
(13, 10, 9, '2023-12-29 17:49:02', 'Lai Babbers', 'Hello Test', 2, 'Declined', 'Lancery Javate', '2023-12-29 17:49:07'),
(14, 8, 11, '2023-12-29 17:55:04', 'Lai Babbers', 'Joshua Junny', 2, 'Declined', 'Lancery Javate', '2023-12-29 17:55:17'),
(15, 8, 11, '2023-12-29 17:55:33', 'Lai Babbers', 'Joshua Junny', 100, 'Approved', 'Lancery Javate', '2023-12-29 17:55:38'),
(16, 8, 11, '2023-12-29 17:56:17', 'Lai Babbers', 'Joshua Junny', 100, 'Declined', 'Lancery Javate', '2023-12-29 17:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Student_id` int(11) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Birthday` date NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Contact_number` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Year` varchar(100) NOT NULL,
  `Section` varchar(100) NOT NULL,
  `Course` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Active_ind` int(1) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=682 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`Student_id`, `First_name`, `Last_name`, `Birthday`, `Gender`, `Contact_number`, `Email`, `Year`, `Section`, `Course`, `Address`, `Active_ind`) VALUES
(7, 'Lancelot', 'Javate', '2002-12-07', 'Male', '92736583658', 'lancejavate@email.com', '3', 'A', 'BSIT', 'Poblacion, XYZ', 0),
(8, 'Joshua', 'Junny', '2002-08-15', 'Male', '9383628271', 'joshuaaro@email.com', '3', 'A', 'BSIT', 'Catarman, ABC', 1),
(9, 'Mommers', 'Dadders', '1992-12-07', 'Female', '98375637282', 'mommers@dadders.com', '4', 'B', 'BSHM', 'Poblacion, ABC', 0),
(10, 'Hello', 'Test', '2002-12-07', 'Male', '93872926482', 'hello@test.com', '2', 'B', 'BEeD', 'ABC, Gabi', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_id` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contact_number` varchar(100) NOT NULL,
  `Active_ind` int(1) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1489 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_id`, `Username`, `Password`, `role`, `First_name`, `Last_name`, `Email`, `Contact_number`, `Active_ind`) VALUES
(3, 'Lance', 'e10adc3949ba59abbe56e057f20f883e', 'Librarian', 'Lancery', 'Javate', 'lancejavate@email.com', '9337272629', 1),
(4, 'Joshua', '25d55ad283aa400af464c76d713c07ad', 'Librarian', 'Joshua', 'FunnyBunny', 'funnybunny@email.com', '927496737891', 1),
(5, 'Lai', '25d55ad283aa400af464c76d713c07ad', 'Clerk', 'Lai', 'Babbers', 'babbers@email.com', '98375937263', 1),
(6, 'Test', '699040f05d08e6a816679863f047e2a5', 'Clerk', 'Funny', 'Bunny', 'funny@bunny.email.com', '9283759372', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookinventory`
--
ALTER TABLE `bookinventory`
  ADD PRIMARY KEY (`Book_id`);

--
-- Indexes for table `bookrequest`
--
ALTER TABLE `bookrequest`
  ADD PRIMARY KEY (`Request_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`Student_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookinventory`
--
ALTER TABLE `bookinventory`
  MODIFY `Book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bookrequest`
--
ALTER TABLE `bookrequest`
  MODIFY `Request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `Student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
