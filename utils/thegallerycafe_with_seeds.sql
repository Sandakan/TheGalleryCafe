/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 80300 (8.3.0)
 Source Host           : localhost:3306
 Source Schema         : thegallerycafe

 Target Server Type    : MySQL
 Target Server Version : 80300 (8.3.0)
 File Encoding         : 65001

 Date: 10/08/2024 01:17:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cart
-- ----------------------------
INSERT INTO `cart` VALUES (1, 1, '2024-08-08 14:34:55', '2024-08-08 15:31:37', '2024-08-08 15:31:37');
INSERT INTO `cart` VALUES (2, 1, '2024-08-08 15:34:50', '2024-08-08 15:39:11', '2024-08-08 15:31:37');
INSERT INTO `cart` VALUES (3, 1, '2024-08-08 15:41:10', '2024-08-08 15:41:37', '2024-08-08 15:41:37');
INSERT INTO `cart` VALUES (4, 1, '2024-08-08 15:44:10', '2024-08-08 15:44:28', '2024-08-08 15:44:28');
INSERT INTO `cart` VALUES (5, 1, '2024-08-08 15:46:01', '2024-08-08 15:46:09', '2024-08-08 15:46:09');
INSERT INTO `cart` VALUES (6, 1, '2024-08-08 15:51:21', '2024-08-08 15:51:27', '2024-08-08 15:51:27');
INSERT INTO `cart` VALUES (7, 1, '2024-08-08 16:17:46', '2024-08-08 16:18:31', '2024-08-08 16:18:31');
INSERT INTO `cart` VALUES (8, 1, '2024-08-09 15:13:15', '2024-08-09 15:13:15', NULL);

-- ----------------------------
-- Table structure for cart_item
-- ----------------------------
DROP TABLE IF EXISTS `cart_item`;
CREATE TABLE `cart_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `menu_item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `cart_id`(`cart_id` ASC) USING BTREE,
  CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cart_item
-- ----------------------------
INSERT INTO `cart_item` VALUES (1, 1, 1, 6, '2024-08-08 14:34:55', '2024-08-08 14:42:02', NULL);
INSERT INTO `cart_item` VALUES (2, 1, 3, 4, '2024-08-08 14:47:52', '2024-08-08 14:57:42', NULL);
INSERT INTO `cart_item` VALUES (3, 1, 14, 3, '2024-08-08 14:48:34', '2024-08-08 15:19:54', '2024-08-08 15:16:40');
INSERT INTO `cart_item` VALUES (4, 1, 14, 1, '2024-08-08 15:23:10', '2024-08-08 15:23:41', '2024-08-08 15:23:41');
INSERT INTO `cart_item` VALUES (5, 1, 14, 1, '2024-08-08 15:25:58', '2024-08-08 15:26:16', '2024-08-08 15:26:16');
INSERT INTO `cart_item` VALUES (6, 2, 3, 1, '2024-08-08 15:34:50', '2024-08-08 15:34:50', NULL);
INSERT INTO `cart_item` VALUES (7, 3, 14, 1, '2024-08-08 15:41:10', '2024-08-08 15:41:10', NULL);
INSERT INTO `cart_item` VALUES (8, 4, 10, 1, '2024-08-08 15:44:10', '2024-08-08 15:44:10', NULL);
INSERT INTO `cart_item` VALUES (9, 5, 12, 1, '2024-08-08 15:46:01', '2024-08-08 15:46:01', NULL);
INSERT INTO `cart_item` VALUES (10, 6, 11, 1, '2024-08-08 15:51:21', '2024-08-08 15:51:21', NULL);
INSERT INTO `cart_item` VALUES (11, 7, 10, 1, '2024-08-08 16:17:46', '2024-08-08 16:17:46', NULL);
INSERT INTO `cart_item` VALUES (12, 7, 31, 1, '2024-08-08 16:17:53', '2024-08-08 16:17:53', NULL);
INSERT INTO `cart_item` VALUES (13, 8, 14, 1, '2024-08-09 15:13:15', '2024-08-09 15:13:15', NULL);
INSERT INTO `cart_item` VALUES (14, 8, 3, 1, '2024-08-09 15:13:27', '2024-08-09 15:13:27', NULL);
INSERT INTO `cart_item` VALUES (15, 8, 31, 1, '2024-08-09 15:13:45', '2024-08-09 15:13:45', NULL);

