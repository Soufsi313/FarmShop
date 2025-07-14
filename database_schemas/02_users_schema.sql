-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: farmshop
-- ------------------------------------------------------
-- Server version	11.5.2-MariaDB

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
-- Dumping routines for database 'farmshop'
--

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'France',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `newsletter_subscribed` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin_soufiane','Meftah Soufiane','s.mef2703@gmail.com','+32489446494','Avenue de la ferme','82/12','BRUXELLES','1000','BE','2025-07-13 05:44:18','$2y$12$/e3KFpQ5LE51MEZJXjmXuuPrwROXlLDPdqe7uhXFSyS.upNXBY0Ye','Admin',1,NULL,'2025-07-13 05:44:18','2025-07-13 05:47:06',NULL),(2,'jean_dupont_1','Jean Dupont','jean.dupont.1@laposte.net','+32 3 24 29 34','Rue de la Loi 16','585/40','Bruxelles','1000','BE','2024-10-30 06:52:04','$2y$12$tTsFf4b0l1wFlKPsIX.L7ePRkRbRDr19I5EcUJ1CsZLiMHnBTOME6','User',1,NULL,'2024-08-30 05:52:04','2025-06-13 05:52:04',NULL),(3,'marie_martin_2','Marie Martin','marie.martin.2@hotmail.com','+32 2 27 34 89','Avenue Louise 54','32/98','Bruxelles','1050','BE','2025-02-16 06:52:04','$2y$12$id/XQl4g00maxmaOhHyqMO0mxTCA.foi7L/uBRv/gXIWFD3UKd9VW','User',1,NULL,'2024-03-15 06:52:05','2025-06-13 05:52:05',NULL),(4,'pierre_bernard_3','Pierre Bernard','pierre.bernard.3@gmail.com','+32 2 69 71 32','Chaussee de Wavre 112','770/40','Bruxelles','1050','BE','2025-05-03 05:52:05','$2y$12$9flRYjb39hhZfcDDNfzBt.TOACBkKjnVeIQep5yndi6ME/KbsSteq','User',1,NULL,'2024-07-24 05:52:05','2025-07-06 05:52:05',NULL),(5,'sophie_dubois_4','Sophie Dubois','sophie.dubois.4@free.fr','+32 4 55 58 54','Rue Neuve 89','785/62','Bruxelles','1000','BE','2024-10-20 05:52:05','$2y$12$lw67930KZqajIfBJx5tpC.j62tdvPaAU2FqatBTemGjWe4s.3yzRq','User',1,NULL,'2024-09-19 05:52:05','2025-07-04 05:52:05',NULL),(6,'antoine_moreau_5','Antoine Moreau','antoine.moreau.5@outlook.com','+32 3 57 19 29','Boulevard Anspach 45','762/4','Bruxelles','1000','BE','2024-10-23 05:52:05','$2y$12$Q7zeAHq4poxlB/8cjktgS.mpHEG0I6grMF3zv0MxBA98HiMCorBte','User',1,NULL,'2024-03-18 06:52:05','2025-06-25 05:52:05',NULL),(7,'camille_laurent_6','Camille Laurent','camille.laurent.6@wanadoo.fr','+32 2 10 39 84','Place Sainte-Catherine 12','844/89','Bruxelles','1000','BE','2024-08-03 05:52:05','$2y$12$gZzUxrqh3qqaME/fORIIw.ZDg.UtGeJ64wjWZ0omoRilSPzswwoAi','User',1,NULL,'2024-02-12 06:52:05','2025-06-16 05:52:05',NULL),(8,'nicolas_simon_7','Nicolas Simon','nicolas.simon.7@laposte.net','+32 4 76 88 68','Rue des Bouchers 23','321/73','Bruxelles','1000','BE','2025-03-14 06:52:05','$2y$12$renRZMpcO4jMiez.vKoW8uC.90cHVaI2h5S5634.Soynu.T8NINOq','User',1,NULL,'2025-03-10 06:52:06','2025-06-14 05:52:06',NULL),(9,'emma_michel_8','Emma Michel','emma.michel.8@yahoo.fr','+32 11 21 76 71','Avenue de Tervueren 78','560/56','Bruxelles','1040','BE','2025-04-26 05:52:06','$2y$12$ukHNIH5Ca507u2DTQ5C2f.WaRbOPd91/WnjzKRXVH24pAi9gbkrse','User',1,NULL,'2024-09-26 05:52:06','2025-07-05 05:52:06',NULL),(10,'lucas_leroy_9','Lucas Leroy','lucas.leroy.9@hotmail.com','+32 2 40 65 22','Chaussee de Louvain 234','725/78','Bruxelles','1210','BE','2024-09-14 05:52:06','$2y$12$COVRUKI1SGUXCgzto6TYOOCdz8t7BCj6IzIJ7Y2HcFH.4rtP1lNGC','User',1,NULL,'2024-07-27 05:52:06','2025-07-10 05:52:06',NULL),(11,'clara_roux_10','Clara Roux','clara.roux.10@hotmail.com','+32 3 85 83 76','Rue du Marche aux Herbes 67','574/90','Bruxelles','1000','BE','2024-12-25 06:52:06','$2y$12$pryQRjSMg3ipCdO1Q5p1uupcf4RI/WRkqkM/OrVxnt.Sa85xMb6/2','User',1,NULL,'2025-02-03 06:52:06','2025-07-08 05:52:06',NULL),(12,'thomas_david_11','Thomas David','thomas.david.11@gmail.com','+32 10 78 80 84','Meir 145','710/78','Anvers','2000','BE','2025-02-26 06:52:06','$2y$12$Qfxcyq3gGbz4/WmfkLkbme4Mk5LmbRJx4ta/RPqkscIANAO0gcCAO','User',1,NULL,'2024-06-13 05:52:06','2025-07-01 05:52:06',NULL),(13,'julie_bertrand_12','Julie Bertrand','julie.bertrand.12@free.fr','+32 3 23 86 72','Groenplaats 34','257/24','Anvers','2000','BE','2024-08-03 05:52:06','$2y$12$168q8YdsxGkTl6DKdEdEfuLxHLUhpkYgtrEgZAAxXqQSs22Dj71Lu','User',1,NULL,'2024-01-23 06:52:06','2025-06-23 05:52:06',NULL),(14,'alexandre_petit_13','Alexandre Petit','alexandre.petit.13@hotmail.com','+32 9 97 38 62','Lange Nieuwstraat 89','108/57','Anvers','2000','BE','2024-09-25 05:52:07','$2y$12$l9XTsBVts4zzIJY/qYI./.gcg2pkYns0LVgYpywd5QIj/1mO/xTkS','User',1,NULL,'2025-04-14 05:52:07','2025-06-16 05:52:07',NULL),(15,'manon_garcia_14','Manon Garcia','manon.garcia.14@free.fr','+32 2 80 11 39','Koningin Astridplein 12','222/76','Anvers','2018','BE','2025-02-16 06:52:07','$2y$12$BkVyg2bGfsVmbvDY64G8teU0SQ3xOmqNaT8aId9mscbhfDqUfNUTS','User',1,NULL,'2024-08-06 05:52:07','2025-07-11 05:52:07',NULL),(16,'maxime_rodriguez_15','Maxime Rodriguez','maxime.rodriguez.15@gmail.com','+32 11 37 62 80','Frankrijklei 56','474/83','Anvers','2000','BE','2025-03-16 06:52:07','$2y$12$8vGtcEELGZDv4esdVfed1.JLCwi4N0vJOiIPMNAMaAS5A0He6mZhG','User',1,NULL,'2025-04-14 05:52:07','2025-06-25 05:52:07',NULL),(17,'laura_fernandez_16','Laura Fernandez','laura.fernandez.16@orange.fr','+32 10 69 17 84','Grote Markt 23','991/9','Gand','9000','BE','2025-06-24 05:52:07','$2y$12$bnylUSc9.HgLoVYVfaOOAetBoyaz20U.eb6GTL4v7CXeqf2J7Lshq','User',1,NULL,'2024-06-08 05:52:07','2025-07-07 05:52:07',NULL),(18,'hugo_lopez_17','Hugo Lopez','hugo.lopez.17@yahoo.fr','+32 2 75 37 75','Korenlei 45','250/54','Gand','9000','BE','2025-01-07 06:52:07','$2y$12$8ctt4jXDTufo8tpZ80XVLOi5TShiBYw494.wCJ9oSrHXBzXRMAHXW','User',1,NULL,'2025-06-20 05:52:07','2025-07-13 05:52:07',NULL),(19,'lea_martinez_18','Lea Martinez','lea.martinez.18@free.fr','+32 3 62 53 99','Veldstraat 78','231/16','Gand','9000','BE','2025-03-05 06:52:07','$2y$12$O7O7k/Sm86sYYU/bDNrGgOgWEXUIYMW/YYcoMHO4WnX0ppG4rariK','User',1,NULL,'2024-12-14 06:52:08','2025-06-28 05:52:08',NULL),(20,'arthur_gonzalez_19','Arthur Gonzalez','arthur.gonzalez.19@wanadoo.fr','+32 10 75 13 35','Sint-Baafsplein 12','488/70','Gand','9000','BE','2025-04-06 05:52:08','$2y$12$OPM2amuC6za32/SFr8Zn7OhEa/90RfB.0.bqR1tlv7EN8uexWpn2K','User',1,NULL,'2025-05-17 05:52:08','2025-06-21 05:52:08',NULL),(21,'chloe_perez_20','Chloe Perez','chloe.perez.20@laposte.net','+32 2 98 17 68','Vrijdagmarkt 34','819/76','Gand','9000','BE','2024-09-03 05:52:08','$2y$12$iCw24cb3.A/8jbuRMg0A7uSj.qChqRsF1xboDyeKdNuC3ZuyuEMmm','User',1,NULL,'2025-02-11 06:52:08','2025-07-02 05:52:08',NULL),(22,'louis_sanchez_21','Louis Sanchez','louis.sanchez.21@gmail.com','+32 9 99 80 76','Markt 67','695/7','Bruges','8000','BE','2025-05-08 05:52:08','$2y$12$wGphvq4UhUKbbs5.EEdwQe0OWAMvrzAsMekR0tqm1.XUX.65fHtW6','User',1,NULL,'2024-06-08 05:52:08','2025-06-28 05:52:08',NULL),(23,'sarah_ramirez_22','Sarah Ramirez','sarah.ramirez.22@yahoo.fr','+32 9 70 23 20','Steenstraat 89','463/32','Bruges','8000','BE','2025-06-20 05:52:08','$2y$12$mhQFOX7ZPv1sq3EiKp5qv.1jIs0u9sGm/3gpV7MIvUITlADxDHchO','User',1,NULL,'2023-08-27 05:52:08','2025-06-21 05:52:08',NULL),(24,'gabriel_torres_23','Gabriel Torres','gabriel.torres.23@yahoo.fr','+32 11 79 63 18','Wollestraat 23','245/59','Bruges','8000','BE','2024-10-23 05:52:08','$2y$12$fSUkFn5iYg51zbh6PfMtmOaUXDMJ9pVRKnINpaLUcRfe3fGX5b4m.','User',1,NULL,'2025-07-05 05:52:09','2025-07-02 05:52:09',NULL),(25,'lina_flores_24','Lina Flores','lina.flores.24@free.fr','+32 10 14 49 67','Simon Stevinplein 45','418/77','Bruges','8000','BE','2025-02-24 06:52:09','$2y$12$gIpNlVbXqkCeIoNjbokYru5XK9gOdIrM.DloKEe6aII5xnfDk.Ybq','User',1,NULL,'2025-01-14 06:52:09','2025-07-03 05:52:09',NULL),(26,'nathan_rivera_25','Nathan Rivera','nathan.rivera.25@orange.fr','+32 2 36 67 90','Katelijnestraat 12','685/34','Bruges','8000','BE','2024-11-02 06:52:09','$2y$12$wzvn0TEQvhEYeWJYYLqwne4HTZWAy9Vz6VJBnN8wbwhVm5GJdU.7u','User',1,NULL,'2025-02-11 06:52:09','2025-07-10 05:52:09',NULL),(27,'ahmed_benali_26','Ahmed Benali','ahmed.benali.26@outlook.com','+32 4 24 93 57','Place Saint-Lambert 34','178/33','Li├¿ge','4000','BE','2025-01-06 06:52:09','$2y$12$nOwfgOg7MvHwqhb0FQbGvuKvWtLRLkLE8K6TjjU99Bem8GcbSFlxm','User',1,NULL,'2023-09-26 05:52:09','2025-07-06 05:52:09',NULL),(28,'fatima_alaoui_27','Fatima Alaoui','fatima.alaoui.27@yahoo.fr','+32 2 47 49 32','Rue de la Regence 78','255/84','Li├¿ge','4000','BE','2025-05-13 05:52:09','$2y$12$xRfw4Pnm8qDq.myNOxTRmun22TpfmgOzgvBwWOFStwO2hXHGJk5US','User',1,NULL,'2023-08-20 05:52:09','2025-07-13 05:52:09',NULL),(29,'mohammed_bouali_28','Mohammed Bouali','mohammed.bouali.28@free.fr','+32 4 90 63 32','Boulevard de la Sauveniere 123','183/76','Li├¿ge','4000','BE','2024-09-30 05:52:09','$2y$12$xKAmIiTtr36p.BYLYtbFlOXpmYZuMuQHkIDoRPY9Fa9QeXpnD0dxO','User',1,NULL,'2024-09-29 05:52:10','2025-07-07 05:52:10',NULL),(30,'aicha_mansouri_29','Aicha Mansouri','aicha.mansouri.29@hotmail.com','+32 4 42 34 69','Rue Pont d\'Avroy 56','671/17','Li├¿ge','4000','BE','2024-12-16 06:52:10','$2y$12$YgldHsXRl.z0JwCqmqS9IeLNyNF7Sy5M9gQfMXEwJgJa19KPojg5a','User',1,NULL,'2025-04-29 05:52:10','2025-06-30 05:52:10',NULL),(31,'omar_hassani_30','Omar Hassani','omar.hassani.30@free.fr','+32 11 10 53 83','Place du Marche 89','383/29','Li├¿ge','4000','BE','2024-09-23 05:52:10','$2y$12$5EG7aEmpE9loJM4tQpgDee0RnC65J/zVv7LKEEMOsHZO6oxo/Ok1i','User',1,NULL,'2024-02-19 06:52:10','2025-07-11 05:52:10',NULL),(32,'khadija_benkirane_31','Khadija Benkirane','khadija.benkirane.31@yahoo.fr','+33 1 14 56 30 86','Rue de Rivoli 123','61/1','Paris','75001','FR','2025-03-25 06:52:10','$2y$12$oQW.RLYztuIQFWdkINsnGuMk9hNm/2yW5EUBbcEG/dScROeh/0cra','User',1,NULL,'2025-04-12 05:52:10','2025-07-08 05:52:10',NULL),(33,'youssef_tounsi_32','Youssef Tounsi','youssef.tounsi.32@hotmail.com','+33 4 47 74 34 93','Avenue des Champs-Elysees 456','849/78','Paris','75008','FR','2025-06-19 05:52:10','$2y$12$8GUCNw5C/8CdS97lK4.lHOFVArvBZoznwnXYGhSWeMK6P5PqfY7d.','User',1,NULL,'2025-05-26 05:52:10','2025-06-20 05:52:10',NULL),(34,'zineb_amrani_33','Zineb Amrani','zineb.amrani.33@outlook.com','+33 2 57 60 14 69','Boulevard Saint-Germain 789','691/73','Paris','75006','FR','2024-11-26 06:52:10','$2y$12$MHZLYwhFsXniFMT.a7a/4OCr0wQyEjslpsttdDmGFuQHXTQA5Nu.O','User',1,NULL,'2024-08-30 05:52:10','2025-06-13 05:52:10',NULL),(35,'karim_belkacem_34','Karim Belkacem','karim.belkacem.34@laposte.net','+33 5 90 68 93 55','Rue de la Paix 34','762/55','Paris','75002','FR','2024-09-18 05:52:10','$2y$12$BVyrMGUQNLrp9q4csn.h1ep.pH0xH2p1wGjbQIJmRjfEiULxjxAg.','User',1,NULL,'2024-11-27 06:52:11','2025-06-14 05:52:11',NULL),(36,'samira_cherif_35','Samira Cherif','samira.cherif.35@laposte.net','+33 3 20 89 20 27','Place Vendome 12','639/45','Paris','75001','FR','2024-11-08 06:52:11','$2y$12$Zx5YbiM0roJTOsLE9F6lfuAKF2yrW/z6fMwKkx3EQqNZS2s9i7XFa','User',1,NULL,'2025-05-16 05:52:11','2025-06-20 05:52:11',NULL),(37,'amine_benjelloun_36','Amine Benjelloun','amine.benjelloun.36@free.fr','+33 2 39 60 33 69','Rue du Faubourg Saint-Honore 67','232/66','Paris','75008','FR','2025-01-09 06:52:11','$2y$12$PNGGxjn876w49I5NFjJZAetOwR37fxFKon6VSSe78sWuEp2WD.BNK','User',1,NULL,'2025-06-15 05:52:11','2025-06-27 05:52:11',NULL),(38,'leila_kadiri_37','Leila Kadiri','leila.kadiri.37@outlook.com','+33 2 67 94 53 99','Avenue Montaigne 23','710/95','Paris','75008','FR','2024-10-24 05:52:11','$2y$12$J7TdlCEeNeQS81EIdBrVvO0xv4k6wnQzI.91JXgRrrflUvRZF/S/S','User',1,NULL,'2025-05-25 05:52:11','2025-07-12 05:52:11',NULL),(39,'mehdi_sefrioui_38','Mehdi Sefrioui','mehdi.sefrioui.38@hotmail.com','+33 2 22 37 55 76','Rue Saint-Antoine 89','378/77','Paris','75004','FR','2025-03-30 05:52:11','$2y$12$DsYMbP7G9IXp5Rl5u05YkuMbDwwSPmk4RolchyN3uZsq3W.wr.iwu','User',1,NULL,'2025-03-14 06:52:11','2025-07-11 05:52:11',NULL),(40,'nadia_bouazza_39','Nadia Bouazza','nadia.bouazza.39@yahoo.fr','+33 1 96 40 11 93','Boulevard Haussmann 145','49/87','Paris','75009','FR','2024-11-28 06:52:11','$2y$12$jtjr/WVVhJFLchywA3D7IewFiATcr.FXyFA.SY179lPvvqIZ/FiO.','User',1,NULL,'2024-04-01 05:52:12','2025-06-25 05:52:12',NULL),(41,'samir_lamrani_40','Samir Lamrani','samir.lamrani.40@outlook.com','+33 4 94 40 62 51','Rue de la Roquette 56','831/60','Paris','75011','FR','2025-06-20 05:52:12','$2y$12$c.4RTU6IVuLUowKmHXgPBuJehCk7ztFWFOKiwZM0W5CM71CJQhj1m','User',1,NULL,'2023-11-29 06:52:12','2025-06-22 05:52:12',NULL),(42,'souad_bennani_41','Souad Bennani','souad.bennani.41@outlook.com','+33 2 62 77 34 93','Cours Mirabeau 78','971/32','Aix-en-Provence','13100','FR','2024-09-16 05:52:12','$2y$12$ctZoq3jml/JP9xRrPGhHQe3iubBsyHxm0ZSQrukf5RSDf2KD0FkAW','User',1,NULL,'2024-05-20 05:52:12','2025-07-09 05:52:12',NULL),(43,'rachid_zemmouri_42','Rachid Zemmouri','rachid.zemmouri.42@orange.fr','+33 5 66 78 86 20','Place des Cardeurs 23','664/84','Aix-en-Provence','13100','FR','2024-12-11 06:52:12','$2y$12$oqZaF0Krwkl24DBElbORvONjmGmVJf8tTqUHRtZcRHe6krGmqbuHW','User',1,NULL,'2023-12-19 06:52:12','2025-07-06 05:52:12',NULL),(44,'hafsa_mimouni_43','Hafsa Mimouni','hafsa.mimouni.43@gmail.com','+33 5 66 96 16 13','Rue Espariat 45','136/3','Aix-en-Provence','13100','FR','2025-06-18 05:52:12','$2y$12$baDOOR6eoBD6RR6v0LHO6eU/OWybXsqBHzqjCzkgWqV0a14J0y1G.','User',1,NULL,'2023-12-03 06:52:13','2025-06-26 05:52:13',NULL),(45,'tarik_chraibi_44','Tarik Chraibi','tarik.chraibi.44@outlook.com','+33 5 10 39 24 15','Avenue Victor Hugo 67','809/27','Aix-en-Provence','13100','FR','2024-07-18 05:52:13','$2y$12$2MbR2UkUAgIuBXP0zE9Xjuu9a0LbdTc3lrE2RyUol78vzlNFtlWSG','User',1,NULL,'2024-03-12 06:52:13','2025-06-21 05:52:13',NULL),(46,'malika_fassi_45','Malika Fassi','malika.fassi.45@wanadoo.fr','+33 4 64 67 61 23','Place de la Rotonde 12','557/31','Aix-en-Provence','13100','FR','2025-04-07 05:52:13','$2y$12$9.INAxK1xgiMVWc/g/T1SeC83nVuZXS7o.PJB9ES0FGRQwVRmkpia','User',1,NULL,'2025-05-21 05:52:13','2025-07-13 05:52:13',NULL),(47,'abdel_filali_46','Abdel Filali','abdel.filali.46@gmail.com','+33 6 52 43 84 13','La Canebiere 123','107/15','Marseille','13001','FR','2024-12-10 06:52:13','$2y$12$ghVNNTK/S8VCiMlEj7xU7OsacEc7BuNwyt6piRTJD1N9uhWnHPvwW','User',1,NULL,'2024-03-20 06:52:13','2025-07-13 05:52:13',NULL),(48,'jamila_tahiri_47','Jamila Tahiri','jamila.tahiri.47@laposte.net','+33 1 86 75 18 60','Cours Julien 45','658/15','Marseille','13006','FR','2025-02-11 06:52:13','$2y$12$ANWyNM4DhrThaMiFYudGxeSMO7xTshqPm8U77e/zIC3uu9L/nUhBu','User',1,NULL,'2024-07-15 05:52:13','2025-06-19 05:52:13',NULL),(49,'khalid_amellal_48','Khalid Amellal','khalid.amellal.48@laposte.net','+33 4 57 21 81 96','Rue de la Republique 89','813/20','Marseille','13002','FR','2025-01-17 06:52:13','$2y$12$o7B/EJv.xlkiZH.lpNkF7ek9SO/8m.krP.vgXDSgQABWX3Ljphr4e','User',1,NULL,'2024-12-15 06:52:13','2025-06-14 05:52:13',NULL),(50,'rajae_bensouda_49','Rajae Bensouda','rajae.bensouda.49@gmail.com','+33 2 92 15 74 60','Avenue du Prado 234','84/34','Marseille','13008','FR','2024-12-19 06:52:13','$2y$12$8kk3IjfjsMjiBOzgjYrF4u6bh/GYISpPa7mx28FZ5D2Tpl1yoJkqa','User',1,NULL,'2024-04-27 05:52:14','2025-06-23 05:52:14',NULL),(51,'driss_berrada_50','Driss Berrada','driss.berrada.50@laposte.net','+33 1 41 88 76 79','Corniche Kennedy 67','554/36','Marseille','13007','FR','2025-02-06 06:52:14','$2y$12$E9OuTTjlC/eYkKWcXcb4aeoYsx2ModqzwCf2B4/NQRbmQazTf1MCy','User',1,NULL,'2024-06-25 05:52:14','2025-07-09 05:52:14',NULL),(52,'kwame_asante_51','Kwame Asante','kwame.asante.51@outlook.com','+33 5 14 36 46 74','Place Bellecour 34','888/5','Lyon','69002','FR','2024-08-31 05:52:14','$2y$12$jrimbuOd49xLqYCm55oU/uILgiYkPRPfryk/XSUF2kgK7mb34pHM.','User',1,NULL,'2023-12-29 06:52:14','2025-06-16 05:52:14',NULL),(53,'ama_osei_52','Ama Osei','ama.osei.52@gmail.com','+33 1 45 17 22 69','Rue de la Republique 78','529/84','Lyon','69002','FR','2024-10-12 05:52:14','$2y$12$L1yZOILQhkhluDEgKWzaKOwPeBfDopb18UnwgT1tJaANqjGnDDDS2','User',1,NULL,'2025-01-26 06:52:14','2025-07-09 05:52:14',NULL),(54,'kofi_mensah_53','Kofi Mensah','kofi.mensah.53@laposte.net','+33 4 75 67 25 97','Cours Lafayette 123','798/14','Lyon','69003','FR','2025-05-18 05:52:14','$2y$12$37Jsg5hSpYV5ZJUkrQs3eeT9yI9/9QLzLpqfVeT2.1T/qiAWDUsBW','User',1,NULL,'2024-08-12 05:52:14','2025-06-19 05:52:14',NULL),(55,'akosua_boateng_54','Akosua Boateng','akosua.boateng.54@yahoo.fr','+33 4 52 80 65 58','Avenue Jean Jaures 56','709/40','Lyon','69007','FR','2024-08-07 05:52:14','$2y$12$mhwa7qPxSRyZzt6XzweHXeLB0epNTAZ/mfu4doH09CIzy.B.r.or6','User',1,NULL,'2024-02-12 06:52:15','2025-06-30 05:52:15',NULL),(56,'sekou_traore_55','Sekou Traore','sekou.traore.55@free.fr','+33 6 50 72 32 62','Place des Terreaux 12','651/36','Lyon','69001','FR','2024-08-30 05:52:15','$2y$12$1rr.iSDBLoWppmDSCWlUpehaMHrTU.8bhGEUMmNNsj/p9Vs1MJ6tq','User',1,NULL,'2023-12-31 06:52:15','2025-06-23 05:52:15',NULL),(57,'aminata_keita_56','Aminata Keita','aminata.keita.56@orange.fr','+33 6 29 77 16 33','Place du Capitole 45','761/90','Toulouse','31000','FR','2025-06-28 05:52:15','$2y$12$62JUdQSXCVVTwlfhw1G1c.8Fj1znLUZDiiL/b4uCPqwG1MS6JatgG','User',1,NULL,'2024-12-20 06:52:15','2025-06-19 05:52:15',NULL),(58,'ibrahim_diallo_57','Ibrahim Diallo','ibrahim.diallo.57@laposte.net','+33 1 99 57 11 37','Rue de Metz 89','775/76','Toulouse','31000','FR','2024-12-30 06:52:15','$2y$12$tuMMQINTa.A7lm1QNyoSo.dkH33fDWzGXcIhSyaCnAITg8G5VLoZi','User',1,NULL,'2025-05-23 05:52:15','2025-06-20 05:52:15',NULL),(59,'mariama_sow_58','Mariama Sow','mariama.sow.58@yahoo.fr','+33 3 47 46 49 35','Allees Jean Jaures 23','300/6','Toulouse','31000','FR','2025-01-31 06:52:15','$2y$12$hZH3pYi90eAFlDVMWMRnWORvwy.bZmPx7xytyFmKY4.KfmHKbXxhO','User',1,NULL,'2025-06-01 05:52:15','2025-06-29 05:52:15',NULL),(60,'moussa_camara_59','Moussa Camara','moussa.camara.59@yahoo.fr','+33 2 52 69 33 33','Boulevard de Strasbourg 67','184/87','Toulouse','31000','FR','2024-10-25 05:52:15','$2y$12$LybJv7fhk5b/tihZc5.9D.sn6J3j7PUU7WSQN2OjZrJDcAkRX1/QK','User',1,NULL,'2024-04-25 05:52:15','2025-07-10 05:52:15',NULL),(61,'fatoumata_kone_60','Fatoumata Kone','fatoumata.kone.60@gmail.com','+33 3 86 50 57 86','Rue Saint-Rome 34','979/34','Toulouse','31000','FR','2025-03-04 06:52:15','$2y$12$ct12ZwR2x/iQwGHCGRvV5uzVC2rL9Ei27UvZu1.kbdXASnGOXn90y','User',1,NULL,'2024-07-10 05:52:16','2025-06-29 05:52:16',NULL),(62,'amadou_sidibe_61','Amadou Sidibe','amadou.sidibe.61@orange.fr','+32 4 27 26 45','Rue de la Loi 16','172/65','Bruxelles','1000','BE','2025-05-19 05:52:16','$2y$12$VFIyXvKP2slKLIPrn8CusuiIH2/sihJMUdbyGOG9tIaC7AtEOGTj2','User',1,NULL,'2024-12-24 06:52:16','2025-07-09 05:52:16',NULL),(63,'rokia_sangare_62','Rokia Sangare','rokia.sangare.62@outlook.com','+32 10 92 48 82','Avenue Louise 54','377/13','Bruxelles','1050','BE','2025-06-13 05:52:16','$2y$12$E8apQW483R7kCg0c4VucROjErATH7ll3UzUKNx9bvjZo1Lo70yw5K','User',1,NULL,'2024-06-26 05:52:16','2025-06-24 05:52:16',NULL),(64,'ousmane_coulibaly_63','Ousmane Coulibaly','ousmane.coulibaly.63@outlook.com','+32 10 27 34 63','Chaussee de Wavre 112','672/9','Bruxelles','1050','BE','2025-06-12 05:52:16','$2y$12$S9tXcTyGdqdd64z5gBKq7uDMCTe9k3qSxY0dJTsiIYrcdOmpEHmzq','User',1,NULL,'2024-05-20 05:52:16','2025-07-08 05:52:16',NULL),(65,'salimata_diabate_64','Salimata Diabate','salimata.diabate.64@outlook.com','+32 11 50 74 94','Rue Neuve 89','815/74','Bruxelles','1000','BE','2025-07-08 05:52:16','$2y$12$DAHvBJt7HrEQIXIFh5LkDeR.mROtZMmisyb/xqz/eqdBGjxf2FfVi','User',1,NULL,'2024-08-10 05:52:16','2025-06-17 05:52:16',NULL),(66,'lamine_toure_65','Lamine Toure','lamine.toure.65@outlook.com','+32 2 14 15 96','Boulevard Anspach 45','532/37','Bruxelles','1000','BE','2025-05-09 05:52:17','$2y$12$BhgfILDYfQRkhnft3l62p.LUBnEV4zkvutb8CYiGjUUNWD7puLeBW','User',1,NULL,'2024-08-19 05:52:17','2025-06-25 05:52:17',NULL),(67,'adama_konate_66','Adama Konate','adama.konate.66@wanadoo.fr','+32 4 72 63 59','Place Sainte-Catherine 12','672/32','Bruxelles','1000','BE','2025-06-16 05:52:17','$2y$12$jqmo04TEPL2yOezlppGdvOPROG.fGmr3FrZo3R.4nXLXH5cIeb4Hq','User',1,NULL,'2023-10-22 05:52:17','2025-07-02 05:52:17',NULL),(68,'bakary_doucoure_67','Bakary Doucoure','bakary.doucoure.67@free.fr','+32 11 50 51 62','Rue des Bouchers 23','348/39','Bruxelles','1000','BE','2025-01-02 06:52:17','$2y$12$vtymYWk3xgwNBNuUpfNY9.c6P6DlzHcA7aLbjLhMogN87ChVyWQMK','User',1,NULL,'2023-09-04 05:52:17','2025-07-02 05:52:17',NULL),(69,'ramata_sissoko_68','Ramata Sissoko','ramata.sissoko.68@yahoo.fr','+32 4 58 36 13','Avenue de Tervueren 78','501/36','Bruxelles','1040','BE','2025-02-24 06:52:17','$2y$12$gx8xFWJuN9YmBFRO4O9IAO1RbTd4saTVGRUX2MOvhho3AkXhKuJXa','User',1,NULL,'2024-05-30 05:52:17','2025-07-10 05:52:17',NULL),(70,'yaya_berthe_69','Yaya Berthe','yaya.berthe.69@gmail.com','+32 3 72 99 42','Chaussee de Louvain 234','142/7','Bruxelles','1210','BE','2024-10-04 05:52:17','$2y$12$a6fJ94QD9XJ0WicDadg/XeXctjgHiZ5.El8U17xwzVYDFR1ozsH.i','User',1,NULL,'2025-03-09 06:52:17','2025-06-30 05:52:17',NULL),(71,'awa_dembele_70','Awa Dembele','awa.dembele.70@hotmail.com','+32 2 94 27 60','Rue du Marche aux Herbes 67','449/22','Bruxelles','1000','BE','2024-11-07 06:52:17','$2y$12$7Zl9jWddeJrqFcLbOiZV1.4mEYq0URYJe97/ATno4kSqCnWVhoKtO','User',1,NULL,'2024-01-07 06:52:18','2025-06-21 05:52:18',NULL),(72,'mamadou_barry_71','Mamadou Barry','mamadou.barry.71@free.fr','+32 9 38 46 13','Meir 145','768/93','Anvers','2000','BE','2025-05-25 05:52:18','$2y$12$BQXijsPsVoKmzYZhW1KsheBK7U6XPv3NE3u8nCOEEBzimzxAmmecW','User',1,NULL,'2024-09-08 05:52:18','2025-06-26 05:52:18',NULL),(73,'hawa_bah_72','Hawa Bah','hawa.bah.72@hotmail.com','+32 2 41 68 31','Groenplaats 34','747/82','Anvers','2000','BE','2024-09-03 05:52:18','$2y$12$CfIBaHlx8uG7mVoOnI7EwOQonkG.vSkMMpJedN0PG8LHHrONp6iAq','User',1,NULL,'2024-12-14 06:52:18','2025-07-06 05:52:18',NULL),(74,'souleymane_kaba_73','Souleymane Kaba','souleymane.kaba.73@yahoo.fr','+32 11 49 92 10','Lange Nieuwstraat 89','572/90','Anvers','2000','BE','2025-01-06 06:52:18','$2y$12$GpX55xriXXqRDYaQZ.ED9uPz4W6jN43YQTRLo4H2DyLoiIYYQQgxa','User',1,NULL,'2025-03-29 06:52:18','2025-06-13 05:52:18',NULL),(75,'mawa_fofana_74','Mawa Fofana','mawa.fofana.74@laposte.net','+32 9 96 12 12','Koningin Astridplein 12','39/58','Anvers','2018','BE','2025-03-25 06:52:18','$2y$12$Po9UmrC6ODc0ftKpLhwInuGX84T/y5TZ2EhdgT5lQm6XorEnbb8LG','User',1,NULL,'2024-10-11 05:52:18','2025-07-06 05:52:18',NULL),(76,'boubacar_sylla_75','Boubacar Sylla','boubacar.sylla.75@free.fr','+32 9 74 18 89','Frankrijklei 56','928/31','Anvers','2000','BE','2025-06-03 05:52:18','$2y$12$Pc5KorpZdKFydaPdLhxCf.esS2YztURVeDzcNm7/y1GoguqkPjqkq','User',1,NULL,'2023-12-28 06:52:19','2025-06-22 05:52:19',NULL),(77,'wei_chen_76','Wei Chen','wei.chen.76@free.fr','+32 3 66 82 52','Grote Markt 23','3/14','Gand','9000','BE','2025-04-03 05:52:19','$2y$12$c6Cti9qqXnpzI7Ee/Ytj/.p2qDXto8x1WOXK19yUFFAaemLwojkae','User',1,NULL,'2024-09-16 05:52:19','2025-06-15 05:52:19',NULL),(78,'li_wang_77','Li Wang','li.wang.77@hotmail.com','+32 9 95 49 57','Korenlei 45','951/6','Gand','9000','BE','2024-10-05 05:52:19','$2y$12$BmjPkgP103AQuAv0wh64leFN6staJt84GIcaJVyK.N54oVIH1bg16','User',1,NULL,'2023-08-27 05:52:19','2025-06-20 05:52:19',NULL),(79,'ming_zhang_78','Ming Zhang','ming.zhang.78@wanadoo.fr','+32 3 63 85 67','Veldstraat 78','16/74','Gand','9000','BE','2025-02-07 06:52:19','$2y$12$D521IzB9s1B41XRyFBliPuAYhGIZYtwp5fa4FMdoBA62q7G7Ay7OK','User',1,NULL,'2023-08-10 05:52:19','2025-06-20 05:52:19',NULL),(80,'mei_liu_79','Mei Liu','mei.liu.79@hotmail.com','+32 11 99 56 48','Sint-Baafsplein 12','672/95','Gand','9000','BE','2025-02-16 06:52:19','$2y$12$7jbZ9VF9PL5OH1VW9pRZp.XmyoJaGJxCg.ayOLhWrlO4olzmSk1DK','User',1,NULL,'2024-12-12 06:52:19','2025-06-13 05:52:19',NULL),(81,'hiroshi_tanaka_80','Hiroshi Tanaka','hiroshi.tanaka.80@free.fr','+32 4 26 64 38','Vrijdagmarkt 34','116/83','Gand','9000','BE','2024-10-12 05:52:19','$2y$12$9nSDavRveiaNDP9m./5S1.KM87HtOYHbFNhgypUwVH/IJA5r87aT2','User',1,NULL,'2024-05-20 05:52:19','2025-06-18 05:52:19',NULL),(82,'yuki_suzuki_81','Yuki Suzuki','yuki.suzuki.81@hotmail.com','+32 10 22 78 72','Markt 67','276/6','Bruges','8000','BE','2024-08-14 05:52:19','$2y$12$G16KfeYG5z/bPYRxpGZtAuivR7ClgrkQpG8LnQRfZQOgVGekcTK1q','User',1,NULL,'2025-03-16 06:52:20','2025-06-21 05:52:20',NULL),(83,'takeshi_yamamoto_82','Takeshi Yamamoto','takeshi.yamamoto.82@hotmail.com','+32 10 29 41 93','Steenstraat 89','860/55','Bruges','8000','BE','2024-09-20 05:52:20','$2y$12$w0yn8wkn50g.19YuW6uNA.X6GvDptv0/E7yvJtmI2S/AB9RhNOKLe','User',1,NULL,'2025-06-22 05:52:20','2025-06-14 05:52:20',NULL),(84,'akiko_watanabe_83','Akiko Watanabe','akiko.watanabe.83@hotmail.com','+32 3 57 53 56','Wollestraat 23','414/79','Bruges','8000','BE','2025-05-09 05:52:20','$2y$12$9SHOmJn/EQlViWhRA5uqLeauRu8o0Pesnr5XZvRb3unCUEYLzsDBy','User',1,NULL,'2024-10-30 06:52:20','2025-07-05 05:52:20',NULL),(85,'raj_patel_84','Raj Patel','raj.patel.84@outlook.com','+32 2 38 95 47','Simon Stevinplein 45','759/99','Bruges','8000','BE','2025-06-03 05:52:20','$2y$12$uH497yna9fTodwAnzKSww.DG5M6h9BS5XH.5DRgYiWR1Sg08GjB2S','User',1,NULL,'2024-06-26 05:52:20','2025-07-07 05:52:20',NULL),(86,'priya_sharma_85','Priya Sharma','priya.sharma.85@free.fr','+32 2 49 45 22','Katelijnestraat 12','558/68','Bruges','8000','BE','2024-11-13 06:52:20','$2y$12$koPD2yh3sWVsC6v0mwpqOumwHS86kb264.eQlUg6YIuPPDHscuTAK','User',1,NULL,'2024-02-04 06:52:20','2025-07-02 05:52:20',NULL),(87,'arjun_kumar_86','Arjun Kumar','arjun.kumar.86@free.fr','+32 11 61 67 45','Place Saint-Lambert 34','252/9','Li├¿ge','4000','BE','2025-05-02 05:52:20','$2y$12$THaErJUnUWaPdpu2ZHUnk.tqbtwJENg5cjDMXlvWoGC0axfd4O9zi','User',1,NULL,'2024-12-01 06:52:21','2025-07-10 05:52:21',NULL),(88,'anita_singh_87','Anita Singh','anita.singh.87@free.fr','+32 2 54 74 72','Rue de la Regence 78','583/45','Li├¿ge','4000','BE','2025-01-18 06:52:21','$2y$12$KmYbxONKP6iZ7S/WS4sulODEYpbq.YrWW6bFxt6lk4AIhYxk3vkxO','User',1,NULL,'2024-08-14 05:52:21','2025-07-13 05:52:21',NULL),(89,'min_kim_88','Min Kim','min.kim.88@yahoo.fr','+32 4 49 41 89','Boulevard de la Sauveniere 123','322/23','Li├¿ge','4000','BE','2025-06-18 05:52:21','$2y$12$Aig3.BfV3Y7sLYHjHBKokOKP7LBVTfJWYApjmb/ue1fVsP7fsnebG','User',1,NULL,'2024-01-04 06:52:21','2025-07-04 05:52:21',NULL),(90,'soo_park_89','Soo Park','soo.park.89@free.fr','+32 11 25 29 51','Rue Pont d\'Avroy 56','520/74','Li├¿ge','4000','BE','2025-03-09 06:52:21','$2y$12$XeeANd0BfgjypuVJ43z8iuI.RTA1OvtUpNBJf74ZKCfbvQ7JSUpTS','User',1,NULL,'2025-06-18 05:52:21','2025-07-10 05:52:21',NULL),(91,'jun_lee_90','Jun Lee','jun.lee.90@free.fr','+32 10 97 55 52','Place du Marche 89','215/37','Li├¿ge','4000','BE','2025-06-16 05:52:21','$2y$12$ejsW.9qoJvg4YF07sbh2eOONzj6xgzB3Lq6zlEMq48f/v3u2IXqr2','User',1,NULL,'2024-03-15 06:52:21','2025-06-29 05:52:21',NULL),(92,'hye_choi_91','Hye Choi','hye.choi.91@laposte.net','+33 4 33 31 72 32','Rue de Rivoli 123','272/90','Paris','75001','FR','2024-07-28 05:52:21','$2y$12$hsr0ANkcdcYaZzvJdLQPtufaMMW7EE9brV8WMCVYwNZfoa0skZeJe','User',1,NULL,'2025-02-14 06:52:22','2025-06-26 05:52:22',NULL),(93,'thanh_nguyen_92','Thanh Nguyen','thanh.nguyen.92@hotmail.com','+33 5 59 25 25 60','Avenue des Champs-Elysees 456','437/37','Paris','75008','FR','2025-02-17 06:52:22','$2y$12$AKfFnu0qxpZqT8CTPs/2He2WhAoLJ2QXlvQmeasmXiumIfdzYZdFe','User',1,NULL,'2023-08-04 05:52:22','2025-07-09 05:52:22',NULL),(94,'linh_tran_93','Linh Tran','linh.tran.93@free.fr','+33 4 48 27 16 81','Boulevard Saint-Germain 789','138/78','Paris','75006','FR','2024-07-24 05:52:22','$2y$12$0yij9tvbTplr9qWtL7BKVOeejJM8p3U3iIZu9pi0eCjR9h6VHbCVe','User',1,NULL,'2025-03-24 06:52:22','2025-06-16 05:52:22',NULL),(95,'duc_le_94','Duc Le','duc.le.94@gmail.com','+33 3 56 11 38 77','Rue de la Paix 34','185/91','Paris','75002','FR','2025-04-26 05:52:22','$2y$12$41n8cMOBjuSiivibd2gk5emg.LcuoP57H9r3BHkII7N1tHdusd6ZO','User',1,NULL,'2025-04-24 05:52:22','2025-06-17 05:52:22',NULL),(96,'mai_pham_95','Mai Pham','mai.pham.95@yahoo.fr','+33 7 97 70 23 66','Place Vendome 12','938/43','Paris','75001','FR','2024-11-18 06:52:22','$2y$12$VZypicF3t3m2w0ZVryjdPO49ozknAPPEQ4sDvcPzl9uDaXb7xwxhu','User',1,NULL,'2024-06-10 05:52:22','2025-06-23 05:52:22',NULL),(97,'budi_santoso_96','Budi Santoso','budi.santoso.96@free.fr','+33 6 92 22 47 95','Rue du Faubourg Saint-Honore 67','245/54','Paris','75008','FR','2024-08-10 05:52:22','$2y$12$HMqWiK5WtPpxDsmBhRgXr.XXe7BPss6HZRfIa0ea6OoR6unKpjuIm','User',1,NULL,'2024-12-10 06:52:22','2025-06-29 05:52:22',NULL),(98,'sari_wijaya_97','Sari Wijaya','sari.wijaya.97@orange.fr','+33 3 16 91 70 53','Avenue Montaigne 23','125/67','Paris','75008','FR','2024-11-22 06:52:22','$2y$12$wHPG5e/yL/T7MLj6d2RiXecwnhe.k80yxqQAxU0c1X7SBVetDPJ9C','User',1,NULL,'2023-08-09 05:52:23','2025-06-24 05:52:23',NULL),(99,'andi_susanto_98','Andi Susanto','andi.susanto.98@wanadoo.fr','+33 3 60 63 70 89','Rue Saint-Antoine 89','889/55','Paris','75004','FR','2025-05-15 05:52:23','$2y$12$kciPN4QFOLVgsmHB8EEpEO2DH4ohnYLWAmwnWEVW7s83MHFjUmznW','User',1,NULL,'2024-07-19 05:52:23','2025-07-05 05:52:23',NULL),(100,'dewi_pratama_99','Dewi Pratama','dewi.pratama.99@free.fr','+33 6 83 98 72 58','Boulevard Haussmann 145','924/52','Paris','75009','FR','2025-01-08 06:52:23','$2y$12$feekl.96CanNbOMa2Leh1.PcH2y3Bto2Le2eRW5LKWTIbtFq/rN9W','User',1,NULL,'2024-05-17 05:52:23','2025-06-22 05:52:23',NULL),(101,'rizki_utama_100','Rizki Utama','rizki.utama.100@outlook.com','+33 5 35 64 74 23','Rue de la Roquette 56','8/98','Paris','75011','FR','2024-12-01 06:52:23','$2y$12$oOSZul2ldeYikqbVBi5nSOO/Q3EblxOr5zCaS0g88sOyu4LqJcISG','User',1,NULL,'2023-12-26 06:52:23','2025-06-22 05:52:23',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('D4ppOBU7OoRIznojim1bGN5swAAL6rqdLyAFYB8r',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.102.0 Chrome/134.0.6998.205 Electron/35.6.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibEVnd1ppYUlHZnRzYWZqeGIzcHR3cUptVDQ1Z3NRM2NpbDUzZG9PciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTAzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHJvZHVjdHM/aWQ9Nzc4NWUxYmYtODcxMC00NDc1LTlkNzUtNGYxZjIxZDkyMmY5JnZzY29kZUJyb3dzZXJSZXFJZD0xNzUyNDc3MDA3MzQxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752477007),('oOCyh0EyqRSA3xJz3Nh5AtjhMHKotz25ludKkbZl',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:140.0) Gecko/20100101 Firefox/140.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSmJiNUtyaUtlU0VNSEE3Zk9oYkxtT3dKZE5QaUJRSkt3aVZUNmpYciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1752477070);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_likes`
--

DROP TABLE IF EXISTS `product_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_likes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_likes_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `product_likes_product_id_foreign` (`product_id`),
  CONSTRAINT `product_likes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_likes`
