-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 01:07 AM
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
-- Database: `inventory`
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `deleteCategory` (IN `categoryId` INT(11))   BEGIN
DELETE FROM category 
WHERE id = categoryId;
END$$

CREATE PROCEDURE `deleteProduct` (IN `productId` INT(11))   BEGIN
DELETE FROM product 
WHERE id = productId;
END$$

CREATE PROCEDURE `getAllProducts` ()   BEGIN
SELECT product.*, category.name AS category
FROM product
INNER JOIN category ON category.id = product.fk_category_id
WHERE product.name != '';
END$$

CREATE PROCEDURE `getProductByCategory` (IN `categoryId` INT(11))   BEGIN
SELECT product.*, category.name AS category
    FROM product
    INNER JOIN category ON category.id = product.fk_category_id
    WHERE fk_category_id = categoryId;
END$$

CREATE PROCEDURE `getProductById` (IN `productId` INT(11))   BEGIN
SELECT *
    FROM product
    WHERE id = productId;
END$$

CREATE PROCEDURE `getProductByName` (IN `productName` VARCHAR(128))   BEGIN
SELECT product.*, category.name AS category
    FROM product
    INNER JOIN category ON category.id = product.fk_category_id
    WHERE product.name LIKE CONCAT('%', productName , '%');
END$$

CREATE PROCEDURE `getProductByNameAndCategory` (IN `productName` VARCHAR(128), `categoryId` INT(11))   BEGIN
SELECT product.*, category.name AS category
    FROM product
    INNER JOIN category ON category.id = product.fk_category_id
    WHERE fk_category_id = categoryId
    AND product.name LIKE CONCAT('%', productName , '%');
END$$

CREATE PROCEDURE `insertCategory` (`categoryName` VARCHAR(128))   BEGIN
INSERT INTO category(name) VALUES (categoryName);
END$$

CREATE PROCEDURE `insertProduct` (`productName` VARCHAR(128), `productCategory_id` INT(11), `productPicture` VARCHAR(128), `productPrice` DOUBLE, `productQuantity` INT(11), `productExpiration_date` DATE)   BEGIN
INSERT INTO product(`name`,`fk_category_id`,`picture`,`price`,`quantity`,`expiration_date`) 
VALUES (productName, productCategory_id, productPicture, productPrice, productQuantity, productExpiration_date);
END$$

CREATE PROCEDURE `updateCategory` (IN `categoryId` INT(11), `categoryName` VARCHAR(250))   BEGIN
UPDATE category 
SET `name` = categoryName 
WHERE id = categoryId;
END$$

CREATE PROCEDURE `updateProduct` (IN `productId` INT(11), `productName` VARCHAR(128), `categoryId` INT(11), `productPrice` DOUBLE, `productQuantity` INT(11), `productExpiryDate` DATE)   BEGIN
UPDATE product 
SET 
`name` = productName, 
`fk_category_id`= categoryId,  
`price` = productPrice,
`quantity` = productQuantity,
`expiration_date` = productExpiryDate
WHERE id = productId;
END$$

CREATE PROCEDURE `updateProductPicture` (IN `productId` INT(11), `productPicture` VARCHAR(128))   BEGIN
UPDATE product 
SET 
`picture` = productPicture
WHERE id = productId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Dairy'),
(2, 'Drinks'),
(3, 'Fruits and Veg'),
(4, 'Meat'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `fk_category_id` int(11) NOT NULL,
  `picture` varchar(128) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `fk_category_id`, `picture`, `price`, `quantity`, `expiration_date`) VALUES
(24, 'reffr', 1, 'photo_default.png', 5.00, 55, '2023-11-10'),
(26, 'Product 1', 3, 'photo_default.png', 600.00, 5, '2024-07-11'),
(29, 'rfrf', 2, 'photo_default.png', 0.00, 5, '2024-11-27'),
(30, 'Product 11', 1, 'photo_default.png', 200.00, 7, '2024-11-28'),
(31, 'frfrefre', 2, 'photo_default.png', 200.00, 45, '2024-11-20'),
(32, 'Test item', 1, 'photo_default.png', 555.00, 6, '2024-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'mphulo.mafa@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_id` (`fk_category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
