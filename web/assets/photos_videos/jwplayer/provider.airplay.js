/*!
   JW Player version 8.6.2
   Copyright (c) 2018, JW Player, All Rights Reserved 
   This source code and its use and distribution is subject to the terms 
   and conditions of the applicable license agreement. 
   https://www.jwplayer.com/tos/
   This product includes portions of other software. For the full text of licenses, see
   https://ssl.p.jwpcdn.com/player/v/8.6.2/notice.txt
*/
(window.webpackJsonpjwplayer=window.webpackJsonpjwplayer||[]).push([[14],{150:function(e,t,a){"use strict";a.r(t),a.d(t,"default",function(){return l});var i=a(11);function l(e,t){var a=null,l=this;function n(){t.set("castState",{available:t.get("castAvailable"),active:t.get("castActive")})}function c(e){e&&e.forEach(function(e){e.file=Object(i.getAbsolutePath)(e.file)})}function r(e){e&&(e.image=Object(i.getAbsolutePath)(e.image),c(e.allSources),c(e.sources))}l.updateAvailability=function(e){t.set("castAvailable","available"===e.availability),n()},l.updateActive=function(){var i=!1;a&&(i=!!a.webkitCurrentPlaybackTargetIsWireless),t.off("change:playlistItem",r),i&&(e.instreamDestroy(),r(t.get("playlistItem")),t.on("change:playlistItem",r)),t.set("airplayActive",i),t.set("castActive",i),n()},l.airplayToggle=function(){a&&a.webkitShowPlaybackTargetPicker()},t.change("itemReady",function(){a=null,t.getVideo()&&(a=t.getVideo().video),a&&(a.removeEventListener("webkitplaybacktargetavailabilitychanged",l.updateAvailability),a.removeEventListener("webkitcurrentplaybacktargetiswirelesschanged",l.updateActive),a.addEventListener("webkitplaybacktargetavailabilitychanged",l.updateAvailability),a.addEventListener("webkitcurrentplaybacktargetiswirelesschanged",l.updateActive)),l.updateAvailability({}),l.updateActive()})}}}]);