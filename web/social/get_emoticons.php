<?php

if( !isset($bootOptions) ){
	$path = "";

	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
}

	


	$emoticonsFolders = array("set1" => array(37,1), "set2" => array(24,9), "set3" =>array(20,11), "set4" => array(24,1), "set5" => array(19,2)); 

	$emoticonsSymbols = array( "set1" => array(':)','(clap)','(whisper)','(bye)','()','(three)','(two)',':$',':(','(doh)','(kicked)','(wasntme)','(worry)','(sick)','[:)',':0','(haha)','(hehe)'
,':D',';D',':P','(party)','(cry)',':0:',':L',':BL','(pretty)','(k)','(pray)','(dontknow)','(didnthear)','(think)','(badsmell)','(dontwanthear)','(?)',':S','(evil)'),

						  "set2" => array('t:-t','t:t','t:st','t:j','t:a','t:sp','t:l','t:q','t:-q','t:--q','t:-q-','st:t','t:-sq','-t:sq','-t:-t','-sq:-sq','t:lk','t:ulk','t:bs','t:s','t:c','t:r','t:$','t:e'),
						  
						  "set3" => array('(babygirl)','(babyboy)','(girl)','(boy)','(mum)','(dad)','(pumparty)','(pumpkin)','(greencake)','(santa)','(present)','(tree)','(cake)','(heart)','(love)','(lovebirds)','(happyheart)','(flowers)','(flower)'),
						  
						  "set4" => array('(a)','(s)','(g)','(p)','(v)','(sh)','(bl)','(fr)','(isl)','(sum)','(ski)','(steps)','(coco)','(strw)','(lemon)','(mart)','(drink)','(coffee)','(sfd)','(frh)','(tag)','(fwr)','(fwy)','(fwm)'),
						  
						  "set5" => array('(sunny)','(partly cloudy)','(cloudy)','(thunder)','(rain)','(showers)','(wind)','(haze)','(chance of rain)','(half-moon)','(full-moon)','(risk)','(cold)','(hot)','(fahrenheit)','(celsius)','(drop)','(ice)')
);

	$emoticonsH = "";
	$emoticons = "";
	
	foreach($emoticonsFolders as $oneEmoticon => $oneEmoticonNB)
	{
		$emoticonsH .= "<div class='oneEmoTab'><span><img src='".ReturnLink("images/Emoticons/".$oneEmoticon."/".$oneEmoticonNB[1].".png'")." /></span></div>";
		
		
		$emoticons .= "<div class='oneEmoList'>";
		
		for($i = 1 ; $i <= $oneEmoticonNB[0]; $i++)
		$emoticons .= "<div class='one_emoticon_div ".$oneEmoticon."' title='".$emoticonsSymbols[$oneEmoticon][$i-1]."' data-set='".$oneEmoticon."'><div><img src='".ReturnLink("images/Emoticons/".$oneEmoticon."/".$i.".png'")." /></div></div>";
		
		$emoticons .= "</div>";
			
	}
	
	echo "<div class='emoHeaders'><div class='emotiArrow'></div>".$emoticonsH."</div>";
	
	echo "<div class='emoticonsContainer'>".$emoticons."</div>";

?>
	
