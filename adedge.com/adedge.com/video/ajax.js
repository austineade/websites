function Ajax(){
	var ajax_box;
	var req;
	
	this.Init=function (){
	    try{
	        req=new ActiveXObject("Msxml2.XMLHTTP");
	    }
	    catch(e){
	        try{
	            req=new ActiveXObject("Microsoft.XMLHTTP");
	        }
	        catch(oc){
	            req=null;
	        }
	    }
	    if(!req&&typeof XMLHttpRequest!="undefined"){
	        req = new XMLHttpRequest();
		}
	}
	
	this.get=function(url,div){
	  	this.Init();
	  	if(req!=null){
	  		ajax_box=div;
		    req.onreadystatechange = this.getResult;
		    document.getElementById(ajax_box).innerHTML="<img src=\"images/loading.gif\" /> Loading...";
		    url=url.replace(/&amp;/g,'&');
		    req.open("get", baseurl+url, true);
		    try {
		       req.send(null);
		    } catch (ex) { document.getElementById(ajax_box).innerHTML="Your browser does not support this feature!";}
		}
	}
	
	
	
	this.getResult=function (){
	  if (req.readyState == 4){
	    if (req.status == 200){
	      document.getElementById(ajax_box).innerHTML=req.responseText;
	    }
	    else {
	        document.getElementById(ajax_box).innerHTML="Internal Server Error!";
	    }
	  }
	}
}

function Init(){
    try{
        req=new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e){
        try{
            req=new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(oc){
            req=null;
        }
    }
    if(!req&&typeof XMLHttpRequest!="undefined"){
        req = new XMLHttpRequest();
	}
}

function get_url_contents(url,div){
	var ajax=new Ajax();
	ajax.get(url,div);
}

function rate(id,rate){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_rate;
	    req.open("get", baseurl+"index.php?m=rate&id="+id+"&rate="+rate+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function add_to_favorites(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=favorites_add&id="+id+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function add_to_playlist(id, plid){
  	Init();
  	if(req!=null && plid!=0 && plid!="new"){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=favorites_add&id="+id+"&p="+plid+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}else if (plid=="new"){
		location.href=baseurl+"index.php?m=users.create_playlist";
	}
}

function report_video(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=report_video&id="+id+"&asynch", true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function report_comment(id){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_alert;
	    req.open("get", baseurl+"index.php?m=report_comment&id="+id, true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function show_playlist(id, pub, item){
  	Init();
  	if(req!=null){
  		ajax_box='ajax_box';
	    req.onreadystatechange = getResult;
	    
	    document.getElementById('playlist').value=id;
	    document.getElementById('edit_playlist').style.display="block";
	    
	    //if(pub==1) document.getElementById('public').checked=true;
	    //else document.getElementById('public').checked=false;
	    
	    if(item.title!=undefined)document.getElementById('description').innerHTML=item.title;
	    else document.getElementById('description').innerHTML=item;
	    
	    document.getElementById(ajax_box).innerHTML="<img src=\"images/loading.gif\" /> Loading...";
	    req.open("get", baseurl+"index.php?m=playlist&p="+id, true);
	    try {
	       req.send(null);
	    } catch (ex) { }
	}
}

function post_comment(url, form){
  	Init();
  	if(req!=null){
  		ajax_box='comments_box';
	    req.onreadystatechange = getResult;
	    req.open("post", baseurl+url, true);
	    try {
	       req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	       var send="";
	       var i;
  		   for(i=0; i<form.elements.length; i++){
  		   	var el=form.elements[i];
  		   	send+=form.elements[i].name+"="+escape(el.value)+"&";
  		   }
	       req.send(send);
	    } catch (ex) { }
	}	
}

function getResult(){
	  if (req.readyState == 4){
	    if (req.status == 200){
	      document.getElementById(ajax_box).innerHTML=req.responseText;
	    }
	    else {
	        document.getElementById(ajax_box).innerHTML="Internal Server Error!";
	    }
	  }
	}

function getResult_rate(){
  if (req.readyState == 4){
    if (req.status == 200){
      document.getElementById("ajax_rate").innerHTML=req.responseText;
    }
    else {
        document.getElementById("ajax_rate").innerHTML="Internal Server Error!";
    }
  }
}

function getResult_alert(){
  if (req.readyState == 4){
    if (req.status == 200){
      alert(req.responseText);
    }
    else {
      alert("Internal Server Error!");
    }
  }
}