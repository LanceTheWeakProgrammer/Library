-- Script was generated by Devart dbForge Studio for MySQL, Version 6.0.128.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 09/12/2023 8:46:52 am
-- Server version: 5.5.5-10.4.28-MariaDB
-- Client version: 4.1

--
-- Definition for database library
--
CREATE DATABASE IF NOT EXISTS library
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- 
-- Set default database
--
USE library;

--
-- Definition for table bookinventory
--
CREATE TABLE IF NOT EXISTS bookinventory (
  Book_id int(11) NOT NULL AUTO_INCREMENT,
  Title varchar(200) NOT NULL,
  Qty_stock int(50) NOT NULL,
  Qty_issued int(50) NOT NULL,
  Total int(50) NOT NULL,
  Published_date date NOT NULL,
  PRIMARY KEY (Book_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Definition for table bookrequest
--
CREATE TABLE IF NOT EXISTS bookrequest (
  Request_id int(11) NOT NULL AUTO_INCREMENT,
  Student_id int(11) NOT NULL,
  Book_id int(11) NOT NULL,
  Requestdttm datetime NOT NULL,
  RequestedBy varchar(100) NOT NULL,
  RequestedFor varchar(100) NOT NULL,
  Qty_requested int(50) NOT NULL,
  Requeststatus varchar(100) NOT NULL,
  Updatedby varchar(100) NOT NULL,
  Updatedttm datetime NOT NULL,
  PRIMARY KEY (Request_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 16
AVG_ROW_LENGTH = 1092
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Definition for table student
--
CREATE TABLE IF NOT EXISTS student (
  Student_id int(11) NOT NULL AUTO_INCREMENT,
  First_name varchar(100) NOT NULL,
  Last_name varchar(100) NOT NULL,
  Birthday date NOT NULL,
  Gender varchar(10) NOT NULL,
  Contact_number varchar(100) NOT NULL,
  Email varchar(100) NOT NULL,
  Year varchar(100) NOT NULL,
  Section varchar(100) NOT NULL,
  Course varchar(100) NOT NULL,
  Address varchar(255) NOT NULL,
  Active_ind int(1) NOT NULL,
  PRIMARY KEY (Student_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 682
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Definition for table user
--
CREATE TABLE IF NOT EXISTS user (
  User_id int(11) NOT NULL AUTO_INCREMENT,
  Username varchar(100) NOT NULL,
  Password varchar(100) NOT NULL,
  role varchar(100) NOT NULL,
  First_name varchar(100) NOT NULL,
  Last_name varchar(100) NOT NULL,
  Email varchar(100) NOT NULL,
  Contact_number varchar(100) NOT NULL,
  Active_ind int(1) NOT NULL,
  PRIMARY KEY (User_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci
ROW_FORMAT = DYNAMIC;

DELIMITER $$

--
-- Definition for procedure getBooks
--
CREATE PROCEDURE getBooks ()
SQL SECURITY INVOKER
BEGIN
  SELECT
    *
  FROM bookinventory;
END
$$

--
-- Definition for procedure getRequestBook
--
CREATE PROCEDURE getRequestBook (IN _book_id int(11))
SQL SECURITY INVOKER
BEGIN
  SELECT
    *
  FROM bookinventory
  WHERE Book_id = _book_id;
END
$$

--
-- Definition for procedure getStudents
--
CREATE PROCEDURE getStudents ()
SQL SECURITY INVOKER
BEGIN
  SELECT
    *
  FROM student;
END
$$

--
-- Definition for procedure getUser
--
CREATE PROCEDURE getUser ()
SQL SECURITY INVOKER
BEGIN
  SELECT
    *
  FROM user;
END
$$

--
-- Definition for procedure insertBook
--
CREATE PROCEDURE insertBook (
  IN name varchar(200)
, IN stock int(50)
, IN pub_date date)
SQL SECURITY INVOKER
BEGIN
  INSERT INTO library.bookinventory (Title
  , Qty_stock
  , Qty_issued
  , Total
  , Published_date)
    VALUES (name, stock, 0, stock, pub_date);
END
$$

--
-- Definition for procedure insertBookRequest
--
CREATE PROCEDURE insertBookRequest (
IN _student_id int(11),
IN _book_id int(11),
IN _requestedBy varchar(100),
IN _requestedFor varchar(100),
IN qty_requested int(50),
IN requeststatus varchar(100),
IN updatedby varchar(100))
SQL SECURITY INVOKER
BEGIN
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
END
$$

--
-- Definition for procedure insertStudent
--
CREATE PROCEDURE insertStudent (
  IN _first_name varchar(100)
, IN _last_name varchar(100)
, IN _birthday date
, IN _gender varchar(10)
, IN _contact_number varchar(100)
, IN _email varchar(100)
, IN _year varchar(100)
, IN _section varchar(100)
, IN _course varchar(100)
, IN _address varchar(255))
SQL SECURITY INVOKER
BEGIN
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
END
$$

--
-- Definition for procedure insertUser
--
CREATE PROCEDURE insertUser (
  IN username varchar(100)
, IN password varchar(100)
, IN _role varchar(100)
, IN _first_name varchar(10)
, IN _last_name varchar(10)
, IN _email varchar(100)
, IN _contact_number varchar(100))
SQL SECURITY INVOKER
BEGIN
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
END
$$

--
-- Definition for procedure resetPassword
--
CREATE PROCEDURE resetPassword (
  IN _user_id int(11)
, IN password varchar(100))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.user
  SET Password = password
  WHERE User_id = _user_id;
END
$$

--
-- Definition for procedure updateBook
--
CREATE PROCEDURE updateBook (
  IN _book_id int(11)
, IN name varchar(200)
, IN qty_stock int(50)
, IN pub_date date)
SQL SECURITY INVOKER
BEGIN
  UPDATE library.bookinventory
  SET Title = name,
      Qty_stock = qty_stock,
      Total = qty_stock,
      Published_date = pub_date
  WHERE Book_id = _book_id;
END
$$

--
-- Definition for procedure updateBookRequest
--
CREATE PROCEDURE updateBookRequest (
  IN _request_id int(11)
, IN requeststatus varchar(100)
, IN updatedby varchar(100))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.bookrequest
  SET Requeststatus = requeststatus,
      Updatedby = updatedby,
      Updatedttm = NOW()
  WHERE Request_id = _request_id;
END
$$

--
-- Definition for procedure updateBookStock
--
CREATE PROCEDURE updateBookStock (
  IN _book_id int(11)
, IN qty_stock int(50)
, IN qty_issued int(50))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.bookinventory
  SET Qty_stock = qty_stock,
      Qty_issued = qty_issued
  WHERE Book_id = _book_id;
END
$$

--
-- Definition for procedure updateStudent
--
CREATE PROCEDURE updateStudent (
  IN _student_id int(11)
, IN _first_name varchar(100)
, IN _last_name varchar(100)
, IN _birthday date
, IN _gender varchar(10)
, IN _contact_number varchar(100)
, IN _email varchar(100)
, IN _year varchar(100)
, IN _section varchar(100)
, IN _course varchar(100)
, IN _address varchar(255))
SQL SECURITY INVOKER
BEGIN
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
END
$$

--
-- Definition for procedure updateStudentStatus
--
CREATE PROCEDURE updateStudentStatus (IN _student_id int(11)
, IN active_ind int(1))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.student
  SET Active_ind = active_ind
  WHERE Student_id = _student_id;
END
$$

--
-- Definition for procedure updateUser
--
CREATE PROCEDURE updateUser (IN _user_id varchar(100)
, IN _role varchar(100)
, IN _first_name varchar(10)
, IN _last_name varchar(10)
, IN _email varchar(100)
, IN _contact_number varchar(100))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.user
  SET role = _role,
      First_name = _first_name,
      Last_name = _last_name,
      Email = _email,
      Contact_number = _contact_number
  WHERE User_id = _user_id;
END
$$

--
-- Definition for procedure updateUserStatus
--
CREATE PROCEDURE updateUserStatus (IN _user_id int(11)
, IN active_ind int(1))
SQL SECURITY INVOKER
BEGIN
  UPDATE library.user
  SET Active_ind = active_ind
  WHERE User_id = _user_id;
END
$$

-- Definition for procedure searchBookRequest
CREATE PROCEDURE searchBookRequest(
  IN _requested_by VARCHAR(100)
)
  SQL SECURITY INVOKER
BEGIN
  SELECT br.*, bi.Title as BookTitle
  FROM bookrequest br
  LEFT JOIN bookinventory bi ON br.Book_id = bi.Book_id
  WHERE _requested_by IS NULL OR br.RequestedBy LIKE CONCAT('%', _requested_by, '%');
END
$$

-- Definition for procedure searchStudent
--
CREATE PROCEDURE searchStudent (IN _first_name VARCHAR(100), IN _last_name VARCHAR(100))
SQL SECURITY INVOKER
BEGIN
  SELECT *
  FROM student
  WHERE (_first_name IS NULL OR First_name LIKE CONCAT('%', _first_name, '%'))
    AND (_last_name IS NULL OR Last_name LIKE CONCAT('%', _last_name, '%'));
END
$$

-- Definition for procedure getDashboardData
CREATE PROCEDURE getDashboardData ()
SQL SECURITY INVOKER
BEGIN
  DECLARE total_students INT;
  DECLARE total_books_requested INT;
  DECLARE total_books_approved INT;
  DECLARE overall_total_books INT;

  SELECT COUNT(*) INTO total_students FROM student;

  SELECT SUM(Qty_requested) INTO total_books_requested FROM bookrequest;

  SELECT COUNT(*) INTO total_books_approved FROM bookrequest WHERE Requeststatus = 'Approved';

  SELECT SUM(Qty_stock) INTO overall_total_books FROM bookinventory;

  SELECT total_students, total_books_requested, total_books_approved, overall_total_books;
END
$$

DELIMITER ;