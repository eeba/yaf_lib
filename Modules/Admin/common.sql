-- --------------------------------------------------------

--
-- 表的结构 `admin_menu`
--

CREATE TABLE `admin_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(16) NOT NULL,
  `order` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `name`, `order`) VALUES(1, '设置', 99);

-- --------------------------------------------------------

--
-- 表的结构 `admin_menu_controller`
--

CREATE TABLE `admin_menu_controller` (
  `id` int(10) UNSIGNED NOT NULL,
  `mid` int(10) UNSIGNED NOT NULL,
  `controller` char(64) NOT NULL,
  `action` char(64) NOT NULL,
  `name` char(16) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `admin_menu_controller`
--

INSERT INTO `admin_menu_controller` (`id`, `mid`, `controller`, `action`, `name`, `order`) VALUES
(1, 1, 'Controller_Menu', 'indexAction', '菜单配置', 1),
(2, 1, 'Controller_User', 'indexAction', '管理员列表', 2),
(3, 1, 'Controller_Role', 'indexAction', '角色列表', 3);

-- --------------------------------------------------------

--
-- 表的结构 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常，2:禁用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `admin_role`
--

INSERT INTO `admin_role` (`id`, `name`, `status`) VALUES
(1, '开发', 1),
(2, '超级管理员', 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_role_acl`
--

CREATE TABLE `admin_role_acl` (
  `id` int(11) UNSIGNED NOT NULL,
  `rid` int(11) NOT NULL DEFAULT '0',
  `uri` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `admin_role_acl`
--

INSERT INTO `admin_role_acl` (`id`, `rid`, `uri`) VALUES
(1, 1, '[\"\\/admin\\/role\\/index\",\"\\/admin\\/role\\/save\",\"\\/admin\\/role\\/detail\",\"\\/admin\\/login\\/index\",\"\\/admin\\/login\\/dologin\",\"\\/admin\\/login\\/logout\",\"\\/admin\\/login\\/captcha\",\"\\/admin\\/util\\/upload\",\"\\/admin\\/user\\/index\",\"\\/admin\\/user\\/save\",\"\\/admin\\/user\\/detail\",\"\\/admin\\/user\\/resetpassword\",\"\\/admin\\/acl\\/index\",\"\\/admin\\/acl\\/save\",\"\\/admin\\/index\\/index\",\"\\/admin\\/index\\/welcome\",\"\\/admin\\/menu\\/index\",\"\\/admin\\/menu\\/savemenu\",\"\\/admin\\/menu\\/savechildmenu\",\"\\/admin\\/menu\\/del\",\"\\/admin\\/menu\\/delchild\",\"\\/admin\\/error\\/error\"]');

-- --------------------------------------------------------

--
-- 表的结构 `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `rid` int(11) NOT NULL,
  `email` varchar(64) DEFAULT '',
  `phone` char(11) DEFAULT '',
  `password` char(32) DEFAULT '',
  `salt` char(4) DEFAULT '',
  `nickname` varchar(64) DEFAULT '',
  `avatar` varchar(64) DEFAULT '',
  `register_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `register_ip` varchar(15) DEFAULT '',
  `status` tinyint(4) DEFAULT '2' COMMENT '状态,1打开,2关闭'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `admin_user`
--

INSERT INTO `admin_user` (`id`, `rid`, `email`, `phone`, `password`, `salt`, `nickname`, `avatar`, `register_time`, `register_ip`, `status`) VALUES
(1, 1, 'admin@admin.com', '15900000000', '9207cea894f92ea9309701abd643bb16', '5i7B', 'admin', '', '2018-08-01 19:37:29', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_user_acl`
--

CREATE TABLE `admin_user_acl` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) DEFAULT '0',
  `uri` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `admin_menu`
--
ALTER TABLE `admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_menu_controller`
--
ALTER TABLE `admin_menu_controller`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mid` (`mid`);

--
-- 表的索引 `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_role_acl`
--
ALTER TABLE `admin_role_acl`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_user_acl`
--
ALTER TABLE `admin_user_acl`
  ADD PRIMARY KEY (`id`);