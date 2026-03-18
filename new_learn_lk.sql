-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 02:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_learn_lk`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `assignment_id` varchar(20) NOT NULL,
  `assignment_name` varchar(100) DEFAULT NULL,
  `assignment_datetime` datetime DEFAULT NULL,
  `other` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `class_datetime` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `class_status` enum('started','stopped') NOT NULL DEFAULT 'stopped'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `class_datetime`, `year`, `subject`, `is_active`, `class_status`) VALUES
('Bio-2055', 'Bio Pp class', 'Sunday 8.00 PM - 11.00 PM', 2025, 'Biology', 0, 'stopped'),
('Bio-3376', 'Biology theory class', 'Tuesday 8.00 AM - 12.00 PM', 2026, 'Biology', 0, 'stopped'),
('Bio-4039', 'Bio Theory class', 'Monday 8.00 AM - 12.00 PM', 2025, 'Biology', 0, 'stopped'),
('Bio-4144', 'Biology Revition class', 'Wednesday 8.00 AM - 12.00 PM', 2026, 'Biology', 0, 'stopped'),
('Bio-5495', 'Biology theory class', 'Sunday 8.00 AM - 12.00 PM', 2026, 'Biology', 0, 'stopped'),
('Bio-8431', 'Bio Revition class', 'Sunday 8.00 AM - 2.00 PM', 2025, 'Biology', 0, 'stopped'),
('Chem-1117', 'Biology theory class', 'Sunday 8.00 AM - 12.00 PM', 2025, 'Chemistry', 0, 'stopped'),
('Chem-2214', 'Chemistry Revision class', 'Sunday 8.00 AM - 12.00 PM', 2025, 'Chemistry', 0, 'stopped'),
('Chem-6705', 'Chemistry Theory class', 'Sunday 8.00 AM - 12.00 PM', 2025, 'Chemistry', 0, 'stopped'),
('Chem-7019', 'Chemistry Revision class', 'Monday 12.30 AM - 4.00 PM', 2026, 'Chemistry', 0, 'stopped'),
('Chem-8560', 'Chemistry Theory class', 'Friday 8.00 AM - 12.00 PM', 2026, 'Chemistry', 0, 'stopped'),
('Chem-8564', 'Biology theory class', 'Sunday 8.00 AM - 12.00 PM', 2025, 'Chemistry', 0, 'stopped');

-- --------------------------------------------------------

--
-- Table structure for table `class_resource`
--

CREATE TABLE `class_resource` (
  `id` int(11) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_resource`
--

INSERT INTO `class_resource` (`id`, `class_id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 'Chem-2214', '𝗖𝗵𝗮𝗿𝗮𝗰𝘁𝗲𝗿𝘀 𝗜𝗻 𝗖𝗼𝗺𝗶𝗰.pdf', 'uploads/1749726478_𝗖𝗵𝗮𝗿𝗮𝗰𝘁𝗲𝗿𝘀 𝗜𝗻 𝗖𝗼𝗺𝗶𝗰.pdf', '2025-06-12 11:07:58'),
(2, 'Chem-2214', '0105 (2).mp4', 'uploads/1749726516_0105 (2).mp4', '2025-06-12 11:08:36'),
(3, 'Bio-2055', '1749726478_𝗖𝗵𝗮𝗿𝗮𝗰𝘁𝗲𝗿𝘀 𝗜𝗻 𝗖𝗼𝗺𝗶𝗰.pdf', 'uploads/1749749676_1749726478_𝗖𝗵𝗮𝗿𝗮𝗰𝘁𝗲𝗿𝘀 𝗜𝗻 𝗖𝗼𝗺𝗶𝗰.pdf', '2025-06-12 17:34:36'),
(6, 'Chem-1117', '1749796308_SAD Short Note Final 2 (1) (1).pdf', 'uploads/1749801894_1749796308_SAD Short Note Final 2 (1) (1).pdf', '2025-06-13 08:04:54'),
(7, 'Chem-1117', 'Record_2025_06_09_11_08_27_74.mp4', 'uploads/1749801908_Record_2025_06_09_11_08_27_74.mp4', '2025-06-13 08:05:08'),
(8, 'Bio-5495', '2nd Paper Answers - 2022.pdf', 'uploads/1754647210_2nd Paper Answers - 2022.pdf', '2025-08-08 10:00:10'),
(9, 'Bio-5495', 'Record_2025_07_28_10_34_57_519.mp4', 'uploads/1754647284_Record_2025_07_28_10_34_57_519.mp4', '2025-08-08 10:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `c_subject`
--

CREATE TABLE `c_subject` (
  `class_id` varchar(20) NOT NULL,
  `subject_id` varchar(20) NOT NULL,
  `assignment_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` varchar(20) NOT NULL,
  `class_id` varchar(20) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `class_id`, `subject`, `duration`, `total_questions`, `created_by`) VALUES
('bio-01', 'Bio-2055', 'Biology', 30, 10, 'Bio\\4568'),
('bio-02', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568'),
('bio-03', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568'),
('bio-05', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568'),
('bio-08', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568'),
('bio-09', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568'),
('bio-10', 'Bio-2055', 'Biology', 2, 2, 'Bio\\4568');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `st_id` varchar(20) NOT NULL,
  `st_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `p_no` varchar(15) NOT NULL,
  `al_year` year(4) NOT NULL,
  `al_stream` varchar(50) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`st_id`, `st_name`, `dob`, `email`, `address`, `gender`, `p_no`, `al_year`, `al_stream`, `nic`, `password`, `profile_pic`) VALUES
