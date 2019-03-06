-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2019 at 07:50 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `darcvi9_se`
--

-- --------------------------------------------------------

--
-- Table structure for table `fotkice`
--

CREATE TABLE `fotkice` (
  `id` int(100) NOT NULL,
  `adr` varchar(9999) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `fun_acc`
--

CREATE TABLE `fun_acc` (
  `id` int(100) NOT NULL,
  `gid` int(100) NOT NULL DEFAULT '0',
  `fid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_announcements`
--

CREATE TABLE `fun_announcements` (
  `id` int(100) NOT NULL,
  `antext` varchar(200) NOT NULL DEFAULT '',
  `clid` int(100) NOT NULL DEFAULT '0',
  `antime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_avatars`
--

CREATE TABLE `fun_avatars` (
  `id` int(10) NOT NULL,
  `avlink` varchar(150) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_blogcomment`
--

CREATE TABLE `fun_blogcomment` (
  `id` int(100) NOT NULL,
  `blogowner` int(100) NOT NULL DEFAULT '0',
  `blogsigner` int(100) NOT NULL DEFAULT '0',
  `blogmsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fun_blogs`
--

CREATE TABLE `fun_blogs` (
  `id` int(100) NOT NULL,
  `bowner` int(100) NOT NULL DEFAULT '0',
  `bname` varchar(30) NOT NULL DEFAULT '',
  `btext` blob NOT NULL,
  `bgdate` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_brate`
--

CREATE TABLE `fun_brate` (
  `id` int(100) NOT NULL,
  `blogid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `brate` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_buddies`
--

CREATE TABLE `fun_buddies` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `tid` int(100) NOT NULL DEFAULT '0',
  `agreed` char(1) NOT NULL DEFAULT '0',
  `reqdt` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_cards`
--

CREATE TABLE `fun_cards` (
  `id` int(10) NOT NULL,
  `fntsz` int(5) NOT NULL DEFAULT '0',
  `xst` int(10) NOT NULL DEFAULT '0',
  `yst` int(10) NOT NULL DEFAULT '0',
  `xjp` int(10) NOT NULL DEFAULT '0',
  `yjp` int(10) NOT NULL DEFAULT '0',
  `tcolor` varchar(20) NOT NULL DEFAULT '',
  `category` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_chat`
--

CREATE TABLE `fun_chat` (
  `id` int(99) NOT NULL,
  `chatter` int(100) NOT NULL DEFAULT '0',
  `who` int(100) NOT NULL DEFAULT '0',
  `timesent` int(50) NOT NULL DEFAULT '0',
  `msgtext` varchar(255) NOT NULL DEFAULT '',
  `rid` int(99) NOT NULL DEFAULT '0',
  `exposed` char(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_chonline`
--

CREATE TABLE `fun_chonline` (
  `lton` int(15) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `rid` int(99) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_clubmembers`
--

CREATE TABLE `fun_clubmembers` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `clid` int(100) NOT NULL DEFAULT '0',
  `accepted` char(1) NOT NULL DEFAULT '0',
  `points` int(100) NOT NULL DEFAULT '0',
  `joined` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_clubs`
--

CREATE TABLE `fun_clubs` (
  `id` int(100) NOT NULL,
  `owner` int(100) NOT NULL DEFAULT '0',
  `name` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `rules` blob NOT NULL,
  `logo` varchar(200) NOT NULL DEFAULT '',
  `plusses` int(100) NOT NULL DEFAULT '0',
  `created` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_download`
--

CREATE TABLE `fun_download` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `itemurl` varchar(255) NOT NULL DEFAULT '',
  `pudt` int(100) NOT NULL DEFAULT '0',
  `type` int(100) NOT NULL DEFAULT '0',
  `downloads` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fun_faqs`
--

CREATE TABLE `fun_faqs` (
  `id` int(100) NOT NULL,
  `category` varchar(10) NOT NULL DEFAULT '',
  `question` varchar(100) NOT NULL DEFAULT '',
  `answer` varchar(250) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_favtopic`
--

CREATE TABLE `fun_favtopic` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `favtpc` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fun_fcats`
--

CREATE TABLE `fun_fcats` (
  `id` int(50) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `position` int(50) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_forums`
--

CREATE TABLE `fun_forums` (
  `id` int(50) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `position` int(50) NOT NULL DEFAULT '0',
  `cid` int(100) NOT NULL DEFAULT '0',
  `clubid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_fotografije`
--

CREATE TABLE `fun_fotografije` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `imageurl` varchar(100) NOT NULL DEFAULT '',
  `sex` char(1) NOT NULL DEFAULT '',
  `time` int(100) NOT NULL DEFAULT '0',
  `descript` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fun_games`
--

CREATE TABLE `fun_games` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `gvar1` varchar(30) NOT NULL DEFAULT '',
  `gvar2` varchar(30) NOT NULL DEFAULT '',
  `gvar3` varchar(30) NOT NULL DEFAULT '',
  `gvar4` varchar(30) NOT NULL DEFAULT '',
  `gvar5` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_gbook`
--

CREATE TABLE `fun_gbook` (
  `id` int(100) NOT NULL,
  `gbowner` int(100) NOT NULL DEFAULT '0',
  `gbsigner` int(100) NOT NULL DEFAULT '0',
  `gbmsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_groups`
--

CREATE TABLE `fun_groups` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `autoass` char(1) NOT NULL DEFAULT '1',
  `mage` int(10) NOT NULL DEFAULT '0',
  `userst` char(1) NOT NULL DEFAULT '0',
  `posts` int(100) NOT NULL DEFAULT '0',
  `plusses` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_hangman`
--

CREATE TABLE `fun_hangman` (
  `id` int(100) NOT NULL,
  `text` varchar(30) NOT NULL DEFAULT '',
  `dscr` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_ignore`
--

CREATE TABLE `fun_ignore` (
  `id` int(10) NOT NULL,
  `name` int(99) NOT NULL DEFAULT '0',
  `target` int(99) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_judges`
--

CREATE TABLE `fun_judges` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `fid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_komentari`
--

CREATE TABLE `fun_komentari` (
  `id` int(100) NOT NULL,
  `komowner` int(100) NOT NULL DEFAULT '0',
  `komsigner` int(100) NOT NULL DEFAULT '0',
  `kommsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_komentari_foto`
--

CREATE TABLE `fun_komentari_foto` (
  `id` int(100) NOT NULL,
  `komowner` int(100) NOT NULL DEFAULT '0',
  `komsigner` int(100) NOT NULL DEFAULT '0',
  `kommsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_komentari_grupa`
--

CREATE TABLE `fun_komentari_grupa` (
  `id` int(100) NOT NULL,
  `komowner` int(100) NOT NULL DEFAULT '0',
  `komsigner` int(100) NOT NULL DEFAULT '0',
  `kommsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0',
  `club` int(100) NOT NULL DEFAULT '0',
  `grupa` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_komentari_zid`
--

CREATE TABLE `fun_komentari_zid` (
  `id` int(100) NOT NULL,
  `komowner` int(100) NOT NULL DEFAULT '0',
  `komsigner` int(100) NOT NULL DEFAULT '0',
  `kommsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_mangr`
--

CREATE TABLE `fun_mangr` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `gid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_mlog`
--

CREATE TABLE `fun_mlog` (
  `id` int(100) NOT NULL,
  `action` varchar(10) NOT NULL DEFAULT '',
  `details` blob NOT NULL,
  `actdt` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_mms`
--

CREATE TABLE `fun_mms` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `filename` text NOT NULL,
  `date` varchar(100) NOT NULL DEFAULT '',
  `filesize` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fun_modr`
--

CREATE TABLE `fun_modr` (
  `id` int(11) NOT NULL,
  `name` int(100) NOT NULL DEFAULT '0',
  `forum` varchar(99) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_moods`
--

CREATE TABLE `fun_moods` (
  `id` int(99) NOT NULL,
  `text` varchar(10) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `dscr` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_mpot`
--

CREATE TABLE `fun_mpot` (
  `id` int(10) NOT NULL,
  `ddt` varchar(20) NOT NULL DEFAULT '',
  `dtm` varchar(20) NOT NULL DEFAULT '',
  `ppl` int(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_nicks`
--

CREATE TABLE `fun_nicks` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `nicklvl` char(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_notify`
--

CREATE TABLE `fun_notify` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL DEFAULT '0',
  `touid` int(100) NOT NULL DEFAULT '0',
  `unread` char(1) NOT NULL DEFAULT '1',
  `timesent` int(100) NOT NULL DEFAULT '0',
  `starred` char(1) NOT NULL DEFAULT '0',
  `reported` char(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_online`
--

CREATE TABLE `fun_online` (
  `id` int(10) NOT NULL,
  `userid` int(100) NOT NULL DEFAULT '0',
  `actvtime` int(100) NOT NULL DEFAULT '0',
  `place` varchar(50) NOT NULL DEFAULT '',
  `placedet` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_penalties`
--

CREATE TABLE `fun_penalties` (
  `id` int(10) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `penalty` char(1) NOT NULL DEFAULT '0',
  `exid` int(100) NOT NULL DEFAULT '0',
  `timeto` int(100) NOT NULL DEFAULT '0',
  `pnreas` varchar(100) NOT NULL DEFAULT '',
  `ipadd` varchar(30) NOT NULL DEFAULT '',
  `browserm` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_poke`
--

CREATE TABLE `fun_poke` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `pid` int(100) NOT NULL DEFAULT '0',
  `vreme` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_polls`
--

CREATE TABLE `fun_polls` (
  `id` int(100) NOT NULL,
  `pqst` varchar(255) NOT NULL DEFAULT '',
  `opt1` varchar(100) NOT NULL DEFAULT '',
  `opt2` varchar(100) NOT NULL DEFAULT '',
  `opt3` varchar(100) NOT NULL DEFAULT '',
  `opt4` varchar(100) NOT NULL DEFAULT '',
  `opt5` varchar(100) NOT NULL DEFAULT '',
  `pdt` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_posts`
--

CREATE TABLE `fun_posts` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `tid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `dtpost` int(100) NOT NULL DEFAULT '0',
  `reported` char(1) NOT NULL DEFAULT '0',
  `quote` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_pp_gbook`
--

CREATE TABLE `fun_pp_gbook` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `sname` varchar(15) NOT NULL DEFAULT '',
  `semail` varchar(100) NOT NULL DEFAULT '',
  `stext` text NOT NULL,
  `sdate` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_pp_pres`
--

CREATE TABLE `fun_pp_pres` (
  `id` int(100) NOT NULL,
  `pid` int(100) NOT NULL DEFAULT '0',
  `ans` int(5) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_presults`
--

CREATE TABLE `fun_presults` (
  `id` int(100) NOT NULL,
  `pid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `ans` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_private`
--

CREATE TABLE `fun_private` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL DEFAULT '0',
  `touid` int(100) NOT NULL DEFAULT '0',
  `unread` char(1) NOT NULL DEFAULT '1',
  `timesent` int(100) NOT NULL DEFAULT '0',
  `starred` char(1) NOT NULL DEFAULT '0',
  `reported` char(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_rooms`
--

CREATE TABLE `fun_rooms` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `pass` varchar(100) NOT NULL DEFAULT '',
  `static` char(1) NOT NULL DEFAULT '',
  `mage` int(10) NOT NULL DEFAULT '0',
  `chposts` int(100) NOT NULL DEFAULT '0',
  `perms` int(10) NOT NULL DEFAULT '0',
  `censord` char(1) NOT NULL DEFAULT '1',
  `freaky` char(1) NOT NULL DEFAULT '0',
  `lastmsg` int(100) NOT NULL DEFAULT '0',
  `clubid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_rss`
--

CREATE TABLE `fun_rss` (
  `id` int(50) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(150) NOT NULL DEFAULT '',
  `srcd` varchar(200) NOT NULL DEFAULT '',
  `dscr` varchar(100) NOT NULL DEFAULT '',
  `imgsrc` varchar(100) NOT NULL DEFAULT '',
  `pubdate` varchar(50) NOT NULL DEFAULT '',
  `fid` int(50) NOT NULL DEFAULT '0',
  `lupdate` int(100) NOT NULL DEFAULT '0',
  `pgurl` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_rssdata`
--

CREATE TABLE `fun_rssdata` (
  `id` int(100) NOT NULL,
  `rssid` int(50) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `imgsrc` varchar(255) NOT NULL DEFAULT '',
  `text` blob NOT NULL,
  `pubdate` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_search`
--

CREATE TABLE `fun_search` (
  `id` int(20) NOT NULL,
  `svar1` varchar(50) NOT NULL DEFAULT '',
  `svar2` varchar(50) NOT NULL DEFAULT '',
  `svar3` varchar(50) NOT NULL DEFAULT '',
  `svar4` varchar(50) NOT NULL DEFAULT '',
  `svar5` varchar(50) NOT NULL DEFAULT '',
  `stime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_ses`
--

CREATE TABLE `fun_ses` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `uid` varchar(30) NOT NULL DEFAULT '',
  `expiretm` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_settings`
--

CREATE TABLE `fun_settings` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_shouts`
--

CREATE TABLE `fun_shouts` (
  `id` int(100) NOT NULL,
  `shout` blob,
  `shouter` int(100) NOT NULL DEFAULT '0',
  `shtime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_shouts_grupa`
--

CREATE TABLE `fun_shouts_grupa` (
  `id` int(100) NOT NULL,
  `shout` blob,
  `shouter` int(100) NOT NULL DEFAULT '0',
  `shtime` int(100) NOT NULL DEFAULT '0',
  `club` int(100) NOT NULL DEFAULT '0',
  `grupa` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_shout_like`
--

CREATE TABLE `fun_shout_like` (
  `id` int(100) NOT NULL,
  `shid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `reqdt` int(100) NOT NULL DEFAULT '0',
  `liked` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_shout_like_grupa`
--

CREATE TABLE `fun_shout_like_grupa` (
  `id` int(100) NOT NULL,
  `shid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `reqdt` int(100) NOT NULL DEFAULT '0',
  `liked` int(100) NOT NULL DEFAULT '0',
  `grupa` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_shout_like_zid`
--

CREATE TABLE `fun_shout_like_zid` (
  `id` int(100) NOT NULL,
  `shid` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `reqdt` int(100) NOT NULL DEFAULT '0',
  `liked` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_smilies`
--

CREATE TABLE `fun_smilies` (
  `id` int(100) NOT NULL,
  `scode` varchar(15) NOT NULL DEFAULT '',
  `imgsrc` varchar(200) NOT NULL DEFAULT '',
  `hidden` char(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_tema`
--

CREATE TABLE `fun_tema` (
  `id` int(100) NOT NULL,
  `tema` text NOT NULL,
  `boja` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_topics`
--

CREATE TABLE `fun_topics` (
  `id` int(100) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `fid` int(100) NOT NULL DEFAULT '0',
  `authorid` int(100) NOT NULL DEFAULT '0',
  `text` blob NOT NULL,
  `pinned` char(1) NOT NULL DEFAULT '0',
  `closed` char(1) NOT NULL DEFAULT '0',
  `crdate` int(100) NOT NULL DEFAULT '0',
  `views` int(100) NOT NULL DEFAULT '0',
  `reported` char(1) NOT NULL DEFAULT '0',
  `lastpost` int(100) NOT NULL DEFAULT '0',
  `moved` char(1) NOT NULL DEFAULT '0',
  `pollid` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_users`
--

CREATE TABLE `fun_users` (
  `id` int(100) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `pass` varchar(60) NOT NULL DEFAULT '',
  `birthday` varchar(50) NOT NULL DEFAULT '',
  `sex` char(1) NOT NULL DEFAULT '',
  `location` varchar(100) NOT NULL DEFAULT '',
  `perm` char(1) NOT NULL DEFAULT '0',
  `posts` int(100) NOT NULL DEFAULT '0',
  `plusses` int(100) NOT NULL DEFAULT '0',
  `signature` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `site` varchar(50) NOT NULL DEFAULT '',
  `browserm` varchar(50) NOT NULL DEFAULT '',
  `ipadd` varchar(30) NOT NULL DEFAULT '',
  `lastact` int(100) NOT NULL DEFAULT '0',
  `regdate` int(100) NOT NULL DEFAULT '0',
  `chmsgs` int(100) NOT NULL DEFAULT '0',
  `chmood` int(100) NOT NULL DEFAULT '0',
  `shield` char(1) NOT NULL DEFAULT '0',
  `gplus` int(100) NOT NULL DEFAULT '0',
  `budmsg` varchar(100) NOT NULL DEFAULT '',
  `lastpnreas` varchar(100) NOT NULL DEFAULT '',
  `lastplreas` varchar(100) NOT NULL DEFAULT '',
  `shouts` int(100) NOT NULL DEFAULT '0',
  `pollid` int(100) NOT NULL DEFAULT '0',
  `rbcid` varchar(255) NOT NULL DEFAULT '',
  `hvia` char(1) NOT NULL DEFAULT '1',
  `lastvst` int(100) NOT NULL DEFAULT '0',
  `battlep` int(100) NOT NULL DEFAULT '0',
  `tema` int(100) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_usfun`
--

CREATE TABLE `fun_usfun` (
  `id` int(100) NOT NULL DEFAULT '0',
  `uid` int(100) NOT NULL DEFAULT '0',
  `action` varchar(10) NOT NULL DEFAULT '',
  `target` int(100) NOT NULL DEFAULT '0',
  `actime` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_vault`
--

CREATE TABLE `fun_vault` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `itemurl` varchar(255) NOT NULL DEFAULT '',
  `pudt` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `fun_xinfo`
--

CREATE TABLE `fun_xinfo` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT '0',
  `gmailun` varchar(100) NOT NULL DEFAULT '',
  `gmailpw` blob NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `street` varchar(50) NOT NULL DEFAULT '',
  `timezone` char(3) NOT NULL DEFAULT '',
  `height` varchar(10) NOT NULL DEFAULT '',
  `weight` varchar(10) NOT NULL DEFAULT '',
  `phoneno` varchar(20) NOT NULL DEFAULT '',
  `likes` varchar(250) NOT NULL DEFAULT '',
  `deslikes` varchar(250) NOT NULL DEFAULT '',
  `realname` varchar(100) NOT NULL DEFAULT '',
  `racerel` varchar(100) NOT NULL DEFAULT '',
  `hairtype` varchar(50) NOT NULL DEFAULT '',
  `eyescolor` varchar(10) NOT NULL DEFAULT '',
  `profession` varchar(100) NOT NULL DEFAULT '',
  `habitsb` varchar(250) NOT NULL DEFAULT '',
  `habitsg` varchar(250) NOT NULL DEFAULT '',
  `favsport` varchar(100) NOT NULL DEFAULT '',
  `favmusic` varchar(100) NOT NULL DEFAULT '',
  `moretext` blob NOT NULL,
  `sitedscr` varchar(200) NOT NULL DEFAULT '',
  `budsonly` char(1) NOT NULL DEFAULT '1',
  `sexpre` char(1) NOT NULL DEFAULT '',
  `gmailchk` int(10) NOT NULL DEFAULT '30',
  `gmaillch` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `knjiga`
--

CREATE TABLE `knjiga` (
  `id` int(100) NOT NULL,
  `name` tinytext NOT NULL,
  `mail` varchar(30) NOT NULL DEFAULT '',
  `text` blob NOT NULL,
  `time` int(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `lib3rtymrc_links`
--

CREATE TABLE `lib3rtymrc_links` (
  `id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `uid` int(10) NOT NULL,
  `description` varchar(200) NOT NULL,
  `timesent` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fotkice`
--
ALTER TABLE `fotkice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_acc`
--
ALTER TABLE `fun_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_announcements`
--
ALTER TABLE `fun_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_avatars`
--
ALTER TABLE `fun_avatars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `avlink` (`avlink`);

--
-- Indexes for table `fun_blogcomment`
--
ALTER TABLE `fun_blogcomment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_blogs`
--
ALTER TABLE `fun_blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bname` (`bname`);

--
-- Indexes for table `fun_brate`
--
ALTER TABLE `fun_brate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_buddies`
--
ALTER TABLE `fun_buddies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `fun_cards`
--
ALTER TABLE `fun_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_chat`
--
ALTER TABLE `fun_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_chonline`
--
ALTER TABLE `fun_chonline`
  ADD PRIMARY KEY (`lton`),
  ADD UNIQUE KEY `username` (`uid`);

--
-- Indexes for table `fun_clubmembers`
--
ALTER TABLE `fun_clubmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_clubs`
--
ALTER TABLE `fun_clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_download`
--
ALTER TABLE `fun_download`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_faqs`
--
ALTER TABLE `fun_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_favtopic`
--
ALTER TABLE `fun_favtopic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_fcats`
--
ALTER TABLE `fun_fcats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_forums`
--
ALTER TABLE `fun_forums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_fotografije`
--
ALTER TABLE `fun_fotografije`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_games`
--
ALTER TABLE `fun_games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_gbook`
--
ALTER TABLE `fun_gbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_groups`
--
ALTER TABLE `fun_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_hangman`
--
ALTER TABLE `fun_hangman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_ignore`
--
ALTER TABLE `fun_ignore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_judges`
--
ALTER TABLE `fun_judges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_komentari`
--
ALTER TABLE `fun_komentari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_komentari_foto`
--
ALTER TABLE `fun_komentari_foto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_komentari_grupa`
--
ALTER TABLE `fun_komentari_grupa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_komentari_zid`
--
ALTER TABLE `fun_komentari_zid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_mangr`
--
ALTER TABLE `fun_mangr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_mlog`
--
ALTER TABLE `fun_mlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_mms`
--
ALTER TABLE `fun_mms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_modr`
--
ALTER TABLE `fun_modr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_moods`
--
ALTER TABLE `fun_moods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_mpot`
--
ALTER TABLE `fun_mpot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_nicks`
--
ALTER TABLE `fun_nicks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_notify`
--
ALTER TABLE `fun_notify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_online`
--
ALTER TABLE `fun_online`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `fun_penalties`
--
ALTER TABLE `fun_penalties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_poke`
--
ALTER TABLE `fun_poke`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `fun_polls`
--
ALTER TABLE `fun_polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_posts`
--
ALTER TABLE `fun_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_pp_gbook`
--
ALTER TABLE `fun_pp_gbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_pp_pres`
--
ALTER TABLE `fun_pp_pres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_presults`
--
ALTER TABLE `fun_presults`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_private`
--
ALTER TABLE `fun_private`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_rooms`
--
ALTER TABLE `fun_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_rss`
--
ALTER TABLE `fun_rss`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_rssdata`
--
ALTER TABLE `fun_rssdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_search`
--
ALTER TABLE `fun_search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_ses`
--
ALTER TABLE `fun_ses`
  ADD UNIQUE KEY `id` (`id`,`uid`);

--
-- Indexes for table `fun_settings`
--
ALTER TABLE `fun_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_shouts`
--
ALTER TABLE `fun_shouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_shouts_grupa`
--
ALTER TABLE `fun_shouts_grupa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_shout_like`
--
ALTER TABLE `fun_shout_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`shid`);

--
-- Indexes for table `fun_shout_like_grupa`
--
ALTER TABLE `fun_shout_like_grupa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`shid`);

--
-- Indexes for table `fun_shout_like_zid`
--
ALTER TABLE `fun_shout_like_zid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`shid`);

--
-- Indexes for table `fun_smilies`
--
ALTER TABLE `fun_smilies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scode` (`scode`);

--
-- Indexes for table `fun_tema`
--
ALTER TABLE `fun_tema`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_topics`
--
ALTER TABLE `fun_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_users`
--
ALTER TABLE `fun_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fun_usfun`
--
ALTER TABLE `fun_usfun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_vault`
--
ALTER TABLE `fun_vault`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fun_xinfo`
--
ALTER TABLE `fun_xinfo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `knjiga`
--
ALTER TABLE `knjiga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lib3rtymrc_links`
--
ALTER TABLE `lib3rtymrc_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fotkice`
--
ALTER TABLE `fotkice`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_acc`
--
ALTER TABLE `fun_acc`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_announcements`
--
ALTER TABLE `fun_announcements`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_avatars`
--
ALTER TABLE `fun_avatars`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_blogcomment`
--
ALTER TABLE `fun_blogcomment`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_blogs`
--
ALTER TABLE `fun_blogs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_brate`
--
ALTER TABLE `fun_brate`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_buddies`
--
ALTER TABLE `fun_buddies`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_cards`
--
ALTER TABLE `fun_cards`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_chat`
--
ALTER TABLE `fun_chat`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_clubmembers`
--
ALTER TABLE `fun_clubmembers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_clubs`
--
ALTER TABLE `fun_clubs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_download`
--
ALTER TABLE `fun_download`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_faqs`
--
ALTER TABLE `fun_faqs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_favtopic`
--
ALTER TABLE `fun_favtopic`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_fcats`
--
ALTER TABLE `fun_fcats`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_forums`
--
ALTER TABLE `fun_forums`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_fotografije`
--
ALTER TABLE `fun_fotografije`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_games`
--
ALTER TABLE `fun_games`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_gbook`
--
ALTER TABLE `fun_gbook`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_groups`
--
ALTER TABLE `fun_groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_hangman`
--
ALTER TABLE `fun_hangman`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_ignore`
--
ALTER TABLE `fun_ignore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_judges`
--
ALTER TABLE `fun_judges`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_komentari`
--
ALTER TABLE `fun_komentari`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_komentari_foto`
--
ALTER TABLE `fun_komentari_foto`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_komentari_grupa`
--
ALTER TABLE `fun_komentari_grupa`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_komentari_zid`
--
ALTER TABLE `fun_komentari_zid`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_mangr`
--
ALTER TABLE `fun_mangr`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_mlog`
--
ALTER TABLE `fun_mlog`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_mms`
--
ALTER TABLE `fun_mms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_modr`
--
ALTER TABLE `fun_modr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_moods`
--
ALTER TABLE `fun_moods`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_mpot`
--
ALTER TABLE `fun_mpot`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_nicks`
--
ALTER TABLE `fun_nicks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_notify`
--
ALTER TABLE `fun_notify`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_online`
--
ALTER TABLE `fun_online`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_penalties`
--
ALTER TABLE `fun_penalties`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_poke`
--
ALTER TABLE `fun_poke`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_polls`
--
ALTER TABLE `fun_polls`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_posts`
--
ALTER TABLE `fun_posts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_pp_gbook`
--
ALTER TABLE `fun_pp_gbook`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_pp_pres`
--
ALTER TABLE `fun_pp_pres`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_presults`
--
ALTER TABLE `fun_presults`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_private`
--
ALTER TABLE `fun_private`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_rooms`
--
ALTER TABLE `fun_rooms`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_rss`
--
ALTER TABLE `fun_rss`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_rssdata`
--
ALTER TABLE `fun_rssdata`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_search`
--
ALTER TABLE `fun_search`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_settings`
--
ALTER TABLE `fun_settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_shouts`
--
ALTER TABLE `fun_shouts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_shouts_grupa`
--
ALTER TABLE `fun_shouts_grupa`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_shout_like`
--
ALTER TABLE `fun_shout_like`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_shout_like_grupa`
--
ALTER TABLE `fun_shout_like_grupa`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_shout_like_zid`
--
ALTER TABLE `fun_shout_like_zid`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_smilies`
--
ALTER TABLE `fun_smilies`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_tema`
--
ALTER TABLE `fun_tema`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_topics`
--
ALTER TABLE `fun_topics`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_users`
--
ALTER TABLE `fun_users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_vault`
--
ALTER TABLE `fun_vault`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fun_xinfo`
--
ALTER TABLE `fun_xinfo`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `knjiga`
--
ALTER TABLE `knjiga`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lib3rtymrc_links`
--
ALTER TABLE `lib3rtymrc_links`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
