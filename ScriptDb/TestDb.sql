DROP DATABASE IF EXISTS `Test`;

CREATE DATABASE `Test`;

USE `Test`;

CREATE TABLE `User`
(
  `id` int NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(25) NOT NULL,
  `password` VARCHAR(25) NOT NULL,
  CONSTRAINT PK_User PRIMARY KEY (`id`)
);

CREATE TABLE `Order`
(
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  CONSTRAINT PK_Order PRIMARY KEY (`id`),
  CONSTRAINT FK_Order_User FOREIGN KEY (`userId`)
    REFERENCES `User`(`id`)
);

CREATE TABLE `Goods`
(
  `id` int NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(100) NOT NULL,
  CONSTRAINT PK_Goods PRIMARY KEY (`id`)
);

CREATE TABLE `OrderGoods`
(
  `orderId` int NOT NULL,
  `goodsId` int NOT NULL,
  CONSTRAINT FK_OrderGoods_Order FOREIGN KEY (`orderId`)
    REFERENCES `Order`(`id`),
  CONSTRAINT FK_OrderGoods_Goods FOREIGN KEY (`goodsId`)
  REFERENCES `Goods`(`id`)
);