('Bio\\2025\\0893', 'V.G.T. Sampath', '2003-12-10', 'vgtharindu@gmail.com', '227/1 ginigalahen,radawela', 'Male', '0772010733', '2025', 'Science', '200334400893', 'vgts1234', 'uploads/1749737546_IMG-20231121-WA0160.jpg'),
('Bio\\2025\\3678', 'bashitha', '2025-06-17', 'basi@gmail.com', 'kaburupitiya', 'Male', '078 4567890', '2025', 'Science', '200112233678', '$2y$10$8HhNnItm9PbMsv3rbwbTgOSq.nZq682wKKHQKl3zbzgHcYcoAkoDK', NULL),
('Bio\\2025\\4567', 'K.D. Maduranga', '2002-06-04', 'maduranga@gmail.com', 'Colombo 07', 'Male', '078 6789765', '2025', 'Science', '200223344567', '$2y$10$VVXspuMZJBXM32XBROHM0emg/F5wb4KRzeli8msQPvyEioc.V2lzK', 'uploads/1749795111_download (5).png'),
('Bio\\2026\\3099', 'K.D.Sachinthana Geewindi', '2001-12-05', 'k.sachinthanageewindi@gmail.com', 'Temple Rd,Galle', 'Female', '0711809351', '2026', 'Science', '200163303099', '$2y$10$6Iu8J9ZeHDmEBHn9EkrtmuLmx9U8LhlHV7U9N6W87DNKgz4VgPFMG', 'uploads/1749802113_student.png'),
('Bio\\2026\\3543', 'Student', '2001-02-08', 'student@gmail.com', 'colombo 07', 'Male', '0788234567', '2026', 'Science', '200114433543', '$2y$10$NBiHCPnYqiWn9vWVB7X9heGIUEwBCVu2RVvbIIw.SZDZI/LYCPQ4W', 'uploads/1754667498_download (1).png');

-- --------------------------------------------------------

--
-- Table structure for table `st_class`
--

CREATE TABLE `st_class` (
  `st_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_class`
--

INSERT INTO `st_class` (`st_id`, `class_id`) VALUES
('Bio\\2025\\0893', 'Bio-2055'),
('Bio\\2025\\0893', 'Bio-4039'),
('Bio\\2025\\0893', 'Chem-2214'),
('Bio\\2025\\0893', 'Chem-6705'),
('Bio\\2026\\3543', 'Bio-5495');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(20) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` varchar(20) NOT NULL,
  `t_name` varchar(100) DEFAULT NULL,
  `subject_id` varchar(20) DEFAULT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `nic` varchar(12) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `al_stream` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `t_name`, `subject_id`, `detail`, `nic`, `phone`, `email`, `al_stream`, `password`, `gender`, `profile_pic`) VALUES
('Bio\\2891', 'SURANGA LAKMAL', NULL, 'B.sC COLOMBO', '200234562891', '0715678900', 'SURANGA@GMAIL.COM', 'Biology', '$2y$10$dsfhNkyzeUTaf0I4hvNQQeGN5cmtn2d9E4gVekSMLva0N6gx08eIC', 'Male', 'uploads/1749801722_teacher.jpg'),
('Bio\\4568', 'K.W.Wijesuriya', NULL, 'B.Sc Hons Colombo', '197872244567', '071 1423455', 'wijesuriya98@gmail.com', 'Biology', 'w1234', 'Male', 'uploads/1749657169_images (1).jpg'),
('Bio\\7890', 'H.S. Rathnayake', NULL, 'B.Sc Hons Colombo', '198834567890', '071 3456765', 'rathnayake@gmail.com', 'Biology', '$2y$10$yB.sM7XsFjrZH5NsINuCqusbLAnwJ8zFBj2CN7rXgx83a.jxc1U7a', 'Male', 'uploads/1749795369_images (1).jpg'),
('Math\\4567', 'bashitha', NULL, '', '200113344567', '078 4567890', 'basi@gmail.com', 'Maths', '$2y$10$mGxAsxEEaM6Xq2NvQW5EWeUUbjZrWN./8ERI03awpsoYWyzOwB7EG', 'Male', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_class`
--

CREATE TABLE `t_class` (
  `teacher_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_class`
--

INSERT INTO `t_class` (`teacher_id`, `class_id`) VALUES
('Bio\\2891', 'Chem-1117'),
('Bio\\4568', 'Bio-2055'),
('Bio\\4568', 'Bio-3376'),
('Bio\\4568', 'Bio-4039'),
('Bio\\4568', 'Bio-4144'),
('Bio\\4568', 'Bio-8431'),
('Bio\\7890', 'Bio-5495'),
('Bio\\7890', 'Chem-8564');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `class_resource`
--
ALTER TABLE `class_resource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `c_subject`
--
ALTER TABLE `c_subject`
  ADD PRIMARY KEY (`class_id`,`subject_id`,`assignment_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `st_class`
--
ALTER TABLE `st_class`
  ADD PRIMARY KEY (`st_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `t_class`
--
ALTER TABLE `t_class`
  ADD PRIMARY KEY (`teacher_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_resource`
--
ALTER TABLE `class_resource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_resource`
--
ALTER TABLE `class_resource`
  ADD CONSTRAINT `class_resource_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `c_subject`
--
ALTER TABLE `c_subject`
  ADD CONSTRAINT `c_subject_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `c_subject_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`),
  ADD CONSTRAINT `c_subject_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`);

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `exam_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `teacher` (`teacher_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE;

--
-- Constraints for table `st_class`
--
ALTER TABLE `st_class`
  ADD CONSTRAINT `st_class_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`),
  ADD CONSTRAINT `st_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `t_class`
--
ALTER TABLE `t_class`
  ADD CONSTRAINT `t_class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
