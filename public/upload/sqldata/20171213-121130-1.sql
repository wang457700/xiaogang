-- -----------------------------
-- Think MySQL Data Transfer 
-- 
-- Host     : 
-- Port     : 
-- Database : 
-- 
-- Part : #1
-- Date : 2017-12-13 12:11:30
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;


-- -----------------------------
-- Table structure for `run_ads`
-- -----------------------------
DROP TABLE IF EXISTS `run_ads`;
CREATE TABLE `run_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(8) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `code_lilk` varchar(80) DEFAULT NULL COMMENT '广告链接',
  `is_show` int(5) DEFAULT NULL COMMENT '是否显示',
  `ads_img` varchar(80) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `description` varchar(80) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `run_ads`
-- -----------------------------
INSERT INTO `run_ads` VALUES ('3', '13', '从天光乍破，走到暮雪白头。', '', '1', '20171118\\b215d6221b05f0d7dd56b169bcf097db.jpg', '2017-12-02 11:08:18', '最好的年华要给最好的人，可我已经错过了。');
INSERT INTO `run_ads` VALUES ('4', '13', '临渊羡鱼，不如退而结网。', '', '1', '20171122\\9600b9a611e4511093984be3fd2159bb.jpg', '2017-11-22 10:14:33', '扬汤止沸，不如去火抽薪');
INSERT INTO `run_ads` VALUES ('12', '13', '牵着我的手，闭着眼睛走你也不会迷路', '', '1', '20171122\\1cd6d9717885dc5949120d2941ca985e.jpg', '2017-11-22 10:14:16', '那些刻在椅背后的爱情会不会像水泥地上的花朵开出地老天荒的没有风的森林。');
INSERT INTO `run_ads` VALUES ('11', '13', '那些以前说着永不分离的人，早已经散落在天涯了', '', '1', '20171122\\36439e05f543e8747b6734882b7281ce.jpg', '2017-11-22 00:00:00', '离去，让事情变得简单，人们变得善良，像个孩子一样，我们重新开始。');
INSERT INTO `run_ads` VALUES ('18', '12', 'self 2017', '', '1', '20171201\\d65b92bd6bf6b28b100fd9f6c46e2d72.JPG', '2017-12-01 16:39:47', 'this is man beautiful, i love self');
INSERT INTO `run_ads` VALUES ('19', '12', 'self 2', '', '1', '20171201\\8a9292ed63b2aae535e393e43036de26.JPG', '2017-12-01 16:39:29', '我想我是一个安静的人');
INSERT INTO `run_ads` VALUES ('20', '12', 'self 3', '', '1', '20171201\\e972039db83db15546531768fd3cafaf.JPG', '2017-12-01 16:38:01', '萌你一脸');
