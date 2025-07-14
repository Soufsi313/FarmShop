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
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `sender_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'system',
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `status` enum('unread','read','archived') NOT NULL DEFAULT 'unread',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `read_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT 0,
  `action_url` varchar(255) DEFAULT NULL,
  `action_label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_user_id_status_index` (`user_id`,`status`),
  KEY `messages_user_id_type_index` (`user_id`,`type`),
  KEY `messages_created_at_index` (`created_at`),
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,NULL,'contact','Test','Test message de plus de 10 caracteres','{\"contact_reason\":\"autre\",\"sender_email\":\"test@test.com\",\"sender_name\":\"Test\",\"sender_phone\":null,\"original_contact_id\":1,\"migrated_from_contacts\":true,\"admin_responded\":true,\"response_message_id\":4,\"responded_at\":\"2025-07-13T12:54:30.078238Z\",\"responded_by\":1}','read','normal','2025-07-13 10:54:30',NULL,0,NULL,NULL,'2025-07-13 10:26:40','2025-07-13 10:54:30',NULL),(2,1,NULL,'contact','Sujet test','Message test','{\"contact_reason\":\"autre\",\"sender_email\":\"s.mef2703@gmail.com\",\"sender_name\":\"Meftah Soufiane\",\"sender_phone\":\"+32489446494\",\"original_contact_id\":2,\"migrated_from_contacts\":true,\"admin_responded\":true,\"response_message_id\":3,\"responded_at\":\"2025-07-13T12:35:24.701691Z\",\"responded_by\":1}','read','normal','2025-07-13 10:35:24',NULL,0,NULL,NULL,'2025-07-13 10:26:40','2025-07-13 10:35:24',NULL),(3,1,1,'admin_response','R├®ponse : Sujet test','R├®ponse ├á votre message :\n\nR├®ponse test\n\n--- Message original ---\nMessage test','{\"original_message_id\":2,\"admin_response\":true,\"response_date\":\"2025-07-13T12:35:24.697069Z\"}','unread','normal','2025-07-13 10:46:11',NULL,1,NULL,NULL,'2025-07-13 10:35:24','2025-07-13 10:46:11',NULL),(4,1,1,'admin_response','R├®ponse : Test','R├®ponse ├á votre message :\n\nje vous r├®ponds\n\n--- Message original ---\nTest message de plus de 10 caracteres','{\"original_message_id\":1,\"admin_response\":true,\"response_date\":\"2025-07-13T12:54:30.073011Z\"}','unread','normal',NULL,NULL,1,NULL,NULL,'2025-07-13 10:54:30','2025-07-13 10:54:30',NULL),(5,1,NULL,'contact','Question sur vos produits bio','Bonjour,\n\nJe suis int├®ress├® par vos produits biologiques, notamment les graines potag├¿res. Pourriez-vous m\'envoyer votre catalogue complet ?\n\nCordialement.','{\"sender_name\":\"Marc Lefevre\",\"sender_email\":\"marc.lefevre@gmail.com\",\"sender_phone\":\"06.12.34.56.78\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','urgent','2025-06-08 01:39:41',NULL,0,NULL,NULL,'2025-06-06 05:39:41','2025-06-06 05:39:41',NULL),(6,1,NULL,'contact','Demande de devis pour mat├®riel agricole','Bonjour,\n\nJe souhaiterais obtenir un devis pour l\'achat de mat├®riel agricole : b├¬ches, serfouettes et arrosoirs. Je suis un particulier avec un potager de 200m┬▓.\n\nMerci d\'avance.','{\"sender_name\":\"Julie Moreau\",\"sender_email\":\"julie.moreau@outlook.fr\",\"sender_phone\":\"07.23.45.67.89\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','normal','2025-06-23 03:09:41',NULL,1,NULL,NULL,'2025-06-22 17:09:41','2025-06-22 17:09:41',NULL),(7,1,NULL,'contact','Probl├¿me avec ma commande','Bonsoir,\n\nJ\'ai pass├® commande il y a une semaine (r├®f: #12345) mais je n\'ai toujours pas re├ºu mes produits. Pouvez-vous me donner des nouvelles ?\n\nBien ├á vous.','{\"sender_name\":\"David Martinez\",\"sender_email\":\"david.martinez@yahoo.fr\",\"sender_phone\":null,\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','urgent','2025-06-10 13:36:41',NULL,0,NULL,NULL,'2025-06-09 17:36:41','2025-06-09 17:36:41',NULL),(8,1,NULL,'contact','Disponibilit├® des semences de tomates','Bonjour,\n\nAvez-vous encore en stock des graines de tomates anciennes ? Je cherche particuli├¿rement la vari├®t├® \'C┼ôur de B┼ôuf\'.\n\nMerci pour votre r├®ponse.','{\"sender_name\":\"Sophie Nguyen\",\"sender_email\":\"sophie.nguyen@hotmail.com\",\"sender_phone\":\"06.34.56.78.90\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','high','2025-05-17 05:41:41',NULL,0,NULL,NULL,'2025-05-16 03:41:41','2025-05-16 03:41:41',NULL),(9,1,NULL,'contact','Renseignements sur la livraison','Bonjour,\n\nQuels sont vos d├®lais de livraison pour la r├®gion Provence-Alpes-C├┤te d\'Azur ? Livrez-vous jusqu\'en zone rurale ?\n\nCordialement.','{\"sender_name\":\"Paul Dubois\",\"sender_email\":\"paul.dubois@free.fr\",\"sender_phone\":\"07.45.67.89.01\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-25 08:51:41','2025-06-25 08:51:41',NULL),(10,1,NULL,'contact','Prix des engrais naturels','Bonjour,\n\nPourriez-vous m\'indiquer les prix de vos engrais naturels, notamment le compost et le fumier de cheval ?\n\nMerci beaucoup.','{\"sender_name\":\"Claire Bernard\",\"sender_email\":\"claire.bernard@orange.fr\",\"sender_phone\":null,\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-07-09 11:33:41',NULL,0,NULL,NULL,'2025-07-09 07:33:41','2025-07-09 07:33:41',NULL),(11,1,NULL,'contact','Formation en agriculture biologique','Bonjour,\n\nProposez-vous des formations ou des ateliers sur l\'agriculture biologique ? Je d├®bute dans ce domaine.\n\nBonne journ├®e.','{\"sender_name\":\"Antoine Rousseau\",\"sender_email\":\"antoine.rousseau@sfr.fr\",\"sender_phone\":\"06.56.78.90.12\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','low','2025-05-22 06:39:41',NULL,0,NULL,NULL,'2025-05-21 10:39:41','2025-05-21 10:39:41',NULL),(12,1,NULL,'contact','Partenariat commercial','Bonjour,\n\nJe repr├®sente une coop├®rative agricole et nous aimerions ├®tudier un partenariat commercial. Pouvons-nous organiser un rendez-vous ?\n\nCordialement.','{\"sender_name\":\"Lisa Chen\",\"sender_email\":\"lisa.chen@protonmail.com\",\"sender_phone\":\"07.67.89.01.23\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','low','2025-07-05 13:41:41',NULL,0,NULL,NULL,'2025-07-05 07:41:41','2025-07-05 07:41:41',NULL),(13,1,NULL,'contact','R├®clamation produit d├®fectueux','Bonjour,\n\nJ\'ai re├ºu ma commande mais l\'un des outils (b├¬che) pr├®sente un d├®faut de fabrication. Comment proc├®der pour un ├®change ?\n\nMerci.','{\"sender_name\":\"Kevin Lambert\",\"sender_email\":\"kevin.lambert@wanadoo.fr\",\"sender_phone\":null,\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-08 00:23:41','2025-06-08 00:23:41',NULL),(14,1,NULL,'contact','Conseil pour potager urbain','Bonjour,\n\nJe vis en appartement et souhaite cr├®er un potager sur mon balcon. Quels conseils pourriez-vous me donner ?\n\nBien ├á vous.','{\"sender_name\":\"Emilie Garnier\",\"sender_email\":\"emilie.garnier@laposte.net\",\"sender_phone\":\"06.78.90.12.34\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','low','2025-06-07 07:58:41',NULL,0,NULL,NULL,'2025-06-06 22:58:41','2025-06-06 22:58:41',NULL),(15,1,NULL,'contact','Catalogue 2025','Bonjour,\n\nVotre nouveau catalogue 2025 est-il disponible ? J\'aimerais consulter vos nouveaut├®s.\n\nCordialement.','{\"sender_name\":\"Marc Lefevre\",\"sender_email\":\"marc.lefevre@gmail.com\",\"sender_phone\":\"06.12.34.56.78\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','normal',NULL,NULL,1,NULL,NULL,'2025-06-07 19:22:41','2025-06-07 19:22:41',NULL),(16,1,NULL,'contact','M├®thodes de paiement accept├®es','Bonjour,\n\nQuels sont les moyens de paiement que vous acceptez ? Prenez-vous les ch├¿ques et virements ?\n\nMerci.','{\"sender_name\":\"Julie Moreau\",\"sender_email\":\"julie.moreau@outlook.fr\",\"sender_phone\":\"07.23.45.67.89\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-06-29 15:00:41',NULL,0,NULL,NULL,'2025-06-28 06:00:41','2025-06-28 06:00:41',NULL),(17,1,NULL,'contact','D├®lais de livraison en zone rurale','Bonjour,\n\nJ\'habite dans un petit village isol├®. Pouvez-vous livrer jusqu\'ici ? Y a-t-il des frais suppl├®mentaires ?\n\nBonne journ├®e.','{\"sender_name\":\"David Martinez\",\"sender_email\":\"david.martinez@yahoo.fr\",\"sender_phone\":null,\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','low',NULL,NULL,0,NULL,NULL,'2025-05-28 04:13:41','2025-05-28 04:13:41',NULL),(18,1,NULL,'contact','Produits pour permaculture','Bonjour,\n\nJe m\'int├®resse ├á la permaculture. Avez-vous des produits sp├®cialement adapt├®s ├á cette pratique ?\n\nCordialement.','{\"sender_name\":\"Sophie Nguyen\",\"sender_email\":\"sophie.nguyen@hotmail.com\",\"sender_phone\":\"06.34.56.78.90\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','normal','2025-06-25 04:26:41',NULL,0,NULL,NULL,'2025-06-24 23:26:41','2025-06-24 23:26:41',NULL),(19,1,NULL,'contact','Assurance qualit├® bio','Bonjour,\n\nComment puis-je ├¬tre s├╗r de la qualit├® biologique de vos produits ? Avez-vous des certifications ?\n\nMerci.','{\"sender_name\":\"Paul Dubois\",\"sender_email\":\"paul.dubois@free.fr\",\"sender_phone\":\"07.45.67.89.01\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','normal','2025-06-15 21:17:41',NULL,0,NULL,NULL,'2025-06-14 07:17:41','2025-06-14 07:17:41',NULL),(20,1,NULL,'contact','Programme de fid├®lit├®','Bonjour,\n\nJe suis int├®ress├® par vos produits biologiques, notamment les graines potag├¿res. Pourriez-vous m\'envoyer votre catalogue complet ?\n\nCordialement.','{\"sender_name\":\"Claire Bernard\",\"sender_email\":\"claire.bernard@orange.fr\",\"sender_phone\":null,\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','high',NULL,NULL,0,NULL,NULL,'2025-06-19 13:45:41','2025-06-19 13:45:41',NULL),(21,1,NULL,'contact','Retour produit non conforme','Bonjour,\n\nJe souhaiterais obtenir un devis pour l\'achat de mat├®riel agricole : b├¬ches, serfouettes et arrosoirs. Je suis un particulier avec un potager de 200m┬▓.\n\nMerci d\'avance.','{\"sender_name\":\"Antoine Rousseau\",\"sender_email\":\"antoine.rousseau@sfr.fr\",\"sender_phone\":\"06.56.78.90.12\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','urgent','2025-05-19 22:02:41',NULL,0,NULL,NULL,'2025-05-19 08:02:41','2025-05-19 08:02:41',NULL),(22,1,NULL,'contact','Conseils plantation automne','Bonsoir,\n\nJ\'ai pass├® commande il y a une semaine (r├®f: #12345) mais je n\'ai toujours pas re├ºu mes produits. Pouvez-vous me donner des nouvelles ?\n\nBien ├á vous.','{\"sender_name\":\"Lisa Chen\",\"sender_email\":\"lisa.chen@protonmail.com\",\"sender_phone\":\"07.67.89.01.23\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-28 05:53:41','2025-06-28 05:53:41',NULL),(23,1,NULL,'contact','Stock graines anciennes','Bonjour,\n\nAvez-vous encore en stock des graines de tomates anciennes ? Je cherche particuli├¿rement la vari├®t├® \'C┼ôur de B┼ôuf\'.\n\nMerci pour votre r├®ponse.','{\"sender_name\":\"Kevin Lambert\",\"sender_email\":\"kevin.lambert@wanadoo.fr\",\"sender_phone\":null,\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','normal','2025-05-14 23:48:41',NULL,1,NULL,NULL,'2025-05-13 19:48:41','2025-05-13 19:48:41',NULL),(24,1,NULL,'contact','Service apr├¿s-vente','Bonjour,\n\nQuels sont vos d├®lais de livraison pour la r├®gion Provence-Alpes-C├┤te d\'Azur ? Livrez-vous jusqu\'en zone rurale ?\n\nCordialement.','{\"sender_name\":\"Emilie Garnier\",\"sender_email\":\"emilie.garnier@laposte.net\",\"sender_phone\":\"06.78.90.12.34\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','high','2025-05-20 05:44:41',NULL,0,NULL,NULL,'2025-05-18 23:44:41','2025-05-18 23:44:41',NULL),(25,1,NULL,'contact','Question sur vos produits bio','Bonjour,\n\nPourriez-vous m\'indiquer les prix de vos engrais naturels, notamment le compost et le fumier de cheval ?\n\nMerci beaucoup.','{\"sender_name\":\"Marc Lefevre\",\"sender_email\":\"marc.lefevre@gmail.com\",\"sender_phone\":\"06.12.34.56.78\",\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-07-05 11:49:41',NULL,0,NULL,NULL,'2025-07-04 07:49:41','2025-07-04 07:49:41',NULL),(26,1,NULL,'contact','Demande de devis pour mat├®riel agricole','Bonjour,\n\nProposez-vous des formations ou des ateliers sur l\'agriculture biologique ? Je d├®bute dans ce domaine.\n\nBonne journ├®e.','{\"sender_name\":\"Julie Moreau\",\"sender_email\":\"julie.moreau@outlook.fr\",\"sender_phone\":\"07.23.45.67.89\",\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','low','2025-05-30 22:39:41',NULL,0,NULL,NULL,'2025-05-29 04:39:41','2025-05-29 04:39:41',NULL),(27,1,NULL,'contact','Probl├¿me avec ma commande','Bonjour,\n\nJe repr├®sente une coop├®rative agricole et nous aimerions ├®tudier un partenariat commercial. Pouvons-nous organiser un rendez-vous ?\n\nCordialement.','{\"sender_name\":\"David Martinez\",\"sender_email\":\"david.martinez@yahoo.fr\",\"sender_phone\":null,\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-24 15:27:41','2025-06-24 15:27:41',NULL),(28,1,NULL,'contact','Disponibilit├® des semences de tomates','Bonjour,\n\nJ\'ai re├ºu ma commande mais l\'un des outils (b├¬che) pr├®sente un d├®faut de fabrication. Comment proc├®der pour un ├®change ?\n\nMerci.','{\"sender_name\":\"Sophie Nguyen\",\"sender_email\":\"sophie.nguyen@hotmail.com\",\"sender_phone\":\"06.34.56.78.90\",\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','urgent','2025-06-14 18:10:41',NULL,1,NULL,NULL,'2025-06-12 18:10:41','2025-06-12 18:10:41',NULL),(29,1,NULL,'contact','Renseignements sur la livraison','Bonjour,\n\nJe vis en appartement et souhaite cr├®er un potager sur mon balcon. Quels conseils pourriez-vous me donner ?\n\nBien ├á vous.','{\"sender_name\":\"Paul Dubois\",\"sender_email\":\"paul.dubois@free.fr\",\"sender_phone\":\"07.45.67.89.01\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-06-28 22:59:41',NULL,0,NULL,NULL,'2025-06-27 23:59:41','2025-06-27 23:59:41',NULL),(30,1,NULL,'contact','Prix des engrais naturels','Bonjour,\n\nVotre nouveau catalogue 2025 est-il disponible ? J\'aimerais consulter vos nouveaut├®s.\n\nCordialement.','{\"sender_name\":\"Claire Bernard\",\"sender_email\":\"claire.bernard@orange.fr\",\"sender_phone\":null,\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-06 09:28:41','2025-06-06 09:28:41',NULL),(31,1,NULL,'contact','Formation en agriculture biologique','Bonjour,\n\nQuels sont les moyens de paiement que vous acceptez ? Prenez-vous les ch├¿ques et virements ?\n\nMerci.','{\"sender_name\":\"Antoine Rousseau\",\"sender_email\":\"antoine.rousseau@sfr.fr\",\"sender_phone\":\"06.56.78.90.12\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-06-21 03:19:41',NULL,0,NULL,NULL,'2025-06-21 02:19:41','2025-06-21 02:19:41',NULL),(32,1,NULL,'contact','Partenariat commercial','Bonjour,\n\nJ\'habite dans un petit village isol├®. Pouvez-vous livrer jusqu\'ici ? Y a-t-il des frais suppl├®mentaires ?\n\nBonne journ├®e.','{\"sender_name\":\"Lisa Chen\",\"sender_email\":\"lisa.chen@protonmail.com\",\"sender_phone\":\"07.67.89.01.23\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','high',NULL,NULL,0,NULL,NULL,'2025-05-16 12:07:41','2025-05-16 12:07:41',NULL),(33,1,NULL,'contact','R├®clamation produit d├®fectueux','Bonjour,\n\nJe m\'int├®resse ├á la permaculture. Avez-vous des produits sp├®cialement adapt├®s ├á cette pratique ?\n\nCordialement.','{\"sender_name\":\"Kevin Lambert\",\"sender_email\":\"kevin.lambert@wanadoo.fr\",\"sender_phone\":null,\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-05-23 16:43:41','2025-05-23 16:43:41',NULL),(34,1,NULL,'contact','Conseil pour potager urbain','Bonjour,\n\nComment puis-je ├¬tre s├╗r de la qualit├® biologique de vos produits ? Avez-vous des certifications ?\n\nMerci.','{\"sender_name\":\"Emilie Garnier\",\"sender_email\":\"emilie.garnier@laposte.net\",\"sender_phone\":\"06.78.90.12.34\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-15 23:11:41','2025-06-15 23:11:41',NULL),(35,1,NULL,'contact','Catalogue 2025','Bonjour,\n\nJe suis int├®ress├® par vos produits biologiques, notamment les graines potag├¿res. Pourriez-vous m\'envoyer votre catalogue complet ?\n\nCordialement.','{\"sender_name\":\"Marc Lefevre\",\"sender_email\":\"marc.lefevre@gmail.com\",\"sender_phone\":\"06.12.34.56.78\",\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','low',NULL,NULL,0,NULL,NULL,'2025-06-28 05:05:41','2025-06-28 05:05:41',NULL),(36,1,NULL,'contact','M├®thodes de paiement accept├®es','Bonjour,\n\nJe souhaiterais obtenir un devis pour l\'achat de mat├®riel agricole : b├¬ches, serfouettes et arrosoirs. Je suis un particulier avec un potager de 200m┬▓.\n\nMerci d\'avance.','{\"sender_name\":\"Julie Moreau\",\"sender_email\":\"julie.moreau@outlook.fr\",\"sender_phone\":\"07.23.45.67.89\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','urgent','2025-06-14 20:51:41',NULL,0,NULL,NULL,'2025-06-13 12:51:41','2025-06-13 12:51:41',NULL),(37,1,NULL,'contact','D├®lais de livraison en zone rurale','Bonsoir,\n\nJ\'ai pass├® commande il y a une semaine (r├®f: #12345) mais je n\'ai toujours pas re├ºu mes produits. Pouvez-vous me donner des nouvelles ?\n\nBien ├á vous.','{\"sender_name\":\"David Martinez\",\"sender_email\":\"david.martinez@yahoo.fr\",\"sender_phone\":null,\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','urgent','2025-05-18 05:11:41',NULL,0,NULL,NULL,'2025-05-16 06:11:41','2025-05-16 06:11:41',NULL),(38,1,NULL,'contact','Produits pour permaculture','Bonjour,\n\nAvez-vous encore en stock des graines de tomates anciennes ? Je cherche particuli├¿rement la vari├®t├® \'C┼ôur de B┼ôuf\'.\n\nMerci pour votre r├®ponse.','{\"sender_name\":\"Sophie Nguyen\",\"sender_email\":\"sophie.nguyen@hotmail.com\",\"sender_phone\":\"06.34.56.78.90\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','high','2025-07-07 07:26:41',NULL,0,NULL,NULL,'2025-07-05 11:26:41','2025-07-05 11:26:41',NULL),(39,1,NULL,'contact','Assurance qualit├® bio','Bonjour,\n\nQuels sont vos d├®lais de livraison pour la r├®gion Provence-Alpes-C├┤te d\'Azur ? Livrez-vous jusqu\'en zone rurale ?\n\nCordialement.','{\"sender_name\":\"Paul Dubois\",\"sender_email\":\"paul.dubois@free.fr\",\"sender_phone\":\"07.45.67.89.01\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','urgent','2025-05-31 14:59:41',NULL,0,NULL,NULL,'2025-05-29 16:59:41','2025-05-29 16:59:41',NULL),(40,1,NULL,'contact','Programme de fid├®lit├®','Bonjour,\n\nPourriez-vous m\'indiquer les prix de vos engrais naturels, notamment le compost et le fumier de cheval ?\n\nMerci beaucoup.','{\"sender_name\":\"Claire Bernard\",\"sender_email\":\"claire.bernard@orange.fr\",\"sender_phone\":null,\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','normal','2025-05-27 05:24:41',NULL,0,NULL,NULL,'2025-05-25 16:24:41','2025-05-25 16:24:41',NULL),(41,1,NULL,'contact','Retour produit non conforme','Bonjour,\n\nProposez-vous des formations ou des ateliers sur l\'agriculture biologique ? Je d├®bute dans ce domaine.\n\nBonne journ├®e.','{\"sender_name\":\"Antoine Rousseau\",\"sender_email\":\"antoine.rousseau@sfr.fr\",\"sender_phone\":\"06.56.78.90.12\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','high','2025-07-13 10:59:37',NULL,0,NULL,NULL,'2025-06-19 04:08:41','2025-07-13 10:59:37',NULL),(42,1,NULL,'contact','Conseils plantation automne','Bonjour,\n\nJe repr├®sente une coop├®rative agricole et nous aimerions ├®tudier un partenariat commercial. Pouvons-nous organiser un rendez-vous ?\n\nCordialement.','{\"sender_name\":\"Lisa Chen\",\"sender_email\":\"lisa.chen@protonmail.com\",\"sender_phone\":\"07.67.89.01.23\",\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','low','2025-06-30 14:47:41',NULL,0,NULL,NULL,'2025-06-29 15:47:41','2025-06-29 15:47:41',NULL),(43,1,NULL,'contact','Stock graines anciennes','Bonjour,\n\nJ\'ai re├ºu ma commande mais l\'un des outils (b├¬che) pr├®sente un d├®faut de fabrication. Comment proc├®der pour un ├®change ?\n\nMerci.','{\"sender_name\":\"Kevin Lambert\",\"sender_email\":\"kevin.lambert@wanadoo.fr\",\"sender_phone\":null,\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','high',NULL,NULL,0,NULL,NULL,'2025-07-04 02:40:41','2025-07-04 02:40:41',NULL),(44,1,NULL,'contact','Service apr├¿s-vente','Bonjour,\n\nJe vis en appartement et souhaite cr├®er un potager sur mon balcon. Quels conseils pourriez-vous me donner ?\n\nBien ├á vous.','{\"sender_name\":\"Emilie Garnier\",\"sender_email\":\"emilie.garnier@laposte.net\",\"sender_phone\":\"06.78.90.12.34\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','low','2025-07-13 10:59:14',NULL,0,NULL,NULL,'2025-06-18 23:44:41','2025-07-13 10:59:14',NULL),(45,1,NULL,'contact','Question sur vos produits bio','Bonjour,\n\nVotre nouveau catalogue 2025 est-il disponible ? J\'aimerais consulter vos nouveaut├®s.\n\nCordialement.','{\"sender_name\":\"Marc Lefevre\",\"sender_email\":\"marc.lefevre@gmail.com\",\"sender_phone\":\"06.12.34.56.78\",\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-07 18:52:41','2025-06-07 18:52:41',NULL),(46,1,NULL,'contact','Demande de devis pour mat├®riel agricole','Bonjour,\n\nQuels sont les moyens de paiement que vous acceptez ? Prenez-vous les ch├¿ques et virements ?\n\nMerci.','{\"sender_name\":\"Julie Moreau\",\"sender_email\":\"julie.moreau@outlook.fr\",\"sender_phone\":\"07.23.45.67.89\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-07-03 23:51:41',NULL,0,NULL,NULL,'2025-07-03 10:51:41','2025-07-03 10:51:41',NULL),(47,1,NULL,'contact','Probl├¿me avec ma commande','Bonjour,\n\nJ\'habite dans un petit village isol├®. Pouvez-vous livrer jusqu\'ici ? Y a-t-il des frais suppl├®mentaires ?\n\nBonne journ├®e.','{\"sender_name\":\"David Martinez\",\"sender_email\":\"david.martinez@yahoo.fr\",\"sender_phone\":null,\"contact_reason\":\"commande\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','urgent','2025-06-10 16:32:41',NULL,0,NULL,NULL,'2025-06-10 15:32:41','2025-06-10 15:32:41',NULL),(48,1,NULL,'contact','Disponibilit├® des semences de tomates','Bonjour,\n\nJe m\'int├®resse ├á la permaculture. Avez-vous des produits sp├®cialement adapt├®s ├á cette pratique ?\n\nCordialement.','{\"sender_name\":\"Sophie Nguyen\",\"sender_email\":\"sophie.nguyen@hotmail.com\",\"sender_phone\":\"06.34.56.78.90\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','normal','2025-05-22 00:34:41',NULL,0,NULL,NULL,'2025-05-20 17:34:41','2025-05-20 17:34:41',NULL),(49,1,NULL,'contact','Renseignements sur la livraison','Bonjour,\n\nComment puis-je ├¬tre s├╗r de la qualit├® biologique de vos produits ? Avez-vous des certifications ?\n\nMerci.','{\"sender_name\":\"Paul Dubois\",\"sender_email\":\"paul.dubois@free.fr\",\"sender_phone\":\"07.45.67.89.01\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-10 12:37:41','2025-06-10 12:37:41',NULL),(50,1,NULL,'contact','Prix des engrais naturels','Bonjour,\n\nJe suis int├®ress├® par vos produits biologiques, notamment les graines potag├¿res. Pourriez-vous m\'envoyer votre catalogue complet ?\n\nCordialement.','{\"sender_name\":\"Claire Bernard\",\"sender_email\":\"claire.bernard@orange.fr\",\"sender_phone\":null,\"contact_reason\":\"autre\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','high','2025-05-29 22:48:41',NULL,1,NULL,NULL,'2025-05-28 00:48:41','2025-05-28 00:48:41',NULL),(51,1,NULL,'contact','Formation en agriculture biologique','Bonjour,\n\nJe souhaiterais obtenir un devis pour l\'achat de mat├®riel agricole : b├¬ches, serfouettes et arrosoirs. Je suis un particulier avec un potager de 200m┬▓.\n\nMerci d\'avance.','{\"sender_name\":\"Antoine Rousseau\",\"sender_email\":\"antoine.rousseau@sfr.fr\",\"sender_phone\":\"06.56.78.90.12\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','high','2025-06-08 07:24:41',NULL,0,NULL,NULL,'2025-06-08 03:24:41','2025-06-08 03:24:41',NULL),(52,1,NULL,'contact','Partenariat commercial','Bonsoir,\n\nJ\'ai pass├® commande il y a une semaine (r├®f: #12345) mais je n\'ai toujours pas re├ºu mes produits. Pouvez-vous me donner des nouvelles ?\n\nBien ├á vous.','{\"sender_name\":\"Lisa Chen\",\"sender_email\":\"lisa.chen@protonmail.com\",\"sender_phone\":\"07.67.89.01.23\",\"contact_reason\":\"question\",\"migrated_from_contacts\":false,\"admin_responded\":true}','archived','urgent','2025-05-31 15:13:41',NULL,0,NULL,NULL,'2025-05-30 00:13:41','2025-05-30 00:13:41',NULL),(53,1,NULL,'contact','R├®clamation produit d├®fectueux','Bonjour,\n\nAvez-vous encore en stock des graines de tomates anciennes ? Je cherche particuli├¿rement la vari├®t├® \'C┼ôur de B┼ôuf\'.\n\nMerci pour votre r├®ponse.','{\"sender_name\":\"Kevin Lambert\",\"sender_email\":\"kevin.lambert@wanadoo.fr\",\"sender_phone\":null,\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','read','low','2025-06-06 13:17:41',NULL,0,NULL,NULL,'2025-06-06 03:17:41','2025-06-06 03:17:41',NULL),(54,1,NULL,'contact','Conseil pour potager urbain','Bonjour,\n\nQuels sont vos d├®lais de livraison pour la r├®gion Provence-Alpes-C├┤te d\'Azur ? Livrez-vous jusqu\'en zone rurale ?\n\nCordialement.','{\"sender_name\":\"Emilie Garnier\",\"sender_email\":\"emilie.garnier@laposte.net\",\"sender_phone\":\"06.78.90.12.34\",\"contact_reason\":\"support\",\"migrated_from_contacts\":false,\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-01 17:19:41','2025-06-01 17:19:41',NULL),(55,1,17,'contact','Mise ├á jour de mon profil','Bonjour,\n\nJe souhaiterais mettre ├á jour les informations de mon profil, notamment mon adresse et mon num├®ro de t├®l├®phone.\n\nPouvez-vous m\'indiquer la proc├®dure ?\n\nMerci.','{\"contact_reason\":\"question\",\"admin_responded\":false}','unread','high',NULL,NULL,0,NULL,NULL,'2025-05-31 13:50:41','2025-05-31 13:50:41',NULL),(56,1,90,'contact','Probl├¿me de connexion','Bonjour,\n\nJe n\'arrive plus ├á me connecter ├á mon compte depuis ce matin. Mon mot de passe semble ne plus fonctionner.\n\nPouvez-vous m\'aider ?\n\nCordialement.','{\"contact_reason\":\"question\",\"admin_responded\":false}','read','normal','2025-06-24 06:05:41',NULL,0,NULL,NULL,'2025-06-22 03:05:41','2025-06-22 03:05:41',NULL),(57,1,62,'contact','Changement d\'adresse de livraison','Bonjour,\n\nJe viens de d├®m├®nager et j\'aimerais changer mon adresse de livraison pour mes prochaines commandes.\n\nComment proc├®der ?\n\nMerci beaucoup.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','high','2025-06-06 05:40:41',NULL,1,NULL,NULL,'2025-06-05 12:40:41','2025-06-05 12:40:41',NULL),(58,1,40,'contact','Suivi de ma commande','Bonjour,\n\nJ\'ai pass├® commande il y a 3 jours mais je n\'ai aucune information sur l\'exp├®dition. Pouvez-vous me donner le statut ?\n\nBien ├á vous.','{\"contact_reason\":\"support\",\"admin_responded\":false}','unread','low','2025-07-13 10:59:02',NULL,1,NULL,NULL,'2025-06-17 21:10:41','2025-07-13 10:59:02',NULL),(59,1,91,'contact','Remboursement demand├®','Bonjour,\n\nJe souhaiterais ├¬tre rembours├® pour ma derni├¿re commande qui ne correspond pas ├á mes attentes.\n\nQuelle est la proc├®dure ?\n\nCordialement.','{\"contact_reason\":\"question\",\"admin_responded\":false}','read','normal','2025-06-05 19:56:41',NULL,1,NULL,NULL,'2025-06-04 16:56:41','2025-06-04 16:56:41',NULL),(60,1,83,'contact','Am├®lioration suggestion','Bonjour,\n\nJ\'aimerais sugg├®rer une am├®lioration pour votre site : ajouter un syst├¿me de notation des produits.\n\nQu\'en pensez-vous ?\n\nBonne journ├®e.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','high','2025-06-16 23:17:41',NULL,0,NULL,NULL,'2025-06-16 00:17:41','2025-06-16 00:17:41',NULL),(61,1,29,'contact','Bug sur le site web','Bonjour,\n\nJe rencontre un bug sur votre site : le panier ne se met pas ├á jour quand je modifie les quantit├®s.\n\nPouvez-vous corriger cela ?\n\nMerci.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','high','2025-06-24 20:49:41',NULL,0,NULL,NULL,'2025-06-22 11:49:41','2025-06-22 11:49:41',NULL),(62,1,66,'contact','Modification commande en cours','Bonjour,\n\nJ\'aimerais modifier ma commande en cours (ajouter un article). Est-ce encore possible ?\n\nMerci pour votre r├®ponse rapide.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','high','2025-07-02 18:05:41',NULL,0,NULL,NULL,'2025-06-30 00:05:41','2025-06-30 00:05:41',NULL),(63,1,57,'contact','Programme de parrainage','Bonjour,\n\nComment fonctionne votre programme de parrainage ? Quels sont les avantages pour le parrain et le filleul ?\n\nCordialement.','{\"contact_reason\":\"support\",\"admin_responded\":true}','archived','urgent','2025-06-19 10:56:41',NULL,0,NULL,NULL,'2025-06-19 05:56:41','2025-06-19 05:56:41',NULL),(64,1,36,'contact','Facture introuvable','Bonjour,\n\nJe ne trouve plus ma facture de commande du mois dernier. Pouvez-vous me la renvoyer par email ?\n\nMerci d\'avance.','{\"contact_reason\":\"support\",\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-22 00:32:41','2025-06-22 00:32:41',NULL),(65,1,91,'contact','R├®duction fid├®lit├®','Bonjour,\n\nEn tant que client fid├¿le, puis-je b├®n├®ficier d\'une r├®duction sur ma prochaine commande ?\n\nBien ├á vous.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','urgent','2025-06-07 01:58:41',NULL,1,NULL,NULL,'2025-06-05 08:58:41','2025-06-05 08:58:41',NULL),(66,1,76,'contact','Produit indisponible','Bonjour,\n\nLe produit que je voulais commander est marqu├® comme indisponible. Quand sera-t-il de nouveau en stock ?\n\nMerci.','{\"contact_reason\":\"question\",\"admin_responded\":false}','read','normal','2025-07-05 06:04:41',NULL,0,NULL,NULL,'2025-07-02 13:04:41','2025-07-02 13:04:41',NULL),(67,1,91,'contact','Livraison retard├®e','Bonjour,\n\nMa livraison pr├®vue hier n\'est toujours pas arriv├®e. Y a-t-il un probl├¿me avec le transporteur ?\n\nCordialement.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','normal','2025-07-14 03:07:41',NULL,0,NULL,NULL,'2025-07-11 19:07:41','2025-07-11 19:07:41',NULL),(68,1,12,'contact','Qualit├® produit','Bonjour,\n\nJe suis tr├¿s satisfait de la qualit├® de vos produits ! Continuez ainsi.\n\nJuste un petit retour positif.','{\"contact_reason\":\"question\",\"admin_responded\":false}','unread','low',NULL,NULL,0,NULL,NULL,'2025-07-10 06:57:41','2025-07-10 06:57:41',NULL),(69,1,66,'contact','Nouveau mot de passe','Bonjour,\n\nJ\'ai oubli├® mon mot de passe et la fonction de r├®cup├®ration ne fonctionne pas. Pouvez-vous m\'aider ?\n\nMerci.','{\"contact_reason\":\"commande\",\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-15 20:08:41','2025-06-15 20:08:41',NULL),(70,1,10,'contact','Compte suspendu','Bonjour,\n\nJe souhaiterais mettre ├á jour les informations de mon profil, notamment mon adresse et mon num├®ro de t├®l├®phone.\n\nPouvez-vous m\'indiquer la proc├®dure ?\n\nMerci.','{\"contact_reason\":\"support\",\"admin_responded\":true}','archived','normal','2025-07-04 05:01:41',NULL,0,NULL,NULL,'2025-07-03 19:01:41','2025-07-03 19:01:41',NULL),(71,1,66,'contact','Newsletter d├®sinscription','Bonjour,\n\nJe n\'arrive plus ├á me connecter ├á mon compte depuis ce matin. Mon mot de passe semble ne plus fonctionner.\n\nPouvez-vous m\'aider ?\n\nCordialement.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','normal','2025-07-01 21:55:41',NULL,0,NULL,NULL,'2025-06-29 01:55:41','2025-06-29 01:55:41',NULL),(72,1,44,'contact','Service client','Bonjour,\n\nJe viens de d├®m├®nager et j\'aimerais changer mon adresse de livraison pour mes prochaines commandes.\n\nComment proc├®der ?\n\nMerci beaucoup.','{\"contact_reason\":\"question\",\"admin_responded\":false}','unread','normal',NULL,NULL,1,NULL,NULL,'2025-06-12 19:19:41','2025-06-12 19:19:41',NULL),(73,1,61,'contact','Garantie produit','Bonjour,\n\nJ\'ai pass├® commande il y a 3 jours mais je n\'ai aucune information sur l\'exp├®dition. Pouvez-vous me donner le statut ?\n\nBien ├á vous.','{\"contact_reason\":\"question\",\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-11 22:35:41','2025-06-11 22:35:41',NULL),(74,1,62,'contact','├ëchange article','Bonjour,\n\nJe souhaiterais ├¬tre rembours├® pour ma derni├¿re commande qui ne correspond pas ├á mes attentes.\n\nQuelle est la proc├®dure ?\n\nCordialement.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','high','2025-06-21 14:49:41',NULL,0,NULL,NULL,'2025-06-20 12:49:41','2025-06-20 12:49:41',NULL),(75,1,68,'contact','Mise ├á jour de mon profil','Bonjour,\n\nJ\'aimerais sugg├®rer une am├®lioration pour votre site : ajouter un syst├¿me de notation des produits.\n\nQu\'en pensez-vous ?\n\nBonne journ├®e.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','normal','2025-06-17 05:06:41',NULL,0,NULL,NULL,'2025-06-14 16:06:41','2025-06-14 16:06:41',NULL),(76,1,49,'contact','Probl├¿me de connexion','Bonjour,\n\nJe rencontre un bug sur votre site : le panier ne se met pas ├á jour quand je modifie les quantit├®s.\n\nPouvez-vous corriger cela ?\n\nMerci.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','urgent','2025-06-12 16:42:41',NULL,0,NULL,NULL,'2025-06-12 03:42:41','2025-06-12 03:42:41',NULL),(77,1,98,'contact','Changement d\'adresse de livraison','Bonjour,\n\nJ\'aimerais modifier ma commande en cours (ajouter un article). Est-ce encore possible ?\n\nMerci pour votre r├®ponse rapide.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','low','2025-06-03 12:08:41',NULL,0,NULL,NULL,'2025-06-01 21:08:41','2025-06-01 21:08:41',NULL),(78,1,63,'contact','Suivi de ma commande','Bonjour,\n\nComment fonctionne votre programme de parrainage ? Quels sont les avantages pour le parrain et le filleul ?\n\nCordialement.','{\"contact_reason\":\"question\",\"admin_responded\":false}','read','high','2025-06-04 01:22:41',NULL,0,NULL,NULL,'2025-06-01 23:22:41','2025-06-01 23:22:41',NULL),(79,1,59,'contact','Remboursement demand├®','Bonjour,\n\nJe ne trouve plus ma facture de commande du mois dernier. Pouvez-vous me la renvoyer par email ?\n\nMerci d\'avance.','{\"contact_reason\":\"question\",\"admin_responded\":true}','archived','high','2025-06-23 11:59:41',NULL,0,NULL,NULL,'2025-06-22 09:59:41','2025-06-22 09:59:41',NULL),(80,1,30,'contact','Am├®lioration suggestion','Bonjour,\n\nEn tant que client fid├¿le, puis-je b├®n├®ficier d\'une r├®duction sur ma prochaine commande ?\n\nBien ├á vous.','{\"contact_reason\":\"question\",\"admin_responded\":true}','archived','normal','2025-07-11 06:34:41',NULL,0,NULL,NULL,'2025-07-09 17:34:41','2025-07-09 17:34:41',NULL),(81,1,59,'contact','Bug sur le site web','Bonjour,\n\nLe produit que je voulais commander est marqu├® comme indisponible. Quand sera-t-il de nouveau en stock ?\n\nMerci.','{\"contact_reason\":\"commande\",\"admin_responded\":false}','read','low','2025-07-13 11:52:41',NULL,0,NULL,NULL,'2025-07-11 19:52:41','2025-07-11 19:52:41',NULL),(82,1,67,'contact','Modification commande en cours','Bonjour,\n\nMa livraison pr├®vue hier n\'est toujours pas arriv├®e. Y a-t-il un probl├¿me avec le transporteur ?\n\nCordialement.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','normal','2025-07-05 11:54:41',NULL,0,NULL,NULL,'2025-07-04 22:54:41','2025-07-04 22:54:41',NULL),(83,1,61,'contact','Programme de parrainage','Bonjour,\n\nJe suis tr├¿s satisfait de la qualit├® de vos produits ! Continuez ainsi.\n\nJuste un petit retour positif.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','low','2025-06-29 13:32:41',NULL,0,NULL,NULL,'2025-06-29 12:32:41','2025-06-29 12:32:41',NULL),(84,1,42,'contact','Facture introuvable','Bonjour,\n\nJ\'ai oubli├® mon mot de passe et la fonction de r├®cup├®ration ne fonctionne pas. Pouvez-vous m\'aider ?\n\nMerci.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','low','2025-05-29 05:44:41',NULL,0,NULL,NULL,'2025-05-28 15:44:41','2025-05-28 15:44:41',NULL),(85,1,13,'contact','R├®duction fid├®lit├®','Bonjour,\n\nJe souhaiterais mettre ├á jour les informations de mon profil, notamment mon adresse et mon num├®ro de t├®l├®phone.\n\nPouvez-vous m\'indiquer la proc├®dure ?\n\nMerci.','{\"contact_reason\":\"question\",\"admin_responded\":true}','archived','low','2025-06-25 10:34:41',NULL,0,NULL,NULL,'2025-06-23 02:34:41','2025-06-23 02:34:41',NULL),(86,1,55,'contact','Produit indisponible','Bonjour,\n\nJe n\'arrive plus ├á me connecter ├á mon compte depuis ce matin. Mon mot de passe semble ne plus fonctionner.\n\nPouvez-vous m\'aider ?\n\nCordialement.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','high','2025-06-29 21:13:41',NULL,0,NULL,NULL,'2025-06-27 01:13:41','2025-06-27 01:13:41',NULL),(87,1,47,'contact','Livraison retard├®e','Bonjour,\n\nJe viens de d├®m├®nager et j\'aimerais changer mon adresse de livraison pour mes prochaines commandes.\n\nComment proc├®der ?\n\nMerci beaucoup.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','urgent','2025-07-05 11:16:41',NULL,0,NULL,NULL,'2025-07-04 10:16:41','2025-07-04 10:16:41',NULL),(88,1,70,'contact','Qualit├® produit','Bonjour,\n\nJ\'ai pass├® commande il y a 3 jours mais je n\'ai aucune information sur l\'exp├®dition. Pouvez-vous me donner le statut ?\n\nBien ├á vous.','{\"contact_reason\":\"autre\",\"admin_responded\":false}','read','high','2025-06-29 03:02:41',NULL,0,NULL,NULL,'2025-06-28 17:02:41','2025-06-28 17:02:41',NULL),(89,1,75,'contact','Nouveau mot de passe','Bonjour,\n\nJe souhaiterais ├¬tre rembours├® pour ma derni├¿re commande qui ne correspond pas ├á mes attentes.\n\nQuelle est la proc├®dure ?\n\nCordialement.','{\"contact_reason\":\"question\",\"admin_responded\":false}','read','urgent','2025-05-31 09:17:41',NULL,1,NULL,NULL,'2025-05-31 03:17:41','2025-05-31 03:17:41',NULL),(90,1,16,'contact','Compte suspendu','Bonjour,\n\nJ\'aimerais sugg├®rer une am├®lioration pour votre site : ajouter un syst├¿me de notation des produits.\n\nQu\'en pensez-vous ?\n\nBonne journ├®e.','{\"contact_reason\":\"support\",\"admin_responded\":true}','archived','low','2025-07-01 00:24:41',NULL,0,NULL,NULL,'2025-06-28 05:24:41','2025-06-28 05:24:41',NULL),(91,1,67,'contact','Newsletter d├®sinscription','Bonjour,\n\nJe rencontre un bug sur votre site : le panier ne se met pas ├á jour quand je modifie les quantit├®s.\n\nPouvez-vous corriger cela ?\n\nMerci.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','urgent','2025-06-25 23:30:41',NULL,0,NULL,NULL,'2025-06-24 12:30:41','2025-06-24 12:30:41',NULL),(92,1,100,'contact','Service client','Bonjour,\n\nJ\'aimerais modifier ma commande en cours (ajouter un article). Est-ce encore possible ?\n\nMerci pour votre r├®ponse rapide.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','high','2025-06-04 21:06:41',NULL,0,NULL,NULL,'2025-06-03 23:06:41','2025-06-03 23:06:41',NULL),(93,1,2,'contact','Garantie produit','Bonjour,\n\nComment fonctionne votre programme de parrainage ? Quels sont les avantages pour le parrain et le filleul ?\n\nCordialement.','{\"contact_reason\":\"question\",\"admin_responded\":true}','archived','low','2025-06-12 14:44:41',NULL,0,NULL,NULL,'2025-06-11 17:44:41','2025-06-11 17:44:41',NULL),(94,1,57,'contact','├ëchange article','Bonjour,\n\nJe ne trouve plus ma facture de commande du mois dernier. Pouvez-vous me la renvoyer par email ?\n\nMerci d\'avance.','{\"contact_reason\":\"autre\",\"admin_responded\":false}','read','normal','2025-06-21 10:19:41',NULL,0,NULL,NULL,'2025-06-21 03:19:41','2025-06-21 03:19:41',NULL),(95,1,67,'contact','Mise ├á jour de mon profil','Bonjour,\n\nEn tant que client fid├¿le, puis-je b├®n├®ficier d\'une r├®duction sur ma prochaine commande ?\n\nBien ├á vous.','{\"contact_reason\":\"support\",\"admin_responded\":true}','archived','urgent','2025-06-25 08:13:41',NULL,0,NULL,NULL,'2025-06-22 10:13:41','2025-06-22 10:13:41',NULL),(96,1,6,'contact','Probl├¿me de connexion','Bonjour,\n\nLe produit que je voulais commander est marqu├® comme indisponible. Quand sera-t-il de nouveau en stock ?\n\nMerci.','{\"contact_reason\":\"autre\",\"admin_responded\":true}','archived','low','2025-06-15 12:16:41',NULL,1,NULL,NULL,'2025-06-13 18:16:41','2025-06-13 18:16:41',NULL),(97,1,97,'contact','Changement d\'adresse de livraison','Bonjour,\n\nMa livraison pr├®vue hier n\'est toujours pas arriv├®e. Y a-t-il un probl├¿me avec le transporteur ?\n\nCordialement.','{\"contact_reason\":\"autre\",\"admin_responded\":false}','unread','normal',NULL,NULL,0,NULL,NULL,'2025-06-22 15:00:41','2025-06-22 15:00:41',NULL),(98,1,68,'contact','Suivi de ma commande','Bonjour,\n\nJe suis tr├¿s satisfait de la qualit├® de vos produits ! Continuez ainsi.\n\nJuste un petit retour positif.','{\"contact_reason\":\"commande\",\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-05 23:36:41','2025-06-05 23:36:41',NULL),(99,1,9,'contact','Remboursement demand├®','Bonjour,\n\nJ\'ai oubli├® mon mot de passe et la fonction de r├®cup├®ration ne fonctionne pas. Pouvez-vous m\'aider ?\n\nMerci.','{\"contact_reason\":\"commande\",\"admin_responded\":false}','read','high','2025-06-02 11:46:41',NULL,0,NULL,NULL,'2025-06-01 11:46:41','2025-06-01 11:46:41',NULL),(100,1,37,'contact','Am├®lioration suggestion','Bonjour,\n\nJe souhaiterais mettre ├á jour les informations de mon profil, notamment mon adresse et mon num├®ro de t├®l├®phone.\n\nPouvez-vous m\'indiquer la proc├®dure ?\n\nMerci.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','high','2025-06-05 11:36:41',NULL,0,NULL,NULL,'2025-06-04 00:36:41','2025-06-04 00:36:41',NULL),(101,1,2,'contact','Bug sur le site web','Bonjour,\n\nJe n\'arrive plus ├á me connecter ├á mon compte depuis ce matin. Mon mot de passe semble ne plus fonctionner.\n\nPouvez-vous m\'aider ?\n\nCordialement.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','high','2025-06-28 00:41:41',NULL,0,NULL,NULL,'2025-06-26 10:41:41','2025-06-26 10:41:41',NULL),(102,1,100,'contact','Modification commande en cours','Bonjour,\n\nJe viens de d├®m├®nager et j\'aimerais changer mon adresse de livraison pour mes prochaines commandes.\n\nComment proc├®der ?\n\nMerci beaucoup.','{\"contact_reason\":\"autre\",\"admin_responded\":false}','unread','urgent',NULL,NULL,0,NULL,NULL,'2025-06-13 01:38:41','2025-06-13 01:38:41',NULL),(103,1,77,'contact','Programme de parrainage','Bonjour,\n\nJ\'ai pass├® commande il y a 3 jours mais je n\'ai aucune information sur l\'exp├®dition. Pouvez-vous me donner le statut ?\n\nBien ├á vous.','{\"contact_reason\":\"support\",\"admin_responded\":false}','read','low','2025-06-15 04:19:41',NULL,0,NULL,NULL,'2025-06-14 10:19:41','2025-06-14 10:19:41',NULL),(104,1,100,'contact','Facture introuvable','Bonjour,\n\nJe souhaiterais ├¬tre rembours├® pour ma derni├¿re commande qui ne correspond pas ├á mes attentes.\n\nQuelle est la proc├®dure ?\n\nCordialement.','{\"contact_reason\":\"commande\",\"admin_responded\":true}','archived','low','2025-07-06 00:19:41',NULL,0,NULL,NULL,'2025-07-05 08:19:41','2025-07-05 08:19:41',NULL);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#28a745',
  `icon` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `posts_count` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`),
  KEY `blog_categories_created_by_foreign` (`created_by`),
  KEY `blog_categories_is_active_sort_order_index` (`is_active`,`sort_order`),
  KEY `blog_categories_slug_index` (`slug`),
  CONSTRAINT `blog_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_category_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `status` enum('draft','published','scheduled','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `scheduled_for` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `shares_count` int(11) NOT NULL DEFAULT 0,
  `comments_count` int(11) NOT NULL DEFAULT 0,
  `reading_time` decimal(8,2) DEFAULT NULL,
  `allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_sticky` tinyint(1) NOT NULL DEFAULT 0,
  `author_id` bigint(20) unsigned NOT NULL,
  `last_edited_by` bigint(20) unsigned DEFAULT NULL,
  `last_edited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_author_id_foreign` (`author_id`),
  KEY `blog_posts_last_edited_by_foreign` (`last_edited_by`),
  KEY `blog_posts_status_published_at_index` (`status`,`published_at`),
  KEY `blog_posts_blog_category_id_status_index` (`blog_category_id`,`status`),
  KEY `blog_posts_is_featured_is_sticky_index` (`is_featured`,`is_sticky`),
  KEY `blog_posts_slug_index` (`slug`),
  KEY `blog_posts_published_at_index` (`published_at`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_posts_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_posts_last_edited_by_foreign` FOREIGN KEY (`last_edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `content` text NOT NULL,
  `original_content` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','spam') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `moderated_by` bigint(20) unsigned DEFAULT NULL,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `guest_website` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `replies_count` int(11) NOT NULL DEFAULT 0,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `reports_count` int(11) NOT NULL DEFAULT 0,
  `is_reported` tinyint(1) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_edited` tinyint(1) NOT NULL DEFAULT 0,
  `edited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_moderated_by_foreign` (`moderated_by`),
  KEY `blog_comments_blog_post_id_status_index` (`blog_post_id`,`status`),
  KEY `blog_comments_user_id_status_index` (`user_id`,`status`),
  KEY `blog_comments_parent_id_index` (`parent_id`),
  KEY `blog_comments_status_created_at_index` (`status`,`created_at`),
  KEY `blog_comments_is_reported_index` (`is_reported`),
  CONSTRAINT `blog_comments_blog_post_id_foreign` FOREIGN KEY (`blog_post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_moderated_by_foreign` FOREIGN KEY (`moderated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comment_reports`
--

DROP TABLE IF EXISTS `blog_comment_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_comment_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_comment_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `reason` enum('spam','inappropriate_content','harassment','hate_speech','false_information','copyright_violation','other') NOT NULL,
  `description` text DEFAULT NULL,
  `additional_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_info`)),
  `status` enum('pending','reviewed','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `action_taken` enum('none','warning_sent','comment_hidden','comment_deleted','user_warned','user_suspended','user_banned') DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `reporter_ip` varchar(45) DEFAULT NULL,
  `reporter_user_agent` varchar(255) DEFAULT NULL,
  `evidence` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evidence`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_comment_reports_blog_comment_id_reported_by_unique` (`blog_comment_id`,`reported_by`),
  KEY `blog_comment_reports_reviewed_by_foreign` (`reviewed_by`),
  KEY `blog_comment_reports_blog_comment_id_status_index` (`blog_comment_id`,`status`),
  KEY `blog_comment_reports_reported_by_index` (`reported_by`),
  KEY `blog_comment_reports_status_priority_index` (`status`,`priority`),
  KEY `blog_comment_reports_reviewed_at_index` (`reviewed_at`),
  CONSTRAINT `blog_comment_reports_blog_comment_id_foreign` FOREIGN KEY (`blog_comment_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comment_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comment_reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comment_reports`
--

LOCK TABLES `blog_comment_reports` WRITE;
/*!40000 ALTER TABLE `blog_comment_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comment_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','scheduled','sent','cancelled') NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `recipients_count` int(11) NOT NULL DEFAULT 0,
  `sent_count` int(11) NOT NULL DEFAULT 0,
  `failed_count` int(11) NOT NULL DEFAULT 0,
  `opened_count` int(11) NOT NULL DEFAULT 0,
  `clicked_count` int(11) NOT NULL DEFAULT 0,
  `unsubscribed_count` int(11) NOT NULL DEFAULT 0,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_template` tinyint(1) NOT NULL DEFAULT 0,
  `template_name` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsletters_created_by_foreign` (`created_by`),
  KEY `newsletters_updated_by_foreign` (`updated_by`),
  KEY `newsletters_status_created_at_index` (`status`,`created_at`),
  KEY `newsletters_scheduled_at_status_index` (`scheduled_at`,`status`),
  KEY `newsletters_is_template_template_name_index` (`is_template`,`template_name`),
  KEY `newsletters_sent_at_index` (`sent_at`),
  CONSTRAINT `newsletters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `newsletters_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscriptions`
--

DROP TABLE IF EXISTS `newsletter_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT 1,
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `unsubscribe_reason` varchar(255) DEFAULT NULL,
  `unsubscribe_token` varchar(255) NOT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `source` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscriptions_user_id_unique` (`user_id`),
  UNIQUE KEY `newsletter_subscriptions_unsubscribe_token_unique` (`unsubscribe_token`),
  KEY `newsletter_subscriptions_is_subscribed_created_at_index` (`is_subscribed`,`created_at`),
  KEY `newsletter_subscriptions_unsubscribe_token_index` (`unsubscribe_token`),
  CONSTRAINT `newsletter_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscriptions`
--

LOCK TABLES `newsletter_subscriptions` WRITE;
/*!40000 ALTER TABLE `newsletter_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_sends`
--

DROP TABLE IF EXISTS `newsletter_sends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_sends` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `newsletter_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` enum('pending','sent','failed','bounced') NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `failure_reason` varchar(255) DEFAULT NULL,
  `is_opened` tinyint(1) NOT NULL DEFAULT 0,
  `opened_at` timestamp NULL DEFAULT NULL,
  `open_count` int(11) NOT NULL DEFAULT 0,
  `last_opened_at` timestamp NULL DEFAULT NULL,
  `is_clicked` tinyint(1) NOT NULL DEFAULT 0,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `click_count` int(11) NOT NULL DEFAULT 0,
  `last_clicked_at` timestamp NULL DEFAULT NULL,
  `is_unsubscribed` tinyint(1) NOT NULL DEFAULT 0,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `tracking_token` varchar(255) NOT NULL,
  `unsubscribe_token` varchar(255) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_sends_newsletter_id_user_id_unique` (`newsletter_id`,`user_id`),
  UNIQUE KEY `newsletter_sends_tracking_token_unique` (`tracking_token`),
  KEY `newsletter_sends_newsletter_id_status_index` (`newsletter_id`,`status`),
  KEY `newsletter_sends_user_id_sent_at_index` (`user_id`,`sent_at`),
  KEY `newsletter_sends_tracking_token_index` (`tracking_token`),
  KEY `newsletter_sends_is_opened_index` (`is_opened`),
  KEY `newsletter_sends_is_clicked_index` (`is_clicked`),
  CONSTRAINT `newsletter_sends_newsletter_id_foreign` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `newsletter_sends_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_sends`
--

LOCK TABLES `newsletter_sends` WRITE;
/*!40000 ALTER TABLE `newsletter_sends` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_sends` ENABLE KEYS */;
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
