-- MariaDB dump 10.19  Distrib 10.4.34-MariaDB, for Win64 (AMD64)
--
-- Host: MariaDB-10.4    Database: netlancer
-- ------------------------------------------------------
-- Server version	10.4.34-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `netlancer`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `netlancer` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `netlancer`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_categories_parent` (`parent_id`),
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Дизайн','Всё для ваших идей!','line/palette.svg',1),(2,NULL,'Разработка и IT','Сайты, приложения и боты под ключ','line/code.svg',2),(3,NULL,'Тексты и переводы','Слова, которые работают на вас','line/file-text.svg',3),(4,NULL,'Маркетинг и SEO','Приведём клиентов и посчитаем результат','line/trending-up.svg',4),(5,NULL,'Аудио, видео, съёмка','Снимем, смонтируем и озвучим','line/video.svg',5),(10,1,'Логотип и брендинг',NULL,NULL,1),(11,1,'Презентации и инфографика',NULL,NULL,2),(12,1,'Арт и иллюстрации',NULL,NULL,3),(13,1,'Веб и мобильный дизайн',NULL,NULL,4),(14,1,'Маркетплейсы и соцсети',NULL,NULL,5),(15,1,'Интерьер и экстерьер',NULL,NULL,6),(16,1,'Обработка и редактирование',NULL,NULL,7),(17,1,'Полиграфия',NULL,NULL,8),(18,1,'Промышленный дизайн',NULL,NULL,9),(19,1,'Наружная реклама',NULL,NULL,10),(20,1,'ИИ-генерация изображений',NULL,NULL,11),(21,2,'Сайты',NULL,NULL,1),(22,2,'Программирование',NULL,NULL,2),(23,2,'Серверы и тестирование',NULL,NULL,3),(24,3,'Тексты',NULL,NULL,1),(25,3,'Переводы',NULL,NULL,2),(26,3,'Редактура',NULL,NULL,3),(27,4,'Поисковое продвижение',NULL,NULL,1),(28,4,'Соцсети и реклама',NULL,NULL,2),(29,4,'Аналитика',NULL,NULL,3),(30,5,'Видео',NULL,NULL,1),(31,5,'Аудио',NULL,NULL,2),(32,5,'Фото',NULL,NULL,3),(100,10,'Логотипы',NULL,'line/type.svg',1),(101,10,'Фирменный стиль',NULL,'line/palette.svg',2),(102,10,'Визитки',NULL,'line/credit-card.svg',3),(103,10,'Брендирование и сувениры',NULL,'line/gift.svg',4),(104,11,'Презентации',NULL,'line/monitor.svg',1),(105,11,'Инфографика',NULL,'line/pie-chart.svg',2),(106,11,'Карта и схема',NULL,'line/map.svg',3),(107,12,'Иллюстрации и рисунки',NULL,'line/pen.svg',1),(108,12,'Портрет по фото',NULL,'line/camera.svg',2),(109,12,'Дизайн игр',NULL,'line/gamepad.svg',3),(110,12,'Тату, принты',NULL,'line/shirt.svg',4),(111,12,'Стикеры',NULL,'line/smile.svg',5),(112,12,'NFT арт',NULL,'line/hexagon.svg',6),(113,12,'Готовые шаблоны и рисунки',NULL,'line/layout.svg',7),(114,13,'Веб-дизайн',NULL,'line/globe.svg',1),(115,13,'Мобильный дизайн',NULL,'line/smartphone.svg',2),(116,13,'Email-дизайн',NULL,'line/mail.svg',3),(117,13,'Баннеры и иконки',NULL,'line/image.svg',4),(118,13,'Юзабилити-аудит',NULL,'line/search.svg',5),(119,14,'Дизайн в соцсетях',NULL,'line/share.svg',1),(120,14,'Дизайн для маркетплейсов',NULL,'line/shopping-bag.svg',2),(121,15,'Интерьер',NULL,'line/home.svg',1),(122,15,'Дизайн домов и сооружений',NULL,'line/building.svg',2),(123,15,'Ландшафтный дизайн',NULL,'line/tree.svg',3),(124,15,'Дизайн мебели',NULL,'line/tool.svg',4),(125,16,'Отрисовка в вектор',NULL,'line/edit.svg',1),(126,16,'Фотомонтаж и обработка',NULL,'line/aperture.svg',2),(127,16,'3D-графика',NULL,'line/box.svg',3),(128,17,'Брошюра и буклет',NULL,'line/book-open.svg',1),(129,17,'Листовки и флаер',NULL,'line/file-text.svg',2),(130,17,'Плакат и афиша',NULL,'line/image.svg',3),(131,17,'Календарь и открытка',NULL,'line/calendar.svg',4),(132,17,'Каталог, меню, книга',NULL,'line/book-open.svg',5),(133,17,'Грамота и сертификат',NULL,'line/check-circle.svg',6),(134,17,'Гайд и чек-лист',NULL,'line/check-square.svg',7),(135,18,'Электроника и устройства',NULL,'line/smartphone.svg',1),(136,18,'Предметы и аксессуары',NULL,'line/gift.svg',2),(137,18,'Упаковка и этикетка',NULL,'line/box.svg',3),(138,19,'Билборды и стенды',NULL,'line/megaphone.svg',1),(139,19,'Витрины и вывески',NULL,'line/store.svg',2),(140,20,'Нейрофотосессия',NULL,'line/camera.svg',1),(141,20,'ИИ-аватары и портреты',NULL,'line/user.svg',2),(142,20,'ИИ-иллюстрации',NULL,'line/pen.svg',3),(143,20,'ИИ-логотипы и инфографика',NULL,'line/type.svg',4),(150,21,'Сайт под ключ',NULL,'line/globe.svg',1),(151,21,'Доработка сайта',NULL,'line/tool.svg',2),(152,21,'Вёрстка',NULL,'line/code.svg',3),(153,21,'Лендинг',NULL,'line/layout.svg',4),(154,22,'Чат-боты',NULL,'line/message.svg',1),(155,22,'Скрипты и парсеры',NULL,'line/terminal.svg',2),(156,22,'Мобильные приложения',NULL,'line/smartphone.svg',3),(157,22,'Программы для ПК',NULL,'line/monitor.svg',4),(158,23,'Настройка серверов',NULL,'line/server.svg',1),(159,23,'Тестирование',NULL,'line/check-square.svg',2),(160,24,'Статьи и посты',NULL,'line/file-text.svg',1),(161,24,'Копирайтинг',NULL,'line/pen.svg',2),(162,24,'Сценарии',NULL,'line/book-open.svg',3),(163,25,'Перевод текстов',NULL,'line/type.svg',1),(164,25,'Локализация',NULL,'line/map.svg',2),(165,26,'Корректура',NULL,'line/check-circle.svg',1),(166,26,'Редактирование',NULL,'line/edit.svg',2),(167,27,'SEO-оптимизация',NULL,'line/search.svg',1),(168,27,'Контекстная реклама',NULL,'line/target.svg',2),(169,28,'Ведение соцсетей',NULL,'line/share.svg',1),(170,28,'Реклама у блогеров',NULL,'line/megaphone.svg',2),(171,29,'Веб-аналитика',NULL,'line/bar-chart.svg',1),(172,29,'Исследование рынка',NULL,'line/pie-chart.svg',2),(173,30,'Монтаж видео',NULL,'line/film.svg',1),(174,30,'Анимация',NULL,'line/play-circle.svg',2),(175,30,'Ролики для соцсетей',NULL,'line/video.svg',3),(176,31,'Озвучка',NULL,'line/mic.svg',1),(177,31,'Музыка на заказ',NULL,'line/music.svg',2),(178,32,'Фотосъёмка',NULL,'line/camera.svg',1),(179,32,'Предметная съёмка',NULL,'line/aperture.svg',2);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dialogs`
--

