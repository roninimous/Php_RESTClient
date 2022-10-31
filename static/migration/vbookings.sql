-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2019 at 01:00 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7


--
-- Table structure for table `vbookings`
--

CREATE TABLE `vbookings` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `venue` varchar(50) NOT NULL,
  `image_filename` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vbookings`
--

INSERT INTO `vbookings` (`id`, `first_name`, `last_name`, `email`, `mobile`, `booking_date`, `booking_time`, `venue`, `image_filename`) VALUES
(1, 'Steve', 'Jones', 'steve@yahoo.com', '0412345678', '2019-01-01', '15:24:00', '23 fortnite st Chatswood NSW 2065', 'venue1.jpg'),
(2, 'John', 'Smith', 'john@yahoo.com', '0413456789', '2019-02-01', '15:15:00', '45 Call of duty st Braybrook VIC 3026', 'venue2.jpg'),
(3, 'Peter', 'Davis', 'peter@yahoo.com', '0414567891', '2019-03-01', '15:05:00', '400 Dota road Bullen VIC 3071', 'venue3.jpg'),
(8, 'Kerry', 'Mitchel', 'kerry@yahoo.com', '0415678901', '2019-05-01', '12:34:00', '55 Diablo road Bondi NSW 2011', 'venue4.jpg'),
(5, 'Jenny', 'Burns', 'jenny@yahoo.com', '0416789012', '2019-06-01', '16:05:00', '365 Legends st Coogee NSW 2033', 'venue5.jpg'),
(6, 'Penny', 'Smith', 'penny@yahoo.com', '0417890123', '2019-07-01', '12:30:00', '67 Donkey Kong road Preston VIC 3072', 'venue6.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vbookings`
--
ALTER TABLE `vbookings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vbookings`
--
ALTER TABLE `vbookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;


