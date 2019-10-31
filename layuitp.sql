
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for system_aduser
-- ----------------------------
DROP TABLE IF EXISTS `system_aduser`;
CREATE TABLE `system_aduser`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'md5(md5())',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色ID',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '0默认, 1禁止登陆',
  `last_login_ip` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '最后登陆IP',
  `last_login_time` datetime(0) NULL DEFAULT NULL COMMENT '最后登陆时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_aduser
-- ----------------------------
INSERT INTO `system_aduser` VALUES (1, '13302481400', 'd4fbb7d8d5603db43ac2094f5955787c', 2, 'ccc', 0, NULL, NULL, '');

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分组',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '变量值',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `sort` int(10) NULL DEFAULT 0,
  `create_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT 0 COMMENT '创建人',
  `update_at` timestamp(0) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新人',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (1, 'managename', 'public', 'string', 'CAdmin管理系统', '后台名称', 0, '2018-07-17 17:27:27', 0, '2018-07-17 22:10:33', NULL);
INSERT INTO `system_config` VALUES (2, 'domain', 'public', 'string', 'http://www.tp.com', '域名', 0, '2019-08-04 20:43:03', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (3, 'logo_url', 'public', 'string', NULL, 'logo', 0, '2019-08-04 20:44:25', 0, NULL, NULL);

-- ----------------------------
-- Table structure for system_group
-- ----------------------------
DROP TABLE IF EXISTS `system_group`;
CREATE TABLE `system_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `roles` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限',
  `titles` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_group
-- ----------------------------
INSERT INTO `system_group` VALUES (1, 'aaa', 'ddd', '[\"system\\\\Configuration\\\\getLeftMenu\",\"system\\\\Configuration\\\\webSiteInit\",\"system\\\\Configuration\\\\setConfig\"]', '[\"\\u5de6\\u4fa7\\u83dc\\u5355\",\"\\u83b7\\u53d6\\u7cfb\\u7edf\\u8bbe\\u7f6e\",\"\\u4fee\\u6539\\u7cfb\\u7edf\\u8bbe\\u7f6e\"]');
INSERT INTO `system_group` VALUES (2, 'ddd', 'ccc', NULL, NULL);

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '绑定权限',
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `parent` int(11) NULL DEFAULT NULL COMMENT '父级id',
  `jump` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '跳转路径',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (4, '系统设置', 'system\\Aduser\\index', 100, 'layui-icon layui-icon-set', NULL, '');
INSERT INTO `system_menu` VALUES (2, '首页', 'system\\Aduser\\index', 1, 'layui-icon layui-icon-home', NULL, 'index');
INSERT INTO `system_menu` VALUES (9, '系统配置', 'system\\Aduser\\index', 100, 'layui-icon aliicon-xitongpeizhi', 4, 'system/website');
INSERT INTO `system_menu` VALUES (5, '菜单设置', 'system\\Aduser\\index', 100, 'iconfont aliicon-caidanpeizhi', 4, 'system/menubar');
INSERT INTO `system_menu` VALUES (10, '系统用户管理', 'system\\Aduser\\index', 100, 'iconfont aliicon-xitongyonghuguanli', 4, 'system/aduser');
INSERT INTO `system_menu` VALUES (11, '用户组设置', 'system\\Aduser\\index', 100, 'iconfont aliicon-yonghuzushezhi', 4, 'system/group');
INSERT INTO `system_menu` VALUES (12, '微信公众号', 'system\\Aduser\\index', 10, 'iconfont aliicon-weixingongzhonghao', NULL, '');
INSERT INTO `system_menu` VALUES (13, '回复管理', 'system\\Aduser\\index', 100, 'layui-icon layui-icon-cellphone', 12, 'wechat/answer');
INSERT INTO `system_menu` VALUES (14, '统计分析', 'system\\Aduser\\index', 1, 'iconfont aliicon-tongji', 12, 'wechat/index');
INSERT INTO `system_menu` VALUES (15, '菜单管理', 'system\\Aduser\\index', 100, 'iconfont aliicon-menu', 12, 'wechat/menu');
INSERT INTO `system_menu` VALUES (16, '粉丝列表', 'system\\Aduser\\index', 100, 'iconfont aliicon-fensiguanli', 12, 'wechat/member');
INSERT INTO `system_menu` VALUES (17, '图文消息', 'system\\Aduser\\index', 100, 'iconfont aliicon-tuwenxiaoxi1', 12, 'wechat/article');
INSERT INTO `system_menu` VALUES (18, '群发消息', 'system\\Aduser\\index', 100, 'iconfont aliicon-qunfaxiaoxi', 12, 'wechat/groupmsg');