--

LOCK TABLES `product_likes` WRITE;
/*!40000 ALTER TABLE `product_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookies`
--

DROP TABLE IF EXISTS `cookies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `necessary` tinyint(1) NOT NULL DEFAULT 1,
  `analytics` tinyint(1) NOT NULL DEFAULT 0,
  `marketing` tinyint(1) NOT NULL DEFAULT 0,
  `preferences` tinyint(1) NOT NULL DEFAULT 0,
  `social_media` tinyint(1) NOT NULL DEFAULT 0,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `last_updated_at` timestamp NULL DEFAULT NULL,
  `preferences_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences_details`)),
  `consent_version` varchar(255) NOT NULL DEFAULT '1.0',
  `status` enum('pending','accepted','rejected','partial') NOT NULL DEFAULT 'pending',
  `page_url` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `browser_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`browser_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cookies_user_id_status_index` (`user_id`,`status`),
  KEY `cookies_session_id_ip_address_index` (`session_id`,`ip_address`),
  KEY `cookies_accepted_at_index` (`accepted_at`),
  KEY `cookies_status_index` (`status`),
  CONSTRAINT `cookies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookies`
--

LOCK TABLES `cookies` WRITE;
/*!40000 ALTER TABLE `cookies` DISABLE KEYS */;
/*!40000 ALTER TABLE `cookies` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-14 10:13:31
