var rating=0;
var on=0;

function light(mode, star){
    if(mode=="on"){
    	on=1;
        var i=1;
        for(i=1; i<=star; i++)document.getElementById("st"+i).src="images/star_on.gif";
        for(i=star+1; i<=5; i++)document.getElementById("st"+i).src="images/star_off.gif";
    }else{
        var i=1;
        on=0;
        for(i=1; i<=5; i++)document.getElementById("st"+i).src="images/star_off.gif";
        setTimeout('light_rating()',1000);
    }
}

function light_rating(){
	if(on==0){
        for(i=1; i<=rating; i++)document.getElementById("st"+i).src="images/star_on.gif";
     }	
}

function highlight(e,name,c,ch){
	elements=document.getElementsByName(name);
	var i;
	for(i=0; i<elements.length; i++) {
		elements[i].style.background=c;
	}
	e.style.background=ch;
}

function show_partial(text,limit,div){
	if(text.length>limit){
		document.getElementById(div).innerHTML='<div style="display:none">'+text+' (<span class="qlink" onclick="this.parentNode.style.display=\'none\'; this.parentNode.nextSibling.style.display=\'block\'">Less</span>)</div>';
		var parts=text.split(/<br\s?\/?>/g);
		var partial='';
		for(i=0; i<parts.length; i++){
			if(partial.length+parts[i].length+6<=100)partial+=parts[i]+'<br />';
			else break;
		}
		if(partial=='')partial=parts[0].substr(0,100);
		document.getElementById(div).innerHTML+='<div>'+partial+'... (<span class="qlink" onclick="this.parentNode.style.display=\'none\'; this.parentNode.previousSibling.style.display=\'block\'">More</span>)</div>';
	}else{
		document.getElementById(div).innerHTML=text;
	}
}

function popup(url,position){
	var pos;
	if (window.innerHeight){
		pos = window.pageYOffset
	}else if (document.documentElement && document.documentElement.scrollTop){
		pos = document.documentElement.scrollTop
	}else if (document.body){
		pos = document.body.scrollTop
	}
	
	switch(position){
		case 'topright':
		  var x=screen.width-540;
		  var y=pos+105;
		break;
		default:
		  var x=screen.width/2-240;
		  var y=pos+150;
	}
	document.getElementById('popup').style.top=y+'px';
	document.getElementById('popup').style.left=x+'px';
	
	var player=document.getElementById('player_wrap');
	if(player!=undefined)player.style.display='none';
	
	document.getElementById('popup').style.display='block';
	get_url_contents(url,'popup_body');
}

function close_popup(){
	document.getElementById('popup').style.display='none';
	var player=document.getElementById('player_wrap');
	if(player!=undefined)player.style.display='block';
}

function livesearch(value){
	if(value!=''){
		popup('index.php?m=searchpreview&search='+escape(value),'topright');
	}else{
		close_popup();
	}
}