-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.5.34 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table hire_quote.wp_atq_products
CREATE TABLE IF NOT EXISTS `wp_atq_products` (
  `prod_id` int(5) NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(100) NOT NULL,
  `prod_desc` longtext,
  `prod_images` longtext,
  `prod_code` varchar(100) NOT NULL,
  `prod_size` varchar(100) DEFAULT NULL,
  `prod_seller` tinyint(4) DEFAULT '0',
  `prod_sale` tinyint(4) DEFAULT '0',
  `prod_new` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table hire_quote.wp_atq_products: ~2 rows (approximately)
/*!40000 ALTER TABLE `wp_atq_products` DISABLE KEYS */;
INSERT INTO `wp_atq_products` (`prod_id`, `prod_name`, `prod_desc`, `prod_images`, `prod_code`, `prod_size`, `prod_seller`, `prod_sale`, `prod_new`) VALUES
	(2, 'LDS 3/4 SLEEVE SHIRT WITH ROLLUP SLEEVE', '<ul style="list-style: square; margin-left: 20px;">\r\n	<li>CONTRAST BUTTONSTAND UNDER CUFF</li>\r\n	<li>BUTTON CLOSURE DETAIL</li>\r\n	<li>ROLL UP TAB IN CONTRAST FABRIC</li>\r\n	<li>LEFT BREAST POCKET WITH BUTTON POCKET FLAP TAB</li>\r\n	<li>BACK YOLK WITH PLEAT</li>\r\n	<li>INNER COLLER IN CONTRAST FABRIC</li>\r\n</ul>', 'a:1:{i:0;s:64:"http://localhost/hire-quote/wp-content/uploads/2015/12/AT820.jpg";}', 'AT820', 'XL: 3', 0, 0, 0),
	(3, 'LADIES PF, RAGLAN SLV WITH PKTS', '<ul style="list-style: square; margin-left: 20px;">\r\n	<li>STRAIGHT CUT WITH A FULL FRONT ZIP</li>\r\n	<li>COMFORTABLE RAGLAN SLEEVES WITH HEM CUFFS</li>\r\n	<li>FRONT SIDE PANELS FOR THE SHAPED LOOK</li>\r\n	<li>POCKETS</li>\r\n</ul>', 'a:1:{i:0;s:69:"http://localhost/hire-quote/wp-content/uploads/2015/12/AT1008BASE.jpg";}', 'AT1008BASE', '', 0, 0, 0),
	(4, 'LADIES DETAILED BUSH SHIRT LS', '<ul style="list-style: square; margin-left: 20px;">\r\n	<li>FEMININE STYLE FIT</li>\r\n	<li>2 FRONT POCKETS WITH INNER PLEATS</li>\r\n	<li>FRONT AND BACK PANELS TO EMPHASIZE SHAPE</li>\r\n	<li>BACK YOKE DETAIL</li>\r\n</ul>', 'a:1:{i:0;s:64:"http://localhost/hire-quote/wp-content/uploads/2015/12/AT787.jpg";}', 'AT787', '', 0, 0, 0),
	(5, 'D/BREASTED POLYCOTTON CHEFS JKT LS', '<ul style="list-style: square; margin-left: 20px;">\r\n	<li>DOUBLE BREASTED FOR MAXIMUM FRONT PROTECTION</li>\r\n	<li>CAN BE WORN LEFT OR RIGHT</li>\r\n	<li>LONG SLEEVES WITH CUFFS</li>\r\n	<li>LEFT SLEEVE PEN AND THERMOMETER POCKET</li>\r\n	<li>BLACK OR WHITE POPPER</li>\r\n</ul>', 'a:1:{i:0;s:64:"http://localhost/hire-quote/wp-content/uploads/2015/12/AXTO1.jpg";}', 'AXTO1', '', 0, 0, 0);
/*!40000 ALTER TABLE `wp_atq_products` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
