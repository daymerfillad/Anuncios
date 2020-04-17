# SQL Manager 2005 for MySQL 3.7.5.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : anuncios


SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `anuncios`
    CHARACTER SET 'latin1'
    COLLATE 'latin1_swedish_ci';

USE `anuncios`;

#
# Structure for the `acl_classes` table : 
#

CREATE TABLE `acl_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69DD750638A36066` (`class_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `acl_object_identities` table : 
#

CREATE TABLE `acl_object_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_object_identity_id` int(10) unsigned DEFAULT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `object_identifier` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entries_inheriting` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9407E5494B12AD6EA000B10` (`object_identifier`,`class_id`),
  KEY `IDX_9407E54977FA751A` (`parent_object_identity_id`),
  CONSTRAINT `FK_9407E54977FA751A` FOREIGN KEY (`parent_object_identity_id`) REFERENCES `acl_object_identities` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `acl_security_identities` table : 
#

CREATE TABLE `acl_security_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `username` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8835EE78772E836AF85E0677` (`identifier`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `acl_entries` table : 
#

CREATE TABLE `acl_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) unsigned NOT NULL,
  `object_identity_id` int(10) unsigned DEFAULT NULL,
  `security_identity_id` int(10) unsigned NOT NULL,
  `field_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ace_order` smallint(5) unsigned NOT NULL,
  `mask` int(11) NOT NULL,
  `granting` tinyint(1) NOT NULL,
  `granting_strategy` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `audit_success` tinyint(1) NOT NULL,
  `audit_failure` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4` (`class_id`,`object_identity_id`,`field_name`,`ace_order`),
  KEY `IDX_46C8B806EA000B103D9AB4A6DF9183C9` (`class_id`,`object_identity_id`,`security_identity_id`),
  KEY `IDX_46C8B806EA000B10` (`class_id`),
  KEY `IDX_46C8B8063D9AB4A6` (`object_identity_id`),
  KEY `IDX_46C8B806DF9183C9` (`security_identity_id`),
  CONSTRAINT `FK_46C8B8063D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806DF9183C9` FOREIGN KEY (`security_identity_id`) REFERENCES `acl_security_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806EA000B10` FOREIGN KEY (`class_id`) REFERENCES `acl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `acl_object_identity_ancestors` table : 
#

