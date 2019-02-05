DROP DATABASE IF EXISTS `AutoStore`;

CREATE DATABASE `AutoStore`;

USE `AutoStore`;

CREATE TABLE `Role`
(
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  CONSTRAINT PK_Role PRIMARY KEY (`id`)
);

CREATE TABLE `User`
(
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(25) NOT NULL,
  `hash` VARBINARY(100) NOT NULL,
  `online` BIT NOT NULL DEFAULT 0,
  `roleId` INT NOT NULL,
  CONSTRAINT PK_User PRIMARY KEY (`id`),
  CONSTRAINT FK_User_Role FOREIGN KEY (`roleId`)
    REFERENCES `Role`(`id`)
);

CREATE TABLE `Category`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` NVARCHAR(50) NOT NULL,
  CONSTRAINT FK_Category PRIMARY KEY(`id`)
);

CREATE TABLE `Product`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` NVARCHAR(100) NOT NULL,
  `description` NVARCHAR(500) NOT NULL,
  `categoryId` INT NOT NULL,
  `price` DECIMAL(16, 2) NOT NULL,
  `imageData` LONGBLOB NULL,
  CONSTRAINT FK_Product PRIMARY KEY(`id`),
  CONSTRAINT FK_Product_Category FOREIGN KEY(`categoryId`)
    REFERENCES `Category`(`id`)
);

