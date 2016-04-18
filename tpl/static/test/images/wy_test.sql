-- phpMyAdmin SQL Dump
-- version 3.5.0-rc2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 05 月 30 日 12:08
-- 服务器版本: 5.5.36
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `demo`
--

-- --------------------------------------------------------

--
-- 表的结构 `wy_test`
--

CREATE TABLE IF NOT EXISTS `wy_test` (
  `tp_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` varchar(100) DEFAULT NULL,
  `indexpic` varchar(100) DEFAULT NULL,
  `questionpic` varchar(100) DEFAULT NULL,
  `bgcolor` varchar(100) NOT NULL DEFAULT 'ffcb1d',
  `title` varchar(100) NOT NULL,
  `qtitle` varchar(100) NOT NULL,
  `fistq` varchar(100) NOT NULL,
  `fistapic` varchar(100) NOT NULL,
  `fistatitle` varchar(100) NOT NULL,
  `fistatitle2` varchar(100) NOT NULL,
  `fistainfo` varchar(200) DEFAULT NULL,
  `secondq` varchar(100) NOT NULL,
  `secondapic` varchar(100) NOT NULL,
  `secondatitle` varchar(100) NOT NULL,
  `secondatitle2` varchar(100) NOT NULL,
  `secondainfo` varchar(200) DEFAULT NULL,
  `thirdq` varchar(100) DEFAULT NULL,
  `thirdapic` varchar(100) DEFAULT NULL,
  `thirdatitle` varchar(100) DEFAULT NULL,
  `thirdatitle2` varchar(100) DEFAULT NULL,
  `thirdainfo` varchar(200) DEFAULT NULL,
  `fourq` varchar(100) DEFAULT NULL,
  `fourapic` varchar(100) DEFAULT NULL,
  `fouratitle` varchar(100) DEFAULT NULL,
  `fouratitle2` varchar(100) DEFAULT NULL,
  `fourainfo` varchar(200) DEFAULT NULL,
  `fiveq` varchar(100) DEFAULT NULL,
  `fiveapic` varchar(100) DEFAULT NULL,
  `fiveatitle` varchar(100) DEFAULT NULL,
  `fiveatitle2` varchar(100) DEFAULT NULL,
  `fiveainfo` varchar(200) DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `fistfx` varchar(200) DEFAULT NULL,
  `secondfx` varchar(200) DEFAULT NULL,
  `thirdfx` varchar(200) DEFAULT NULL,
  `fourfx` varchar(200) DEFAULT NULL,
  `fivefx` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`tp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `wy_test`
--

INSERT INTO `wy_test` (`tp_id`, `token`, `name`, `keyword`, `wxtitle`, `wxpic`, `wxinfo`, `indexpic`, `questionpic`, `bgcolor`, `title`, `qtitle`, `fistq`, `fistapic`, `fistatitle`, `fistatitle2`, `fistainfo`, `secondq`, `secondapic`, `secondatitle`, `secondatitle2`, `secondainfo`, `thirdq`, `thirdapic`, `thirdatitle`, `thirdatitle2`, `thirdainfo`, `fourq`, `fourapic`, `fouratitle`, `fouratitle2`, `fourainfo`, `fiveq`, `fiveapic`, `fiveatitle`, `fiveatitle2`, `fiveainfo`, `pv`, `addtime`, `fistfx`, `secondfx`, `thirdfx`, `fourfx`, `fivefx`) VALUES
(1, '1d2e1b640424480', '趣味测试', '趣味测试', '趣味测试', 'http://demo.138wo.com/tpl/static/test/images/wxpic.jpg', '趣味测试', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/9/e/1/e/thumb_5568adb916f82.jpg', 'http://demo.138wo.com/tpl/static/attachment/icon/canyin/canyin_white/3.png', 'ffcb1d', '趣味测试', '今年年终奖发了多少钱', '10000-20000元', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/8/8/d/b/thumb_5568b13b1b2a5.jpg', '经鉴定，你属于：', '24K真土豪', '时尚最时尚，你的任性让我刮目相看', '20000-30000元', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/1/3/d/3/thumb_5568b11dddc2b.jpg', '经鉴定，你属于：', '24K真土豪', '时尚最时尚，你的任性让我刮目相看', '30000-40000元', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/8/f/7/b/thumb_5568b0e22a902.jpg', '经鉴定，你属于：', '24K真土豪', '时尚最时尚，你的任性让我刮目相看', '40000-50000元', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/8/f/7/b/thumb_5568b0e22a902.jpg', '经鉴定，你属于：', '24K真土豪', '时尚最时尚，你的任性让我刮目相看', '50000-60000元', 'http://demo.138wo.com/uploads/1/1d2e1b640424480/4/7/1/6/thumb_5568ae41e822e.jpg', '经鉴定，你属于：', '24K真土豪', '时尚最时尚，你的任性让我刮目相看', 5, 1432958766, '你打败了35%的小伙伴快去分享到朋友圈炫耀一下吧', '你打败了45%的小伙伴快去分享到朋友圈炫耀一下吧', '你打败了55%的小伙伴快去分享到朋友圈炫耀一下吧', '你打败了55%的小伙伴快去分享到朋友圈炫耀一下吧', '你打败了55%的小伙伴快去分享到朋友圈炫耀一下吧');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
