//var Emoticons = new Array('smiling','angel','angry', 'blushing','confused','cool','crying','erm','gasping','grin','kiss','money','sad','scared','tongue','winking','wub','touristtube');

var Emoticons = new Array( new Array(':)','(clap)','(whisper)','(bye)','()','(three)','(two)',':$',':(','(doh)','(kicked)','(wasntme)','(worry)','(sick)','[:)',':0','(haha)','(hehe)'
,':D',';D',':P','(party)','(cry)',':0:',':L',':BL','(pretty)','(k)','(pray)','(dontknow)','(didnthear)','(think)','(badsmell)','(dontwanthear)','(?)',':S','(evil)'),
						   new Array('t:-t','t:t','t:st','t:j','t:a','t:sp','t:l','t:q','t:-q','t:--q','t:-q-','st:t','t:-sq','-t:sq','-t:-t','-sq:-sq','t:lk','t:ulk','t:bs','t:s','t:c','t:r','t:$','t:e'),
						   new Array('(babygirl)','(babyboy)','(girl)','(boy)','(mum)','(dad)','(pumparty)','(pumpkin)','(greencake)','(santa)','(present)','(tree)','(cake)','(heart)','(love)','(lovebirds)','(happyheart)','(flowers)','(flower)'),
						   new Array('(a)','(s)','(g)','(p)','(v)','(sh)','(bl)','(fr)','(isl)','(sum)','(ski)','(steps)','(coco)','(strw)','(lemon)','(mart)','(drink)','(coffee)','(sfd)','(frh)','(tag)','(fwr)','(fwy)','(fwm)'),
						   new Array('(sunny)','(partly cloudy)','(cloudy)','(thunder)','(rain)','(showers)','(wind)','(haze)','(chance of rain)','(half-moon)','(full-moon)','(risk)','(cold)','(hot)','(fahrenheit)','(celsius)','(drop)','(ice)')
);


function EmoticonTextFind(string){
	var Emoticon = null;
	
	$.each(Emoticons,function(set_index,set_array){
		
		$.each(set_array, function(i,emoticon_text){
			if(string == emoticon_text)
			{
				Emoticon = "set"+(set_index+1)+"/"+(i+1);	
			}
		});
		
	});
	
	return Emoticon;
}


function EmoticonTextReplace(inString){
	
	var words = inString.split(' ');
	var i = 0;
	var outString = '';
	var Emoticon;
	while(i < words.length){
		
		if( (Emoticon = EmoticonTextFind(words[i])) == null ){
			outString += words[i];
		}else{
			outString += '<img src="'+AbsolutePath+'/images/Emoticons/'+Emoticon+'.png"/>';
		}
		
		i++;
		
		if(i < words.length) outString += ' ';
	}
	
	return outString;
}