/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100411
 Source Host           : localhost:3306
 Source Schema         : food-delivery

 Target Server Type    : MySQL
 Target Server Version : 100411
 File Encoding         : 65001

 Date: 26/06/2020 01:19:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for administrators
-- ----------------------------
DROP TABLE IF EXISTS `administrators`;
CREATE TABLE `administrators`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of administrators
-- ----------------------------
INSERT INTO `administrators` VALUES (1, 'Administrator', 'admin@gmail.com', 'bc68472361054e6e0d2d8d5b2bd15eba', '2020-06-07 16:51:40', '2020-06-07 16:51:40');

-- ----------------------------
-- Table structure for api_token
-- ----------------------------
DROP TABLE IF EXISTS `api_token`;
CREATE TABLE `api_token`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expired_at` datetime(0) NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of api_token
-- ----------------------------
INSERT INTO `api_token` VALUES (1, '5607fe8879e4fd269e88387e8cb30b7e', NULL, 3, '2020-04-07 03:57:32');
INSERT INTO `api_token` VALUES (2, '019f8b946a256d9357eadc5ace2c8678', '2020-04-07 23:07:06', 3, '2020-04-07 04:07:06');
INSERT INTO `api_token` VALUES (3, 'e9412ee564384b987d086df32d4ce6b7', '2020-06-21 10:39:48', 3, '2020-06-20 15:39:48');
INSERT INTO `api_token` VALUES (4, '24368c745de15b3d2d6279667debcba3', '2020-06-21 11:00:44', 3, '2020-06-20 16:00:44');
INSERT INTO `api_token` VALUES (5, 'b4aa00bc1c59b9d1cdd07479070e355e', '2020-06-21 11:00:47', 3, '2020-06-20 16:00:47');
INSERT INTO `api_token` VALUES (6, 'e3a72c791a69f87b05ea7742e04430ed', '2020-06-21 11:03:00', 3, '2020-06-20 16:03:00');
INSERT INTO `api_token` VALUES (7, '0a5c79b1eaf15445da252ada718857e9', '2020-06-21 15:56:17', 3, '2020-06-20 20:56:17');

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cart
-- ----------------------------
INSERT INTO `cart` VALUES (23, 8, 2, 3, '2020-06-20 21:01:00', NULL);

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NULL DEFAULT NULL,
  `product_stock_id` int(11) NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_details
-- ----------------------------
INSERT INTO `order_details` VALUES (1, 5, 1, 1, 10000.00, '2020-06-17 11:22:08', '2020-06-20 15:33:12');
INSERT INTO `order_details` VALUES (2, 6, 2, 1, 11000.00, '2020-06-20 20:12:00', NULL);
INSERT INTO `order_details` VALUES (3, 7, 2, 1, 11000.00, '2020-06-20 20:12:44', NULL);
INSERT INTO `order_details` VALUES (4, 8, 2, 1, 11000.00, '2020-06-20 20:13:30', NULL);
INSERT INTO `order_details` VALUES (5, 9, 2, 1, 11000.00, '2020-06-20 20:15:08', NULL);
INSERT INTO `order_details` VALUES (6, 10, 2, 1, 11000.00, '2020-06-20 20:18:59', NULL);
INSERT INTO `order_details` VALUES (7, 11, 2, 1, 11000.00, '2020-06-20 20:19:44', NULL);
INSERT INTO `order_details` VALUES (8, 12, 2, 1, 11000.00, '2020-06-20 20:20:34', NULL);
INSERT INTO `order_details` VALUES (9, 13, 2, 1, 11000.00, '2020-06-20 20:20:52', NULL);
INSERT INTO `order_details` VALUES (10, 14, 2, 1, 11000.00, '2020-06-20 20:24:12', NULL);
INSERT INTO `order_details` VALUES (11, 15, 2, 1, 11000.00, '2020-06-20 20:24:22', NULL);
INSERT INTO `order_details` VALUES (12, 16, 2, 1, 11000.00, '2020-06-20 20:25:16', NULL);
INSERT INTO `order_details` VALUES (13, 17, 2, 1, 11000.00, '2020-06-20 20:25:47', NULL);
INSERT INTO `order_details` VALUES (14, 18, 2, 1, 11000.00, '2020-06-20 20:36:43', NULL);
INSERT INTO `order_details` VALUES (15, 19, 2, 1, 11000.00, '2020-06-20 20:37:59', NULL);

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `lat` decimal(10, 2) NULL DEFAULT NULL,
  `lng` decimal(10, 2) NULL DEFAULT NULL,
  `shipping_cost` decimal(10, 2) NULL DEFAULT NULL,
  `status` enum('pending','paid','shipping','delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (16, '20200620152', 3, NULL, NULL, NULL, NULL, 'shipping', '2020-06-20 20:25:16', '2020-06-20 20:35:00');
INSERT INTO `orders` VALUES (17, 'FD/20200620', 3, NULL, NULL, NULL, NULL, 'pending', '2020-06-20 20:25:47', NULL);
INSERT INTO `orders` VALUES (18, 'FD/20200620', 3, NULL, NULL, NULL, NULL, 'pending', '2020-06-20 20:36:43', NULL);
INSERT INTO `orders` VALUES (19, 'FD/20200620', 3, 'Jl. Test No 12, Bla Bla Bla', 0.00, 0.00, 12000.00, 'pending', '2020-06-20 20:37:59', NULL);

-- ----------------------------
-- Table structure for product_stocks
-- ----------------------------
DROP TABLE IF EXISTS `product_stocks`;
CREATE TABLE `product_stocks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qty` int(11) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `product_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_stocks
-- ----------------------------
INSERT INTO `product_stocks` VALUES (1, 10, 10000.00, 3, '2020-06-17 01:51:50', '2020-06-20 16:12:07');
INSERT INTO `product_stocks` VALUES (2, 20, 9000.00, 3, '2020-06-17 01:51:48', '2020-06-20 20:54:27');
INSERT INTO `product_stocks` VALUES (3, 5, 5000.00, 8, '2020-06-20 20:55:01', NULL);
INSERT INTO `product_stocks` VALUES (4, 10, 5500.00, 8, '2020-06-20 20:59:47', NULL);

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (3, 'Product A1', 'Description of Product A1', 'uploads/f06b4914f646fa6004e68c3ce5192e21.png', 8, 1, '2020-06-16 14:49:27', '2020-06-17 00:43:57');
INSERT INTO `products` VALUES (5, 'Product B1', 'Description of Product B1', 'uploads/a863d24ac57d8ee5bfc452858b422b12.png', 9, 1, '2020-06-16 14:50:51', '2020-06-17 00:44:00');
INSERT INTO `products` VALUES (7, 'Product B2', 'Description of Product B2', 'uploads/198a0614d340f27c04902cc13fcb92ed.png', 9, 1, '2020-06-17 00:46:28', NULL);
INSERT INTO `products` VALUES (8, 'Makanan A', 'Description Makanan A', 'uploads/63a86d8f31e862862a1185ba3ed63ad9.jpg', 11, 1, '2020-06-20 20:52:03', NULL);