CREATE TABLE `acl_object_identity_ancestors` (
  `object_identity_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`object_identity_id`,`ancestor_id`),
  KEY `IDX_825DE2993D9AB4A6` (`object_identity_id`),
  KEY `IDX_825DE299C671CEA1` (`ancestor_id`),
  CONSTRAINT `FK_825DE2993D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_825DE299C671CEA1` FOREIGN KEY (`ancestor_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `categoria` table : 
#

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prioridad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4E10122D4E10122D` (`categoria`),
  UNIQUE KEY `UNIQ_4E10122DA3886252` (`prioridad`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `usuario` table : 
#

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `activado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2265B05DF85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Structure for the `anuncio` table : 
#

CREATE TABLE `anuncio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asunto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `precio` double NOT NULL,
  `moneda` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` longtext COLLATE utf8_unicode_ci,
  `fecha` datetime NOT NULL,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`categoria_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `FK_4B3BC0D43397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4B3BC0D4DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for the `imagen` table : 
#

CREATE TABLE `imagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagen` varchar(100) NOT NULL,
  `anuncio_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anuncio_id` (`anuncio_id`),
  CONSTRAINT `FK_8319D2B3963066FD` FOREIGN KEY (`anuncio_id`) REFERENCES `anuncio` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

#
# Data for the `acl_classes` table  (LIMIT 0,500)
#

INSERT INTO `acl_classes` (`id`, `class_type`) VALUES 
  (2,'Anuncios\\FrontendBundle\\Entity\\Anuncio');

COMMIT;

#
# Data for the `acl_object_identities` table  (LIMIT 0,500)
#

INSERT INTO `acl_object_identities` (`id`, `parent_object_identity_id`, `class_id`, `object_identifier`, `entries_inheriting`) VALUES 
  (5,NULL,2,'51',1),
  (6,NULL,2,'52',1),
  (8,NULL,2,'56',1),
  (9,NULL,2,'57',1),
  (10,NULL,2,'58',1),
  (11,NULL,2,'59',1),
  (12,NULL,2,'60',1),
  (15,NULL,2,'63',1),
  (16,NULL,2,'64',1),
  (17,NULL,2,'65',1),
  (18,NULL,2,'66',1),
  (19,NULL,2,'67',1),
  (20,NULL,2,'68',1),
  (21,NULL,2,'69',1),
  (22,NULL,2,'70',1),
  (23,NULL,2,'71',1),
  (24,NULL,2,'72',1),
  (25,NULL,2,'73',1),
  (26,NULL,2,'74',1),
  (27,NULL,2,'75',1),
  (28,NULL,2,'76',1),
  (30,NULL,2,'78',1),
  (31,NULL,2,'79',1),
  (32,NULL,2,'80',1),
  (33,NULL,2,'81',1),
  (34,NULL,2,'82',1),
  (35,NULL,2,'83',1),
  (36,NULL,2,'84',1),
  (37,NULL,2,'85',1),
  (39,NULL,2,'87',1),
  (40,NULL,2,'88',1),
  (41,NULL,2,'89',1),
  (42,NULL,2,'90',1),
  (43,NULL,2,'91',1),
  (44,NULL,2,'92',1),
  (45,NULL,2,'93',1);

COMMIT;

#
# Data for the `acl_security_identities` table  (LIMIT 0,500)
#

INSERT INTO `acl_security_identities` (`id`, `identifier`, `username`) VALUES 
  (2,'Anuncios\\FrontendBundle\\Entity\\Usuario-admin',1),
  (3,'Anuncios\\FrontendBundle\\Entity\\Usuario-dfillad',1);

COMMIT;

#
# Data for the `acl_entries` table  (LIMIT 0,500)
#

INSERT INTO `acl_entries` (`id`, `class_id`, `object_identity_id`, `security_identity_id`, `field_name`, `ace_order`, `mask`, `granting`, `granting_strategy`, `audit_success`, `audit_failure`) VALUES 
  (5,2,5,2,NULL,0,32,1,'all',0,0),
  (6,2,6,2,NULL,0,32,1,'all',0,0),
  (8,2,8,2,NULL,0,32,1,'all',0,0),
  (9,2,9,2,NULL,0,32,1,'all',0,0),
  (10,2,10,2,NULL,0,32,1,'all',0,0),
  (11,2,11,2,NULL,0,32,1,'all',0,0),
  (12,2,12,2,NULL,0,32,1,'all',0,0),
  (15,2,15,2,NULL,0,32,1,'all',0,0),
  (16,2,16,2,NULL,0,32,1,'all',0,0),
  (17,2,17,2,NULL,0,32,1,'all',0,0),
  (18,2,18,2,NULL,0,32,1,'all',0,0),
  (19,2,19,2,NULL,0,32,1,'all',0,0),
  (20,2,20,2,NULL,0,32,1,'all',0,0),
  (21,2,21,2,NULL,0,32,1,'all',0,0),
  (22,2,22,2,NULL,0,32,1,'all',0,0),
  (23,2,23,2,NULL,0,32,1,'all',0,0),
  (24,2,24,2,NULL,0,32,1,'all',0,0),
  (25,2,25,2,NULL,0,32,1,'all',0,0),
  (26,2,26,2,NULL,0,32,1,'all',0,0),
  (27,2,27,2,NULL,0,32,1,'all',0,0),
  (28,2,28,2,NULL,0,32,1,'all',0,0),
  (30,2,30,2,NULL,0,32,1,'all',0,0),
  (31,2,31,2,NULL,0,32,1,'all',0,0),
  (32,2,32,2,NULL,0,32,1,'all',0,0),
  (33,2,33,2,NULL,0,32,1,'all',0,0),
  (34,2,34,2,NULL,0,32,1,'all',0,0),
  (35,2,35,2,NULL,0,32,1,'all',0,0),
  (36,2,36,2,NULL,0,32,1,'all',0,0),
  (37,2,37,2,NULL,0,32,1,'all',0,0),
  (39,2,39,2,NULL,0,32,1,'all',0,0),
  (40,2,40,2,NULL,0,32,1,'all',0,0),
  (41,2,41,2,NULL,0,32,1,'all',0,0),
  (42,2,42,2,NULL,0,32,1,'all',0,0),
  (43,2,43,2,NULL,0,32,1,'all',0,0),
  (44,2,44,2,NULL,0,32,1,'all',0,0),
  (45,2,45,3,NULL,0,32,1,'all',0,0);

COMMIT;

#
# Data for the `acl_object_identity_ancestors` table  (LIMIT 0,500)
#

INSERT INTO `acl_object_identity_ancestors` (`object_identity_id`, `ancestor_id`) VALUES 
  (5,5),
  (6,6),
  (8,8),
  (9,9),
  (10,10),
  (11,11),
  (12,12),
  (15,15),
  (16,16),
  (17,17),
  (18,18),
  (19,19),
  (20,20),
  (21,21),
  (22,22),
  (23,23),
  (24,24),
  (25,25),
  (26,26),
  (27,27),
  (28,28),
  (30,30),
  (31,31),
  (32,32),
  (33,33),
  (34,34),
  (35,35),
  (36,36),
  (37,37),
  (39,39),
  (40,40),
  (41,41),
  (42,42),
  (43,43),
  (44,44),
  (45,45);

COMMIT;

#
# Data for the `categoria` table  (LIMIT 0,500)
#

INSERT INTO `categoria` (`id`, `categoria`, `slug`, `prioridad`) VALUES 
  (1,'Laptop','laptop',1),
  (2,'Celulares','celulares',6),
  (3,'Accesorios PC','accesorios-pc',3),
  (4,'Ropa','ropa',80),
  (5,'Otros','otros',100),
  (6,'PC Escritorio','pc-escritorio',2);

COMMIT;

#
# Data for the `usuario` table  (LIMIT 0,500)
#

INSERT INTO `usuario` (`id`, `username`, `password`, `salt`, `role`, `activado`) VALUES 
  (1,'admin','ulWk5OAVW/RjAPoFbvPtw9n0XQkJHkA1Gs45k70vTtb9kX+rrVYq9N6oiOpXeQJUhphZuiGeoEYDMeOJAmhRpg==','8061be455adf1b3f22d58e7f19ac5716','ROLE_ADMIN',1),
  (3,'dfillad','Y76oXZWQk9a9h8Byq/NNMwV97O4gJO3dlx/b4hO3rZQ8pt6QlPoEY3ot0L0TY2+vRilnVAGp6YhC/ScXB9DzJg==','87a85263661dff58a098ec261da1c044','ROLE_USER',1);

COMMIT;

#
# Data for the `anuncio` table  (LIMIT 0,500)
#

INSERT INTO `anuncio` (`id`, `asunto`, `precio`, `moneda`, `descripcion`, `fecha`, `nombre`, `telefono`, `categoria_id`, `usuario_id`, `slug`) VALUES 
  (51,'Actualización de navegadores web modernos',1,'CUC','Se actualizan los navegadores web, Firefox, Chrome y Safari. Están disponibles las ultimas actualizaciones de cada navegador, cada una cuesta un CUC y las tres solo 2 CUC. También están disponibles otros software como TuneUp, Microsoft Office, etc','2013-08-15 14:40:06','Daymer','36-36-47',5,1,'actualizacion-de-navegadores-web-modernos-520ce8467e382'),
  (52,'Se vende una laptop new i3 + 500 HDD + 2 GB Ram + 15 pulgadas + 3ra G',500,'CUC',NULL,'2013-08-15 15:21:48','Gloria','36-31-49',1,1,'se-vende-una-laptop-new-i3-500-hdd-2-gb-ram-15-pulgadas-3ra-g-520cf20c0383b'),
  (56,'Nintendo Wii',180,'CUC',NULL,'2013-08-27 18:41:53','Daymer','36-36-47',5,1,'nintendo-wii-521cf2f1e469c'),
  (57,'RAM',20,'CUC','RAMDDR1 y procesador PENTIUM a 3.0','2013-08-27 18:51:33','RAM_DDR1','361222',3,1,'ram-521cf535bb7f7'),
  (58,'gafas',7,'CUC','se venden gafas unas trasparentes y otras original rojas con la bander de canada en los cristales esta duraaaaaaaaaa..........','2013-08-28 12:12:57','rodny','353657',5,1,'gafas-521de94958d5a'),
  (59,'Compro RAM DDR1 de 1GB de laptop',0,'CUC','Quien este vendiendo una RAM DDR1 de laptop de 1GB que me contacte....','2013-08-28 23:52:46','Daymer','36-36-47',1,1,'compro-ram-ddr1-de-1gb-de-laptop-521e8d4ed9feb'),
  (60,'Se reparan espejuelos de todo tipo',20,'MN','Se reparan todo tipo de espejuelos, gafas, etc. En la Ave 15 entre 10 y 12 # 1007','2013-08-29 15:52:20','Luis Enrique','-',5,1,'se-reparan-espejuelos-de-todo-tipo-521f6e34f102f'),
  (63,'DISCO DURO SATA 500 GB',70,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:25:58','CYBORG','-',3,1,'disco-duro-sata-500-gb-521fbc669f2f1'),
  (64,'TARJETA DE CAPTURA DE TV',20,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:28:16','CYBORG','-',3,1,'tarjeta-de-captura-de-tv-521fbcf057d12'),
  (65,'QUEMADOR LG SATA',30,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:28:54','CYBORG','-',3,1,'quemador-lg-sata-521fbd161604f'),
  (66,'CHASIS ATX PINTADO DE NEGRO',20,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:29:51','CYBORG','-',3,1,'chasis-atx-pintado-de-negro-521fbd4fdf17a'),
  (67,'KIT DE MOTHERBOARD (INTEL D101+PENTIUM D  A 3.0+1GB DE RAM DDR-1)',80,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:31:06','CYBORG','-',3,1,'kit-de-motherboard-intel-d101-pentium-d-a-30-1gb-de-ram-ddr-1-521fbd9a2ab3f'),
  (68,'DVD MARCA DJSOUND, PUERTO USB, (DE USO SIN MANDO)',35,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:32:05','CYBORG','-',5,1,'dvd-marca-djsound-puerto-usb-de-uso-sin-mando-521fbdd58fc31'),
  (69,'FUENTE MIOS MODEL KY-480ATX DE 475W (TIENE SATA)',35,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:33:28','CYBORG','-',3,1,'fuente-mios-model-ky-480atx-de-475w-tiene-sata-521fbe287c800'),
  (70,'MEMORIAS RAM DDR1 DE 512MB',20,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:34:09','CYBORG','-',3,1,'memorias-ram-ddr1-de-512mb-521fbe51aba90'),
  (71,'TARJETA DE VIDEO AMD RADEON HD 6800 SERIES',220,'CUC','Contactar con CYBORG por el chat','2013-08-29 21:35:51','CYBORG','-',3,1,'tarjeta-de-video-amd-radeon-hd-6800-series-521fbeb73c3fb'),
  (72,'C HACEN VIDEOS',60,'CUC','HACEMOS VIDEOS DE TODOS LOS TIPOS, YA SEAN DE CUMPLEAÑOS, 15, BODAS Y VIDEOS CLIPS','2013-08-29 22:37:41','D.E. COMPANY','52789600',5,1,'c-hacen-videos-521fcd351d971'),
  (73,'Alfombra de Baile para PlayStation 2 con 3 Juegos de Baile',30,'CUC','Contactarme en el Chat por The Hunter','2013-08-29 23:17:13','The Hunter','.',5,1,'alfombra-de-baile-para-playstation-2-con-3-juegos-de-baile-521fd679ab706'),
  (74,'TV 29\"',300,'CUC','Contactarme en el Chat por The Hunter','2013-08-29 23:31:54','The Hunter','.',5,1,'tv-29-521fd9ea5a23d'),
  (75,'Mandos Originales Nuevos de PlayStation 2',30,'CUC','Contactarme en el Chat por The Hunter','2013-08-29 23:52:11','The Hunter','.',5,1,'mandos-originales-nuevos-de-playstation-2-521fdeab68139'),
  (76,'MEMORY CARD de PlayStation 2',20,'CUC','Contactarme en el chat por The Hunter','2013-08-30 13:47:32','The Hunter','.',5,1,'memory-card-de-playstation-2-5220a274b0276'),
  (78,'MOVIL HTC',330,'CUC','MOVIL HTC VIVI......CON DUARCORE DE MICRO 1 G DE RAM,16 G DE MEMO INTERNA,,Y PARA TARJETA  TAMBIEN.,CON 8 MEG PIX DE CAMARA ,,ES UNOS DE LOS ULTIMOS MODELOS QUE A TIRADO LA HTC................................ES UN AVION,,,JE,JE,JE\r\nContactar con GEO  en el chat','2013-08-30 17:38:30','GEO','-',2,1,'movil-htc-5220d896f3a09'),
  (79,'SAMSUNG GALAXI S2',300,'CUC','Contactar con GEO por el chat','2013-08-30 17:40:52','GEO','-',2,1,'samsung-galaxi-s2-5220d9249adbf'),
  (80,'TV SAMSUNG 32\" LED Nuevo q lee de to x usb',600,'CUC','Contactarme en el chat por The Hunter','2013-08-30 17:51:41','The Hunter','.',5,1,'tv-samsung-32-led-nuevo-q-lee-de-to-x-usb-5220dbad5c9b4'),
  (81,'Quemador LG SATA model:GH22NS70 NUEVO',35,'CUC','Contactarme en el chat por The Hunter','2013-08-30 20:09:14','The Hunter','.',3,1,'quemador-lg-sata-modelgh22ns70-nuevo-5220fbea643bc'),
  (82,'Tarjeta de video ATI EAX1550 256 mb',30,'CUC','Contactarme en el chat por The Hunter','2013-08-30 20:38:31','The Hunter','.',3,1,'tarjeta-de-video-ati-eax1550-256-mb-522102c7372f1'),
  (83,'Quien vende un celular barato',0,'CUC','Estoy buscando quien venda un celular, no tiene que ser ni moderno ni nuevo, pero tiene que funcionar OK!!!\r\nContactar con Chuja por el chat','2013-08-30 22:01:38','Chuja','-',2,1,'quien-vende-un-celular-barato-52211642e816e'),
  (84,'SE VENDE LAPTOP',350,'CUC','LAPTOP MARCA COMPAQ DE 360 DISCO DURO CON PROCESADOR DUAL CORE A 3.2 HZ CON WIFI INTERNA Y 3 PUERTOS USB LA MISMA ES DE 17´´(PULGADAS)SE ESCUCHAN PROPUESTAS','2013-08-30 22:44:53','QUEBECUA','350100',1,1,'se-vende-laptop-5221206585218'),
  (85,'QUEMADOR IDE EN BUEN ESTADO',20,'CUC','Contactar con CALAVERA por el chat','2013-08-31 16:13:03','CALAVERA','-',3,1,'quemador-ide-en-buen-estado-5222160f7392f'),
  (87,'gafas new paket',7,'CUC','SE VENDE UNA GAFA ROJA CON LA BANDERA DE  CANADA Y UNAS TRANSPARENTES  NEGRAS   ORIGINALESSSSSSSSSSS CONTACTE A RODNY DJ EN EL CHAT','2013-08-31 17:36:57','rodny dj y martha','353657',5,1,'gafas-new-paket-522229b96f2a3'),
  (88,'SE COMERCIALIZA MÚSICA DE TODO TIPO',1010110101,'CUC','LA FABRIKA DE LA MÚSICA QUIEN QUIERA ALGO DE MIUSIC QUE AVISE ESTO ES  A LO100%EXLUSIVE WHIT ONE HUNDRED% DJ CRAZY PARTY       ..................CONTACTESE Y TOMARA LO QUE DESEEN SUS OIDOS DE BUENA MÚSICA  ..LA FABRIKA ESTA ABIERTA LAS 24HORAS  CONTACTE POR EL CHAT A 1..DJ RODNY ,,2 Sr,Rompecorazones o THE GIRL WOLF  ABISEN Y TENDRAN LO ULTIMO Y LO EXLUSIVO  \r\n                                                                 OPEN\r\n                                                                             ....... OPEN.........OPEN                                     .OPEN','2013-08-31 17:43:18','DJ RODNY','353657',5,1,'se-comercializa-musica-de-todo-tipo-52222b36e645b'),
  (89,'CONFIGURACIONES --- MOTHERBOARD+CPU+RAM (NUEVO)',215,'CUC','-KIT 2DA GENERACION 1155 ASROCK H61M-DGS + DUAL CORE G645 2.9GHZ 3MB CACHE + 2GB ADATA 1333 \r\nContactar con DUNIESKY por el chat','2013-09-01 12:42:30','DUNIESKY','-',3,1,'configuraciones-motherboard-cpu-ram-nuevo-522336363b4cf'),
  (90,'KIT ASROCK 970 EXTREME3 AMD 970 & SB950 + AMD FX-8320 - 4.0GHZ',350,'CUC','KIT ASROCK 970 EXTREME3 AMD 970 & SB950 + AMD FX-8320 - 4.0GHZ - 8 CORE 16MB CACHE\r\nContactar con DUNIESKY por el Chat','2013-09-01 12:44:50','DUNIESKY','-',3,1,'kit-asrock-970-extreme3-amd-970-sb950-amd-fx-8320-40ghz-522336c20f657'),
  (91,'COMPRO MONITOR',4444444444,'CUC','SE COMPRA UN MONITOR ........... CONTACTEME Y DIGAME Q TIENE     CONTACTEME POR EL CHAT CON DJ RODNY,Sr.Rompecorazones o The Gilr Wolf    ................','2013-09-01 13:35:35','rodny dj','353657',3,1,'compro-monitor-522342a754150'),
  (92,'Compro Quemador LG',25,'CUC','Se compra un quemador LG nuevo quien quiera contactarme que llame a este telef o por el chat \r\ncontacte a Kratos','2013-09-04 00:47:30','Yoelkis','352828',3,1,'compro-quemador-lg-5226832272cf8'),
  (93,'lolo',20,'CUC','s','2013-09-07 10:11:12','Dayme','52-908723',1,3,'lolo-522afbc017166');

COMMIT;

#
# Data for the `imagen` table  (LIMIT 0,500)
#

INSERT INTO `imagen` (`id`, `imagen`, `anuncio_id`) VALUES 
  (85,'anuncio-520ce7c4710c3.png',51),
  (86,'anuncio-520ce7c47ec06.png',51),
  (87,'anuncio-520ce7c484834.png',51),
  (88,'anuncio-521fbeb73c427.jpeg',71),
  (89,'anuncio-521fbeb79adf9.gif',71),
  (90,'anuncio-521fd679ab731.jpeg',73),
  (91,'anuncio-521fd9ea5a266.jpeg',74),
  (92,'anuncio-521fdeab68163.jpeg',75),
  (93,'anuncio-5220a20318763.jpeg',76),
  (94,'anuncio-5220dbad5c9e5.jpeg',80),
  (95,'anuncio-522102c73731b.jpeg',82),
  (99,'anuncio-522229b96f2d6.jpeg',87),
  (100,'anuncio-522229b9af549.jpeg',87),
  (101,'anuncio-522229b9b4418.jpeg',87),
  (102,'anuncio-52222b36e648c.jpeg',88),
  (103,'anuncio-52222b36ec4e3.jpeg',88),
  (104,'anuncio-52222b36f05d3.gif',88),
  (105,'anuncio-52233cf2676a7.png',91),
  (106,'anuncio-52233cf295cc2.jpeg',91);

COMMIT;