-- ----------------------------
-- Table structure for cuisine
-- ----------------------------
DROP TABLE IF EXISTS `cuisine`;
CREATE TABLE `cuisine`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cuisine
-- ----------------------------
INSERT INTO `cuisine` VALUES (1, 'Continental', '2024-08-03 10:45:56', '2024-08-03 10:45:56', NULL);
INSERT INTO `cuisine` VALUES (2, 'Italian', '2024-08-03 10:46:12', '2024-08-03 10:46:12', NULL);
INSERT INTO `cuisine` VALUES (3, 'Sri Lankan', '2024-08-03 10:46:25', '2024-08-03 10:46:25', NULL);
INSERT INTO `cuisine` VALUES (4, 'Beverages', '2024-08-03 10:46:32', '2024-08-03 10:46:32', NULL);
INSERT INTO `cuisine` VALUES (5, 'Desserts', '2024-08-03 10:46:44', '2024-08-03 10:46:44', NULL);
INSERT INTO `cuisine` VALUES (6, 'Fusion', '2024-08-06 20:03:58', '2024-08-06 20:03:58', NULL);

-- ----------------------------
-- Table structure for event
-- ----------------------------
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of event
-- ----------------------------
INSERT INTO `event` VALUES (1, 'Valentine’s Day Special', 'Celebrate Valentine’s Day with a special menu and romantic ambiance.', '2024-02-14 18:00:00', '2024-02-14 23:00:00', '2024-08-06 15:43:25', '2024-08-06 15:43:25', NULL);
INSERT INTO `event` VALUES (2, 'Mother’s Day Brunch', 'Join us for a special brunch to celebrate Mother’s Day.', '2024-05-12 10:00:00', '2024-05-12 14:00:00', '2024-08-06 15:43:25', '2024-08-06 15:43:25', NULL);
INSERT INTO `event` VALUES (3, 'Christmas Event', 'Lorem ipsum', '2024-12-25 18:00:00', '2024-12-25 20:00:00', '2024-08-06 16:25:40', '2024-08-07 20:31:07', NULL);
INSERT INTO `event` VALUES (4, 'Unkonwn Event', 'Event', '2024-08-08 23:55:00', '2024-08-09 23:55:00', '2024-08-08 23:55:15', '2024-08-09 00:00:06', NULL);

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 'Breakfast Menu', 'Start your day right with our Breakfast Menu, featuring a range of delicious options from hearty classics to light and fresh choices. Whether you prefer a rich, creamy dish like Classic Eggs Benedict or a nutritious Avocado Toast, our breakfast selections are designed to energize your morning.', '2024-08-03 10:42:48', '2024-08-03 10:42:48', NULL);
INSERT INTO `menu` VALUES (2, 'Lunch Menu', 'Our Lunch Menu offers a delightful selection of dishes to power you through the afternoon. Enjoy the flavors of Italy with dishes like Margherita Pizza and Spaghetti Carbonara, or opt for a lighter option like Caesar Salad. Perfect for a satisfying mid-day meal or a casual catch-up with friends.', '2024-08-03 10:42:48', '2024-08-03 10:42:48', NULL);
INSERT INTO `menu` VALUES (3, 'Dinner Menu', 'Experience a taste of Sri Lanka with our Dinner Menu, showcasing rich and flavorful dishes that bring a touch of spice to your evening. From the aromatic Chicken Kottu Roti to the comforting Sri Lankan Fish Curry, our dinner offerings are crafted to provide a memorable culinary experience.', '2024-08-03 10:42:48', '2024-08-03 10:42:48', NULL);
INSERT INTO `menu` VALUES (4, 'Special Beverages Menu', 'Quench your thirst and savor unique flavors with our Special Beverages Menu. Enjoy refreshing Mango Lassi, a chilled Iced Latte, or a soothing Herbal Tea. Each beverage is carefully crafted to complement your meal or provide a relaxing break throughout the day.', '2024-08-03 10:42:48', '2024-08-03 10:42:48', NULL);
INSERT INTO `menu` VALUES (5, 'Dessert Menu', 'End your meal on a sweet note with our Dessert Menu, featuring indulgent treats to satisfy your cravings. From the classic Tiramisu to a rich Chocolate Lava Cake, our desserts offer a perfect conclusion to any dining experience, crafted to delight and please.', '2024-08-03 10:42:48', '2024-08-03 10:42:48', NULL);
INSERT INTO `menu` VALUES (6, 'Fusion Cuisine Menu', 'Experience the delightful combination of various culinary traditions in our Fusion Cuisine Menu. We blend flavors and techniques from different cultures to create unique and exciting dishes that will tantalize your taste buds.', '2024-08-05 15:07:16', '2024-08-05 15:13:42', NULL);
INSERT INTO `menu` VALUES (7, 'Gateau Menu', 'A full menu that is meant to be all about cakes and gateaux.', '2024-08-08 21:12:28', '2024-08-08 21:19:35', '2024-08-08 21:19:35');