-- ----------------------------
-- Table structure for stores
-- ----------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `lat` decimal(10, 0) NULL DEFAULT NULL,
  `lng` decimal(10, 0) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stores
-- ----------------------------
INSERT INTO `stores` VALUES (8, 'Store A', 'uploads/66e328af68ae9dba2ef86fd097fd5239.png', 'Jl. Test Store A', 0, 0, 3, '2020-06-16 14:36:49', NULL);
INSERT INTO `stores` VALUES (9, 'Store B', 'uploads/8dc93409e9d6062a88b8259a8f144749.png', 'Jl. Test Store B', 0, 0, 3, '2020-06-16 14:37:04', NULL);
INSERT INTO `stores` VALUES (11, 'Toko A', 'uploads/194be1ef369eb0a5c62f6a053769486a.jpg', 'Jl. Test No 12', 1, 1, 5, '2020-06-20 20:49:16', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (3, 'Fakhrurrazi Andi', 'fakhrurrazi.andi@gmail.com', NULL, 'c85bfb5795c20157585933c8086b1aff', NULL, NULL, '2020-03-27 06:17:19', '2020-03-27 06:17:19');
INSERT INTO `users` VALUES (4, 'Test', 'test@gmail.com', NULL, '098f6bcd4621d373cade4e832627b4f6', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (5, 'test2', 'test2@gmail.com', NULL, 'ad0234829205b9033196ba818f7a872b', NULL, NULL, '2020-06-20 15:59:08', NULL);

SET FOREIGN_KEY_CHECKS = 1;
