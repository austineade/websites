var p;
var start;
var search;
var update_id;
var update_count;
var params=new Array();
var values=new Array();

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

/*function edit(id,count){
  var c_edit=document.getElementById('c_edit'+id);
  var c_update=document.getElementById('c_update'+id);
  var c_cancel=document.getElementById('c_cancel'+id);
  var i;
  for(i=0; i<count; i++){
  	var field=document.getElementById('field'+id+'_'+i);
  	var edit=document.getElementById('edit'+id+'_'+i);
  	if (field!=undefined && edit!=undefined){
  		field.style.display='none';
  		edit.style.display='block';
  		c_edit.style.display='none';
  		c_update.style.display='inline';
  		c_cancel.style.display='inline';
  	}
  }
}*/

function edit(id,count){
  var c_edit=document.getElementById('c_edit'+id);
  var c_update=document.getElementById('c_update'+id);
  var c_cancel=document.getElementById('c_cancel'+id);
  var field=document.getElementById('field'+id+'_1');
  var param_str='';
  for(i=0; i<params.length; i++){
  	param_str+='&'+params[i]+'='+values[params[i]];
  }
  popup('index.php?p='+p+'&start='+start+'&search='+search+param_str+'&a=edit&ajax=1&'+field.name+'='+escape(field.value));
}

function cancel(id,count){
  var c_edit=document.getElementById('c_edit'+id);
  var c_update=document.getElementById('c_update'+id);
  var c_cancel=document.getElementById('c_cancel'+id);
  var i;
  for(i=0; i<count; i++){
  	var field=document.getElementById('field'+id+'_'+i);
  	var edit=document.getElementById('edit'+id+'_'+i);
  	if (field!=undefined && edit!=undefined){
  		field.style.display='block';
  		edit.style.display='none';
  		c_edit.style.display='inline';
  		c_update.style.display='none';
  		c_cancel.style.display='none';
  		if(edit.type=='checkbox')edit.checked=field.checked;
	  	else if(edit.options)edit.selectedIndex=field.selectedIndex;
	  	else edit.value=html_decode(field.innerHTML);
  	}
  }
}

function update(id,count){
  	Init();
  	if(req!=null){
  		var c_loading=document.getElementById('loading'+id);
  		var c_update=document.getElementById('c_update'+id);
  		c_loading.style.display='inline';
  		c_update.style.display='none';
	    req.onreadystatechange = getResult_update;
	    req.open("post", "index.php?p="+p+"&a=edit&save=1&ajax=1", true);
	    try {
	       req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	       var send="";
	       var i;
  		   for(i=0; i<count; i++){
  		   	var edit=document.getElementById('edit'+id+'_'+i);
  		   	var field=document.getElementById('field'+id+'_'+i);
  		   	if(field!=undefined){
  		   		if(edit!=undefined){
					var value;
					var name=edit.name.substring(0,edit.name.indexOf('['))+"[]";
					if(edit.type=='checkbox'){
						if(edit.checked)value=edit.value;
						else value='';
					}
					else if(edit.options){
						value=edit.options[edit.selectedIndex].value;
					}
					else {
						value=edit.value;
					}
  		   			send+=name+"="+escape(value)+"&";
  		   		} else if(field.value!=undefined) {
  		   			send+=field.name+"="+escape(field.value)+"&";
  		   		}
  		   	}
  		   }
  		   update_id=id;
  		   update_count=count;
	       req.send(send);
	    } catch (ex) { }
	}
}

function add(url, form){
  	Init();
  	if(req!=null){
	    req.onreadystatechange = getResult_add;
	    req.open("post", url, true);
	    try {
	       req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	       var send="";
	       var i;
  		   for(i=0; i<form.elements.length; i++){
  		   	var el=form.elements[i];
  		   	var value;
  		   	if(el.options)value=el.options[el.selectedIndex].value;
  		   	else if(el.type=='checkbox'){
  		   		if(el.checked)value=el.value;
  		   		else value="";
  		   	}
  		   	else value=el.value;
  		   	send+=form.elements[i].name+"="+escape(value)+"&";
  		   }
  		   document.getElementById('ajax_box').innerHTML="<img src=\"images/loading.gif\" /> Loading...";
	       req.send(send);
	    } catch (ex) { }
	}	
}

function getResult_update(){
  if (req.readyState == 4){
    if (req.status == 200){
    	if(req.responseText!="0"){
		  var c_edit=document.getElementById('c_edit'+update_id);
	      var c_cancel=document.getElementById('c_cancel'+update_id);
	      var c_loading=document.getElementById('loading'+update_id);
	  	  var i;
		  for(i=0; i<update_count; i++){
		  	var field=document.getElementById('field'+update_id+'_'+i);
		  	var edit=document.getElementById('edit'+update_id+'_'+i);
		  	if (field!=undefined && edit!=undefined){
		  	  edit.style.display='none';
		  	  if(edit.type=='checkbox')field.checked=edit.checked;
		  	  else if(edit.options)field.selectedIndex=edit.selectedIndex;
		  	  else field.innerHTML=html_encode(edit.value);
		  	  field.style.display='block';
		      c_edit.style.display='inline';
		  	  c_cancel.style.display='none';
		  	  c_loading.style.display='none';
		  	}
		  }
    	} else {
    		cancel(update_id,update_count);
    	}
    }
    else {
        alert("Internal Server Error!");
    }
  }
}

function getResult_add(){
  if (req.readyState == 4){
    if (req.status == 200){
      document.getElementById('ajax_box').innerHTML=req.responseText;
    }
    else {
        document.getElementById('ajax_box').innerHTML="Internal Server Error!";
    }
  }
}

function check(form, name, mode){
	var sel=false;
	var i;
	if(form.elements[name].length!=undefined){
		for(i=0; i<form.elements[name].length; i++){
			if(form.elements[name][i].checked){
				sel=true;
				break;
			}
		}
	}else{
		sel=form.elements[name].checked;
	}
	if(sel){
		form.elements['a'].value=mode;
		var param_str='';
		for(i=0; i<params.length; i++){
		  	param_str+='&'+params[i]+'='+values[params[i]];
		}
		form.action+=param_str+'&start='+start+'&search='+search;
		form.submit();
	}else{
		alert('No records selected! Select the records you want to edit first.');
	}
}

function select_all(el){
	var i;
	var check;
	if(el.length!=undefined){
		if(el[0].checked)check=false;
		else check=true;
		for(i=0; i<el.length; i++)el[i].checked=check;
	}else{
		el.checked=!el.checked;
	}
}

function html_decode(value){
	var decoded;
	decoded=value.replace(/&lt;/gi,"<");
	decoded=decoded.replace(/&gt;/gi,">");
	decoded=decoded.replace(/&amp;/gi,"&");
	decoded=decoded.replace(/&quot;/gi,'"');
	return decoded;
}

function html_encode(value){
	var encoded;
	encoded=value.replace(/</gi,"&lt;");
	encoded=encoded.replace(/>/gi,"&gt;");
	encoded=encoded.replace(/&/gi,"&amp;");
	encoded=encoded.replace(/"/gi,"&quot;");
	return encoded;
}