DROP TABLE IF EXISTS `dialogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dialogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `executor_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_message_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dialogs_pair` (`customer_id`,`executor_id`),
  KEY `idx_dialogs_executor` (`executor_id`),
  KEY `fk_dialogs_order` (`order_id`),
  CONSTRAINT `fk_dialogs_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dialogs_executor` FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dialogs_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dialogs`
--

LOCK TABLES `dialogs` WRITE;
/*!40000 ALTER TABLE `dialogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `dialogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dialog_id` int(10) unsigned NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_messages_dialog` (`dialog_id`,`id`),
  KEY `idx_messages_sender` (`sender_id`),
  CONSTRAINT `fk_messages_dialog` FOREIGN KEY (`dialog_id`) REFERENCES `dialogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `executor_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `budget` int(10) unsigned NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('moderation','open','in_progress','completed','cancelled','rejected') NOT NULL DEFAULT 'moderation',
  `reject_reason` varchar(255) DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_orders_status_created` (`status`,`created_at`),
  KEY `idx_orders_category` (`category_id`),
  KEY `idx_orders_customer` (`customer_id`),
  KEY `idx_orders_executor` (`executor_id`),
  CONSTRAINT `fk_orders_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orders_executor` FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (32,17,18,100,'Kjujnbg','fjiefhwfhwhffewgergegr',100000,'2027-03-21','completed',NULL,0,'2026-10-29 11:44:15','2026-10-29 11:41:03','2026-10-29 11:44:25');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `executor_id` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `days` smallint(5) unsigned NOT NULL,
  `status` enum('sent','accepted','rejected') NOT NULL DEFAULT 'sent',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_responses_order_executor` (`order_id`,`executor_id`),
  KEY `idx_responses_executor` (`executor_id`),
  CONSTRAINT `fk_responses_executor` FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_responses_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responses`
--

LOCK TABLES `responses` WRITE;
/*!40000 ALTER TABLE `responses` DISABLE KEYS */;
INSERT INTO `responses` VALUES (30,32,18,'dwwwqdwwqdwdwq',100000,7,'accepted','2026-10-29 11:42:55');
/*!40000 ALTER TABLE `responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resumes`
--

DROP TABLE IF EXISTS `resumes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resumes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `about` text NOT NULL,
  `skills` varchar(255) NOT NULL,
  `rate` int(10) unsigned DEFAULT NULL,
  `portfolio` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_resumes_user` (`user_id`),
  KEY `idx_resumes_category` (`category_id`),
  CONSTRAINT `fk_resumes_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_resumes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resumes`
--

LOCK TABLES `resumes` WRITE;
/*!40000 ALTER TABLE `resumes` DISABLE KEYS */;
INSERT INTO `resumes` VALUES (12,18,100,'Крутой','10000000000000000000000000000000000000000000 лет','Вск',100,'','u18_photo_dd3e6e06bc328bc9.jpg','[\"u18_work_7e7be8053b300fd6.jpg\"]',0,'2026-10-28 11:14:17','2026-11-05 10:47:37');
/*!40000 ALTER TABLE `resumes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `executor_id` int(10) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reviews_order` (`order_id`),
  KEY `idx_reviews_executor` (`executor_id`),
  KEY `fk_reviews_customer` (`customer_id`),
  CONSTRAINT `fk_reviews_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_executor` FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_reviews_rating` CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (11,32,17,18,3,'Оцекнкиииииииииииии','2026-10-29 11:44:38');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','executor','admin') NOT NULL,
  `about` text DEFAULT NULL,
  `language` varchar(100) NOT NULL DEFAULT 'ru',
  `avatar` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Администратор','admin@example.com','$2y$12$LzKECfsWJyDAyMCX6VEKPuKP2wMXpb/OocyY4fkKLZbC4.ZMmLZa.','admin','Модератор площадки NetLancer.','ru',NULL,0,'2026-07-30 10:25:45'),(17,'Котелкин Матвей','tripoloski17@gmail.com','$2y$12$bswUwjc06bzxhQdMAwJXPecvUuiNymOOMKt5khurPhZCEIikZbEcO','customer',NULL,'ru',NULL,0,'2026-10-28 11:11:45'),(18,'Rv','tripoloski1@gmail.com','$2y$12$Zpts3sQYvJSj/iCJNjD.he9nltNN3bJoCQX2A9zLGRuop.nxvEli2','executor',NULL,'ru','u18_photo_dd3e6e06bc328bc9.jpg',0,'2026-10-28 11:13:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'netlancer'
--

--
-- Dumping routines for database 'netlancer'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-05 11:04:49