-- ----------------------------
-- Table structure for menu_item
-- ----------------------------
DROP TABLE IF EXISTS `menu_item`;
CREATE TABLE `menu_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `category` enum('APPETIZER','DESSERT','MAIN_COURSE','SIDE_DISH') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cuisine_id` int NOT NULL,
  `type` enum('MEAL','BEVERAGE','SPECIAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `menu_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `menu_id`(`menu_id` ASC) USING BTREE,
  INDEX `cuisine_id`(`cuisine_id` ASC) USING BTREE,
  CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `menu_item_ibfk_2` FOREIGN KEY (`cuisine_id`) REFERENCES `cuisine` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_item
-- ----------------------------
INSERT INTO `menu_item` VALUES (1, 'Classic Eggs Benedict', 'Poached eggs, ham, and hollandaise sauce on an English muffin.', 3000.00, 'MAIN_COURSE', 1, 'MEAL', '1.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (2, 'Avocado Toast', 'Smashed avocado on whole grain toast with a sprinkle of feta cheese and cherry tomatoes.', 2500.00, 'MAIN_COURSE', 1, 'MEAL', '2.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (3, 'French Toast', 'Thick slices of bread dipped in egg batter and grilled to perfection, served with maple syrup and fresh berries.', 2700.00, 'MAIN_COURSE', 1, 'MEAL', '3.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (4, 'Margherita Pizza', 'Classic pizza with fresh mozzarella, tomatoes, and basil.', 3500.00, 'MAIN_COURSE', 2, 'MEAL', '4.webp', 2, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (5, 'Spaghetti Carbonara', 'Spaghetti pasta tossed with creamy egg sauce, pancetta, and parmesan cheese.', 4000.00, 'MAIN_COURSE', 2, 'MEAL', '5.webp', 2, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (6, 'Caesar Salad', 'Crisp romaine lettuce, croutons, and parmesan cheese, tossed in Caesar dressing.', 3000.00, 'APPETIZER', 2, 'MEAL', '6.webp', 2, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (7, 'Chicken Kottu Roti', 'Stir-fried flatbread with chicken, vegetables, and aromatic spices.', 1500.00, 'MAIN_COURSE', 3, 'MEAL', '7.webp', 3, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (8, 'Sri Lankan Fish Curry', 'Fresh fish cooked in a rich, spicy coconut curry sauce, served with steamed rice.', 850.00, 'MAIN_COURSE', 3, 'MEAL', '8.webp', 3, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (9, 'Eggplant Moju', 'Tangy and spicy eggplant relish, a perfect side dish for rice and curry.', 700.00, 'SIDE_DISH', 3, 'MEAL', '9.webp', 3, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (10, 'Chocolate Lava Cake', 'Warm chocolate cake with a molten center, served with vanilla ice cream.', 750.00, 'DESSERT', 5, 'MEAL', '10.webp', 5, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (11, 'Sri Lankan Watalappan', 'Traditional coconut custard pudding flavored with cardamom and jaggery.', 1200.00, 'DESSERT', 5, 'MEAL', '11.webp', 5, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (12, 'Tiramisu', 'Classic Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cheese.', 1500.00, 'DESSERT', 5, 'MEAL', '12.webp', 5, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (13, 'Mango Lassi', 'Refreshing yogurt-based drink blended with ripe mangoes and a hint of cardamom.', 1000.00, 'SIDE_DISH', 1, 'BEVERAGE', '13.webp', 4, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (14, 'Iced Latte', 'Cold espresso mixed with chilled milk and a touch of sweetness.', 1200.00, 'SIDE_DISH', 1, 'BEVERAGE', '14.webp', 4, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (15, 'Herbal Tea', 'A soothing blend of herbs and spices, perfect for relaxation.', 750.00, 'SIDE_DISH', 1, 'BEVERAGE', '15.webp', 4, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (16, 'Truffle Fries', 'Crispy fries tossed in truffle oil and parmesan cheese.', 1100.00, 'APPETIZER', 1, 'SPECIAL', '16.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (17, 'Lobster Mac and Cheese', 'Creamy mac and cheese topped with succulent lobster chunks.', 2500.00, 'MAIN_COURSE', 1, 'SPECIAL', '17.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (18, 'Gourmet Mushroom Risotto', 'Rich and creamy risotto with a blend of gourmet mushrooms.', 2300.00, 'MAIN_COURSE', 1, 'SPECIAL', '18.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (19, 'Chocolate Fondue', 'A pot of melted chocolate served with an assortment of fruits and pastries for dipping.', 1750.00, 'DESSERT', 5, 'SPECIAL', '19.webp', 1, '2024-08-03 10:47:19', '2024-08-03 10:47:19', NULL);
INSERT INTO `menu_item` VALUES (20, 'Shrimp Cocktail', 'Chilled shrimp served with a tangy cocktail sauce.', 1200.00, 'MAIN_COURSE', 1, 'MEAL', '20.webp', 1, '2024-08-03 10:47:19', '2024-08-05 22:53:07', NULL);
INSERT INTO `menu_item` VALUES (30, 'Korean BBQ Tacos', 'Soft tortillas filled with marinated Korean BBQ beef, topped with fresh kimchi, and drizzled with spicy mayo.', 850.00, 'MAIN_COURSE', 6, 'MEAL', '30.jpg', 6, '2024-08-06 20:22:52', '2024-08-06 20:22:52', NULL);
INSERT INTO `menu_item` VALUES (31, 'Tandoori Chicken Pizza', 'A thin crust pizza topped with tandoori chicken, mozzarella cheese, red onions, and cilantro.', 1500.00, 'MAIN_COURSE', 1, 'SPECIAL', '31.jpg', 6, '2024-08-06 20:26:24', '2024-08-06 20:39:28', NULL);
INSERT INTO `menu_item` VALUES (32, 'Iced Caramel and Honey Latte', 'A latte with a spoonful of caramel and honey on top.', 850.00, 'DESSERT', 1, 'SPECIAL', '32.png', 6, '2024-08-08 23:06:49', '2024-08-08 23:12:58', NULL);

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_amount` decimal(10, 2) NOT NULL,
  `status` enum('PENDING','COMPLETED','CANCELLED') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reservation_id` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `reservation_id`(`reservation_id` ASC) USING BTREE,
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES (1, 1, 28800.00, 'PENDING', NULL, '2024-08-08 15:31:37', '2024-08-08 15:31:37', NULL);
INSERT INTO `order` VALUES (2, 1, 2700.00, 'PENDING', NULL, '2024-08-08 15:35:17', '2024-08-08 23:49:23', '2024-08-08 23:49:23');
INSERT INTO `order` VALUES (3, 1, 1200.00, 'PENDING', NULL, '2024-08-08 15:41:14', '2024-08-08 15:41:14', NULL);
INSERT INTO `order` VALUES (4, 1, 750.00, 'PENDING', NULL, '2024-08-08 15:44:22', '2024-08-08 15:44:22', NULL);
INSERT INTO `order` VALUES (5, 1, 1500.00, 'PENDING', NULL, '2024-08-08 15:46:05', '2024-08-08 15:46:05', NULL);
INSERT INTO `order` VALUES (6, 1, 1200.00, 'CANCELLED', NULL, '2024-08-08 15:51:25', '2024-08-08 23:39:51', NULL);
INSERT INTO `order` VALUES (7, 1, 2250.00, 'COMPLETED', 1, '2024-08-08 16:18:31', '2024-08-08 23:38:32', NULL);

-- ----------------------------
-- Table structure for order_item
-- ----------------------------
DROP TABLE IF EXISTS `order_item`;
CREATE TABLE `order_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `menu_item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `order_id`(`order_id` ASC) USING BTREE,
  INDEX `menu_item_id`(`menu_item_id` ASC) USING BTREE,
  CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_item
-- ----------------------------
INSERT INTO `order_item` VALUES (1, 1, 1, 6, '2024-08-08 15:31:37', '2024-08-08 15:31:37', NULL);
INSERT INTO `order_item` VALUES (2, 1, 3, 4, '2024-08-08 15:31:37', '2024-08-08 15:31:37', NULL);
INSERT INTO `order_item` VALUES (3, 2, 3, 1, '2024-08-08 15:35:17', '2024-08-08 15:35:17', NULL);
INSERT INTO `order_item` VALUES (4, 3, 14, 1, '2024-08-08 15:41:14', '2024-08-08 15:41:14', NULL);
INSERT INTO `order_item` VALUES (5, 4, 10, 1, '2024-08-08 15:44:22', '2024-08-08 15:44:22', NULL);
INSERT INTO `order_item` VALUES (6, 5, 12, 1, '2024-08-08 15:46:05', '2024-08-08 15:46:05', NULL);
INSERT INTO `order_item` VALUES (7, 6, 11, 1, '2024-08-08 15:51:25', '2024-08-08 15:51:25', NULL);
INSERT INTO `order_item` VALUES (8, 7, 10, 1, '2024-08-08 16:18:31', '2024-08-08 16:18:31', NULL);
INSERT INTO `order_item` VALUES (9, 7, 31, 1, '2024-08-08 16:18:31', '2024-08-08 16:18:31', NULL);
INSERT INTO `order_item` VALUES (10, 2, 14, 1, '2024-08-08 23:45:13', '2024-08-08 23:45:13', NULL);
INSERT INTO `order_item` VALUES (11, 2, 11, 1, '2024-08-08 23:45:48', '2024-08-08 23:45:48', NULL);
INSERT INTO `order_item` VALUES (12, 2, 1, 1, '2024-08-08 23:46:08', '2024-08-08 23:46:08', NULL);

-- ----------------------------
-- Table structure for promotion
-- ----------------------------
DROP TABLE IF EXISTS `promotion`;
CREATE TABLE `promotion`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `discount_percentage` decimal(3, 2) NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of promotion
-- ----------------------------
INSERT INTO `promotion` VALUES (1, 'Happy Hour', 'Enjoy 50% off on selected beverages from 4 PM to 6 PM.', 0.50, '2024-01-01 16:00:00', '2024-12-31 18:00:00', '2024-08-06 15:44:24', '2024-08-06 15:44:24', NULL);
INSERT INTO `promotion` VALUES (2, 'Weekend Days Special', 'Get a 20% discount on all main courses every weekend.', 0.20, '2024-01-06 12:00:00', '2024-12-29 22:00:00', '2024-08-06 15:44:24', '2024-08-09 00:14:41', NULL);
INSERT INTO `promotion` VALUES (3, 'New Year’s Eve Discount', 'Celebrate New Year’s Eve with a 30% discount on all items.', 0.25, '2024-12-31 18:00:00', '2024-12-31 23:59:59', '2024-08-06 15:44:24', '2024-08-06 17:00:35', NULL);
INSERT INTO `promotion` VALUES (4, 'Promotion 1%', 'New promotion', 0.01, '2024-08-09 00:06:00', '2024-08-31 00:06:00', '2024-08-09 00:06:24', '2024-08-09 00:07:17', '2024-08-09 00:07:17');
INSERT INTO `promotion` VALUES (5, 'Promotion 1%', 'My Promotion', 0.01, '2024-08-31 00:07:00', '2024-09-22 00:07:00', '2024-08-09 00:07:39', '2024-08-09 00:08:17', '2024-08-09 00:08:17');
INSERT INTO `promotion` VALUES (6, 'Promotion 1%', 'vfdad', 0.01, '2024-08-09 00:08:00', '2024-08-24 00:08:00', '2024-08-09 00:08:12', '2024-08-09 00:08:15', '2024-08-09 00:08:15');
INSERT INTO `promotion` VALUES (7, 'My Promotion', 'My promotion', 0.01, '2024-08-09 00:09:00', '2024-08-31 00:09:00', '2024-08-09 00:10:30', '2024-08-09 00:10:30', NULL);

-- ----------------------------
-- Table structure for reservation
-- ----------------------------
DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_reservation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `no_of_people` int NOT NULL,
  `occasion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `special_request` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `table_reservation_id`(`table_reservation_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`table_reservation_id`) REFERENCES `table_reservation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reservation
-- ----------------------------
INSERT INTO `reservation` VALUES (1, 1, 1, 2, 'Birthday', 'Play a song by Taylor Swift', '2024-08-08 16:18:31', '2024-08-08 23:30:21', NULL);
INSERT INTO `reservation` VALUES (2, 2, 1, 4, '', '', '2024-08-08 16:27:22', '2024-08-08 23:32:51', '2024-08-08 23:32:51');

-- ----------------------------
-- Table structure for table
-- ----------------------------
DROP TABLE IF EXISTS `table`;
CREATE TABLE `table`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `capacity` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of table
-- ----------------------------
INSERT INTO `table` VALUES (1, 4, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (2, 2, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (3, 6, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (4, 8, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (5, 4, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (6, 2, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (7, 6, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (8, 8, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (9, 4, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);
INSERT INTO `table` VALUES (10, 2, '2024-08-03 13:18:06', '2024-08-03 13:18:06', NULL);

-- ----------------------------
-- Table structure for table_reservation
-- ----------------------------
DROP TABLE IF EXISTS `table_reservation`;
CREATE TABLE `table_reservation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_id` int NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  INDEX `table_id`(`table_id` ASC) USING BTREE,
  CONSTRAINT `table_reservation_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `table` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of table_reservation
-- ----------------------------
INSERT INTO `table_reservation` VALUES (1, 2, '2024-08-10 16:00:00', '2024-08-10 17:00:00', '2024-08-08 16:18:31', '2024-08-08 23:29:49', '2024-08-08 23:29:49');
INSERT INTO `table_reservation` VALUES (2, 1, '2024-08-11 14:00:00', '2024-08-11 15:00:00', '2024-08-08 16:27:22', '2024-08-08 23:32:51', '2024-08-08 23:32:51');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_role` enum('CUSTOMER','ADMIN','STAFF') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'CUSTOMER',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'Sandakan', 'Nipunajith', 'admin@gmail.com', '$2y$10$wG.YCDRLyKQETxmLFIa6OekeS7l3TsfarLinzC0etDLx06kV1R9xq', '+94714275949', 'ADMIN', '2024-08-03 10:48:46', '2024-08-09 16:45:16', NULL);
INSERT INTO `user` VALUES (2, 'Hayes', 'Puckett', 'kewibipah@mailinator.com', '$2y$10$ZY2aA0/IoJ3LJm6b3PbuTOnCwVss01Ow01AR1u.ytq9cxW6wpLTYq', '0777585896', 'CUSTOMER', '2024-08-05 13:16:03', '2024-08-05 13:16:03', '2024-08-05 13:40:36');
INSERT INTO `user` VALUES (3, 'Lawrence', 'Mann', 'zuwim@mailinator.com', '$2y$10$fSMdlzGQyM2ZyBwqMuLhSem2KCf3RmsDNsZzUpyBVcBpI0aIReoEG', '1368622744', 'CUSTOMER', '2024-08-05 13:46:21', '2024-08-08 17:56:15', NULL);
INSERT INTO `user` VALUES (4, 'Sasara', 'Kavishan', 'staff@gmail.com', '$2y$10$Q3ta/0SV6SHGn8w5Wo/FFeBa4hL.SnZx0TyPQeu9sFKFmRB3jDzRy', '07712534534', 'STAFF', '2024-08-06 14:38:22', '2024-08-09 16:43:08', NULL);
INSERT INTO `user` VALUES (5, 'Cecilia', 'Blackburn', 'mabuxisuj@mailinator.com', '$2y$10$MFaSExpjs3gGLmXgbXCA/uUOUweYy3vKBX6ghwp3go7sSCpKfPnG6', '1121734329', 'CUSTOMER', '2024-08-08 17:45:51', '2024-08-08 18:10:54', '2024-08-08 18:10:54');

SET FOREIGN_KEY_CHECKS = 1;