-- ----------------------------
-- Table structure for system_note
-- ----------------------------
DROP TABLE IF EXISTS `system_note`;
CREATE TABLE `system_note`  (
  `uid` int(11) NOT NULL,
  `note` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_note
-- ----------------------------
INSERT INTO `system_note` VALUES (1, '哈哈');

-- ----------------------------
-- Table structure for system_roles
-- ----------------------------
DROP TABLE IF EXISTS `system_roles`;
CREATE TABLE `system_roles`  (
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '方法',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '控制器'
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_roles
-- ----------------------------
INSERT INTO `system_roles` VALUES ('Init', 'Init', NULL);
INSERT INTO `system_roles` VALUES ('system\\Aduser', '系统用户', NULL);
INSERT INTO `system_roles` VALUES ('system\\Aduser\\index', '系统用户', 'system\\Aduser');
INSERT INTO `system_roles` VALUES ('system\\Aduser\\getGroupList', '获取角色列表', 'system\\Aduser');
INSERT INTO `system_roles` VALUES ('system\\Aduser\\edit', '修改用户', 'system\\Aduser');
INSERT INTO `system_roles` VALUES ('system\\Aduser\\del', '删除用户', 'system\\Aduser');
INSERT INTO `system_roles` VALUES ('system\\Aduser\\add', '添加用户', 'system\\Aduser');
INSERT INTO `system_roles` VALUES ('system\\Configuration', '系统配置', NULL);
INSERT INTO `system_roles` VALUES ('system\\Configuration\\index', '系统配置', 'system\\Configuration');
INSERT INTO `system_roles` VALUES ('system\\Configuration\\setConfig', '修改系统设置', 'system\\Configuration');
INSERT INTO `system_roles` VALUES ('system\\Configuration\\upLoadLogo', '上传Logo', 'system\\Configuration');
INSERT INTO `system_roles` VALUES ('system\\Group', '系统用户组设置', NULL);
INSERT INTO `system_roles` VALUES ('system\\Group\\index', '系统用户组', 'system\\Group');
INSERT INTO `system_roles` VALUES ('system\\Group\\getRoles', '获取权限表', 'system\\Group');
INSERT INTO `system_roles` VALUES ('system\\Group\\editGroup', '修改用户组', 'system\\Group');
INSERT INTO `system_roles` VALUES ('wechat\\Article', '文章管理', NULL);
INSERT INTO `system_roles` VALUES ('wechat\\Article\\index', '文章管理', 'wechat\\Article');
INSERT INTO `system_roles` VALUES ('wechat\\Member', '粉丝管理', NULL);
INSERT INTO `system_roles` VALUES ('wechat\\Member\\index', '粉丝管理', 'wechat\\Member');

-- ----------------------------
-- Table structure for wechat_article
-- ----------------------------
DROP TABLE IF EXISTS `wechat_article`;
CREATE TABLE `wechat_article`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `thumb_media_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `digest` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '摘要',
  `content_source_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '原文地址',
  `need_open_comment` tinyint(1) NULL DEFAULT NULL COMMENT '是否打开评论',
  `release` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否发布 0未发布 1已发布',
  `creat_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `update_at` datetime(0) NULL DEFAULT NULL COMMENT '最后修改时间',
  `creat_by` int(11) NULL DEFAULT NULL COMMENT '添加人',
  `sort` int(255) NULL DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wechat_article
-- ----------------------------
INSERT INTO `wechat_article` VALUES (19, '测试111', NULL, '2.1.2招商银行U盾签名步骤第一步、确认电脑安装PDF阅读器，建议版本为：版本1：Acrobat Reader DC阅读器；版本2：Adobe Reader XI 11.0.7以上的阅读器；使用该版本需要进行更新补丁包，官方下载地址为：http://www.adobe.com/support/downloads/detail.jsp?ftpID=5784 ，下载后单击安装完成更新。第二步、正确的签名步骤：①电脑USB接口上插好数字证书（ukey）②下载安装驱动，成功检测数字证书（参考:证书驱动下载安装程序）③鼠标先双击【设置（签名前双击设置）】，再打开PDF文档（请不要打开商事登记辅助程序）&amp;nbsp; ④找到对应需要签名的位置，插入需签名的U盾（23或33型号），然后点击签名处，操作步骤如下：', 'asdfasdd', 'asdfasdd', 1, 0, '2019-08-16 03:31:02', '2019-08-16 03:36:25', NULL, NULL);

-- ----------------------------
-- Table structure for wechat_member
-- ----------------------------
DROP TABLE IF EXISTS `wechat_member`;
CREATE TABLE `wechat_member`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headimgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `subscribe` tinyint(1) NULL DEFAULT NULL COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息',
  `sex` tinyint(1) NULL DEFAULT NULL COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户所在城市',
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户所在国家\r\n用户所在国家\r\n国家',
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '省',
  `subscribe_time` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `unionid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'unionid',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '公众号运营者对粉丝的备注',
  `subscribe_scene` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '返回用户关注的渠道来源，ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENEPROFILE LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_OTHERS 其他',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wechat_uploads
-- ----------------------------
DROP TABLE IF EXISTS `wechat_uploads`;
CREATE TABLE `wechat_uploads`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地址',
  `update_by` int(11) NULL DEFAULT NULL COMMENT '上传人ID',
  `update_action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上传控制器',
  `update_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wechat_uploads
-- ----------------------------
INSERT INTO `wechat_uploads` VALUES (7, 'http://www.tp.com/uploads/article/image/20190815\\aadbb82680a19238fcd4f2430c5cc186.png', 1, 'wechat.article', '2019-08-15 19:02:39', 'image');
INSERT INTO `wechat_uploads` VALUES (8, 'http://www.tp.com/uploads/article/image/20190815\\61de3c91d57e76b87e3a0357c3502a27.jpg', 1, 'wechat.article', '2019-08-15 19:03:07', 'image');
INSERT INTO `wechat_uploads` VALUES (9, 'http://www.tp.com/uploads/article/image/20190815\\3bb941a8500195e1fd3634efa136d3ca.png', 1, 'wechat.article', '2019-08-15 19:04:15', 'image');

SET FOREIGN_KEY_CHECKS = 1;