CREATE TABLE `Status`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` NVARCHAR(25) NOT NULL,
  CONSTRAINT PK_Status PRIMARY KEY(`id`)
);

DROP TABLE IF EXISTS `Order`;
CREATE TABLE `Order`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `statusId` INT NOT NULL DEFAULT 1,
  `userId` INT NULL,
  `name` NVARCHAR(100) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `address` NVARCHAR(255) NOT NULL,
  `city` NVARCHAR(25) NOT NULL,
  `country` NVARCHAR(25) NOT NULL,
  `date` DATETIME NOT NULL DEFAULT NOW(),
  CONSTRAINT PK_Order PRIMARY KEY(`id`),
  CONSTRAINT FK_Order_Status FOREIGN KEY(`statusId`)
    REFERENCES `Status`(`id`),
  CONSTRAINT FK_Order_User FOREIGN KEY(`userId`)
  REFERENCES `User`(`id`)
);

CREATE TABLE `OrderProduct`(
  `orderId` INT NOT NULL,
  `productId` INT NOT NULL,
  `quantity` INT NOT NULL,
  CONSTRAINT FK_OrderProduct_Order FOREIGN KEY(`orderId`)
    REFERENCES `Order`(`id`),
  CONSTRAINT FK_OrderProduct_Product FOREIGN KEY(`productId`)
    REFERENCES `Product`(`id`)
);

# CREATE TABLE `LogOnlineUser`
# (
#   `userId` INT NOT NULL,
#   `dateIn` DATETIME NOT NULL DEFAULT NOW(),
#   `dateOut` DATETIME NULL,
#   CONSTRAINT FK_LogOnlineUser_User FOREIGN KEY(`userId`)
#     REFERENCES `User`(`id`)
# );

CREATE FUNCTION `FixOrder`(
  _userId INT,
  _name NVARCHAR(100),
  _phone VARCHAR(25),
  _address NVARCHAR(255),
  _city NVARCHAR(25),
  _country NVARCHAR(25))
  RETURNS INT
  BEGIN
    INSERT INTO `Order` (`userId`,`name`,`phone`,`address`,`city`,`country`)
      VALUES (_userId,_name,_phone,_address,_city,_country);
    RETURN last_insert_id();
  END;

DROP PROCEDURE IF EXISTS `AddProductToOrder`;
CREATE PROCEDURE `AddProductToOrder`(IN _orderId INT, IN _productId INT, IN _quantity INT)
  BEGIN
    INSERT INTO `OrderProduct`(`orderId`,`productId`,`quantity`)
      VALUES (_orderId,_productId,_quantity);
  END;

CREATE PROCEDURE `Login`(IN _hash VARBINARY(200))
  BEGIN
    DECLARE _result TINYINT DEFAULT 2;
    DECLARE _id INT DEFAULT NULL;
    DECLARE _login, _role VARCHAR(25) DEFAULT NULL;

    SELECT 0 # CASE `online` WHEN 1 THEN 1 ELSE 0 END
      ,IF(_result, NULL, u.`id`)
      ,IF(_result, NULL, u.`login`)
      ,IF(_result, NULL, r.`name`)
    INTO _result, _id, _login, _role
    FROM `User` u
      JOIN `Role` r ON u.roleId = r.id
    WHERE `hash`=_hash;

#     IF _result = 0 THEN
#       UPDATE `User` SET `online` = 1 WHERE `hash` = _hash;
#     END IF;

    SELECT _result AS `result`, _id AS `id`, _login AS `login`, _role AS `role`;
  END;

CREATE PROCEDURE `JLogin`(IN _hash VARBINARY(200))
  BEGIN
    DECLARE _result TINYINT DEFAULT 2;
    DECLARE _id INT DEFAULT NULL;
    DECLARE _login, _role VARCHAR(25) DEFAULT NULL;

    SELECT 0 # CASE `online` WHEN 1 THEN 1 ELSE 0 END
      ,IF(_result, NULL, u.`id`)
      ,IF(_result, NULL, u.`login`)
      ,IF(_result, NULL, r.`name`)
    INTO _result, _id, _login, _role
    FROM `User` u
      JOIN `Role` r ON u.roleId = r.id
    WHERE `hash`=_hash;

#     IF _result = 0 THEN
#       UPDATE `User` SET `online` = 1 WHERE `hash` = _hash;
#     END IF;

    SELECT _result AS `result`;

    SELECT _id AS `id`, _login AS `login`, _role AS `role`;
  END;

CREATE PROCEDURE `Logout`(IN _id INT)
  BEGIN
    UPDATE `User` SET `online` = 0 WHERE `id` = _id AND `online` = 1;
  END;

CREATE PROCEDURE `IsExistsLogin`(IN _login VARCHAR(25))
  BEGIN
    SELECT IF((EXISTS(SELECT '' FROM User WHERE `login` = _login LIMIT 1)), TRUE, FALSE) AS result;
  END;

CREATE PROCEDURE `Registration`(IN _login VARCHAR(25), IN _password VARCHAR(25), IN _roleId INT)
  BEGIN
    DECLARE _result TINYINT DEFAULT IF(EXISTS(SELECT '' FROM `User` WHERE `login` = _login LIMIT 1), 1, 0);
    IF _result = 0 THEN
      INSERT INTO `User`(`login`,`hash`,`roleId`) VALUES (_login,SHA1(CONCAT(_login,'|', _password)),_roleId);
    END IF;
    SELECT _result AS result;
  END;

CREATE PROCEDURE `JRegistration`(IN _login VARCHAR(25), IN _hash VARBINARY(200), IN _roleId INT)
  BEGIN
    DECLARE _result TINYINT DEFAULT IF(EXISTS(SELECT '' FROM `User` WHERE `login` = _login LIMIT 1), 1, 0);
    IF _result = 0 THEN
      INSERT INTO `User`(`login`,`hash`,`roleId`) VALUES (_login,_hash,_roleId);
    END IF;
    SELECT _result AS result;
  END;

CREATE PROCEDURE `GetProductImage`(IN _id INT)
  BEGIN
    SELECT FROM_BASE64(`imageData`) as imageData FROM `Product` WHERE `id`=_id;
  END;

DROP PROCEDURE IF EXISTS `GetProductsByCategory`;
CREATE PROCEDURE `GetProductsByCategory`(IN _category NVARCHAR(25))
READS SQL DATA
  BEGIN
    SELECT p.id,p.name,p.price,p.description,c.name category
    FROM `Product` p
      JOIN `Category` c ON p.categoryId = c.id
    WHERE p.categoryId IN (SELECT c.`id` FROM `Category` c WHERE c.`name`=_category OR _category='')
    ORDER BY `id`;
  END;

DROP PROCEDURE IF EXISTS `GetProductsByCategoryPager`;
CREATE PROCEDURE `GetProductsByCategoryPager`(IN _category NVARCHAR(25), IN _page INT, IN _count INT)
READS SQL DATA
  BEGIN
    DECLARE _offset INT DEFAULT (_page-1)*_count;
    SELECT p.id,p.name,p.price,p.description,c.name category
    FROM `Product` p
      JOIN (
             SELECT *
             FROM `Product`
             WHERE categoryId IN (SELECT c.id FROM Category c WHERE c.name=_category OR _category='')
             ORDER BY `id`
             LIMIT _offset, _count) plo
        ON p.id=plo.id
      JOIN `Category` c ON p.categoryId = c.id;
  END;

DROP PROCEDURE IF EXISTS `GetQuantityByCategory`;
CREATE PROCEDURE `GetQuantityByCategory`(IN _category NVARCHAR(25))
READS SQL DATA
  BEGIN
    SELECT `GetCountByCategory`(_category) AS 'quantity';
  END;

DROP FUNCTION IF EXISTS `GetCountByCategory`;
CREATE FUNCTION `GetCountByCategory`(_category NVARCHAR(25))
  RETURNS INT
  BEGIN
    DECLARE `_conutByCategory` INT;
    SELECT COUNT(*) INTO `_conutByCategory`
    FROM `Product`
    WHERE categoryId IN (SELECT c.id FROM Category c WHERE c.name=_category OR _category='');
    RETURN `_conutByCategory`;
  END;

DROP PROCEDURE IF EXISTS `GetProduct`;
CREATE PROCEDURE `GetProduct`(IN _id INT)
  BEGIN
    SELECT p.`id`,p.`name`,p.`price`,p.`description`,c.name category
    FROM `Product` p
      JOIN `Category` c ON p.categoryId = c.id
    WHERE p.`id`=_id
    LIMIT 1;
  END;

DROP PROCEDURE IF EXISTS `SaveProduct`;
CREATE PROCEDURE `SaveProduct`(
  IN _id INT,
  IN _name NVARCHAR(100),
  IN _description NVARCHAR(500),
  IN _category VARCHAR(25),
  IN _price DOUBLE(16,2),
  IN _image LONGBLOB)
  BEGIN
    DECLARE _categoryId VARCHAR(25);
    SELECT `id` INTO _categoryId FROM `Category` WHERE `name`=_category;
    IF IsExistsProduct(_id)
    THEN
      UPDATE `Product`
      SET `name`=_name,
        `description`=_description,
        `categoryId`=_categoryId,
        `price`=_price,
        `imageData`=IF(_image<>'', TO_BASE64(_image), `imageData`)
      WHERE `id`=_id;
    ELSE
      INSERT INTO `Product`(`name`,`description`,`categoryId`,`price`,`imageData`)
      VALUES (_name,_description,_categoryId,_price,TO_BASE64(_image));
    END IF;
  END;

DROP PROCEDURE IF EXISTS `DeleteProduct`;
CREATE PROCEDURE `DeleteProduct`(IN _id INT)
  BEGIN
    DELETE FROM `Product`
      WHERE `id`=_id;
  END;

DROP FUNCTION IF EXISTS `IsExistsProduct`;
CREATE FUNCTION `IsExistsProduct`(_id INT)
  RETURNS BOOLEAN
  BEGIN
    RETURN EXISTS(SELECT '' FROM `Product` WHERE `id`=_id);
  END;

DROP PROCEDURE IF EXISTS `OrderList`;
CREATE PROCEDURE `OrderList`(IN _status VARCHAR(25))
  BEGIN
    SELECT o.id,s.name status,o.name userName,o.date,TotalPriceByOrder(o.id) totalPrice
    FROM `Order` o
      JOIN `Status` s ON o.statusId = s.id
    WHERE `statusId` IN (SELECT `id` FROM `Status` WHERE `name`=_status)
    ORDER BY `id`;
  END;

DROP PROCEDURE IF EXISTS `GetProductsByOrder`;
CREATE PROCEDURE `GetProductsByOrder`(IN _id INT)
  BEGIN
    SELECT p.id,p.name,p.description,c.name category,p.price,op.quantity
    FROM `OrderProduct` op
      JOIN `Product` p ON op.productId = p.id
      JOIN `Category` c ON p.categoryId = c.id
    WHERE op.orderId=_id;
  END;

DROP PROCEDURE IF EXISTS `GetOrder`;
CREATE PROCEDURE `GetOrder`(IN _id INT)
  BEGIN
    SELECT o.id,s.name status,o.userId,o.name userName,o.date,o.phone,o.address,o.city,o.country
    FROM `Order` o
      JOIN `Status` s ON o.statusId = s.id
    WHERE o.id=_id
    LIMIT 1;

    CALL GetProductsByOrder(_id);
  END;

DROP PROCEDURE IF EXISTS `CloseOrder`;
CREATE PROCEDURE `CloseOrder`(IN _id INT)
  BEGIN
    DECLARE _statusId INT;
    SELECT `id` INTO _statusId FROM `Status` WHERE `name`='close';
    UPDATE `Order`
      SET `statusId`=_statusId
    WHERE `id`=_id;
  END;

DROP FUNCTION IF EXISTS TotalPriceByOrder;
CREATE FUNCTION TotalPriceByOrder(_id INT)
  RETURNS DOUBLE
  BEGIN
    DECLARE _result DOUBLE(16,2);
    SELECT SUM(p.`price`*op.`quantity`) INTO _result
      FROM `OrderProduct` op
        JOIN `Product` p ON op.`productId` = p.`id`
      WHERE op.`orderId`=_id;
    RETURN _result;
  END;

# CREATE TRIGGER `OnlineUserLog`
# AFTER UPDATE
#   ON `User`
#   FOR EACH ROW
#   BEGIN
#     IF NOT OLD.online <=> NEW.online THEN
#       IF NEW.online = 1 THEN
#         INSERT INTO `LogOnlineUser` SET `userId` = NEW.id;
#       ELSE
#         UPDATE `LogOnlineUser`
#           SET `dateOut` = NOW()
#           WHERE `userId` = NEW.id AND `dateOut` IS NULL;
#       END IF;
#     END IF;
#   END;

INSERT INTO `Role`(`name`)
    VALUES ('sa'),('admin'),('user');

INSERT INTO `Category`(`name`)
    VALUES('Car'),('Minibus'),('Jeep');

INSERT INTO `Status`(`name`)
    VALUES('new'),('close');

INSERT INTO `User`(`login`,`hash`,`roleId`)
    VALUES ('sa',SHA1('sa|123'),1),('admin',SHA1('admin|123'),2),('user',SHA1('user|123'),3);

INSERT INTO `Product` (`name`,`description`,`categoryId`,`price`,`imageData`)
    VALUES ('bmw','car bmw',1,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/bmw.png')))
      ,('fiat','minibus fiat',2,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/fiat.png')))
      ,('ford','minibus ford',2,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/ford.png')))
      ,('hyundai','car hyundai',3,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/hyundai.png')))
      ,('lada','car lada',3,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/lada.png')))
      ,('volvo','car volvo',1,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/volvo.png')))
      ,('honda','car honda',1,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/honda.jpg')))
      ,('audi','car audi',3,3000,to_base64(LOAD_FILE('c:/USR/www/php.autoshop/images/cars/audi.jpg')));


SELECT * FROM `Order`;

SELECT * FROM OrderProduct;

SELECT * FROM User;

SELECT * FROM Product;