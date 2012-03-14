function checkMail(email)
{ 	
      if(email=="")
      {
          alert("Please enter your email address.");
	 	  return false;
      }
      
      if(email.indexOf("@")<1||email.indexOf(".")<3)
      {
        	 alert("Please enter a valid email address.");
	 		return false;
      }

      return true;
}

function confirmAction(frm)
{
   if(confirm("Are you sure?"))
   {
      frm.submit();
   }
   return true;
}

function centerWin(filename,width,height,name)
{
   var x=(screen.availWidth-width)/2;
   var y=(screen.availHeight-height)/2-50;

   if(name="") name='win'

   newWin=window.open(filename, name,"width=10,height=10, scrollbars=yes, resizable=yes");
   newWin.moveTo(x,y);
   newWin.resizeTo(width,height);
}


function confirmDelete(frm)
{	
	if(confirm("Are you sure"))
	{
		frm.del.value=1;
		frm.submit();
	}
}

function findCoords(e) {
   var posX = 0, posY = 0;
   if( !e ) { e = window.event; } if( !e ) { return [ 0, 0 ]; }
   if( typeof( e.pageX ) == 'number' ) {
      posX = e.pageX; posY = e.pageY;
   } else {
      if( typeof( e.clientX ) == 'number' ) {
         posX = e.clientX; posY = e.clientY;
         if( document.body && (!( window.opera || window.debug || navigator.vendor == 'KDE')) ) 
         {
            if( typeof( document.body.scrollTop ) == 'number' ) {
               posX += document.body.scrollLeft; posY += document.body.scrollTop;
            }
         }
         if( document.documentElement && !( window.opera || window.debug || navigator.vendor == 'KDE' ) ) {
            if( typeof( document.documentElement.scrollTop ) == 'number' ) {
               posX += document.documentElement.scrollLeft; posY += document.documentElement.scrollTop;
            }
         }
      }
   }
   return [ posX, posY ];
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}


function displayElt(eid,w,h)
{   
	if(w==null) w=250;
	if(h==null) h=200;

    elt=document.getElementById(eid);
	
   var scrolls= getScrollXY();	
    var centerY=scrolls[1] + (screen.height/4);
    var centerX=scrolls[0] + (screen.width/4);	
	
    elt.style.top=(centerY-(h/2))+"px";
    elt.style.left=(centerX+(w/2))+"px";
	elt.style.display='block';

}


function displayElt2(evt,eid)
{   
    elt=document.getElementById(eid);
           
    var scrolls= getScrollXY();
                   var centerY=scrolls[1] + (screen.height/4);
    var centerX=scrolls[0] + (screen.width/4);
    
	x=(elt.style.width).slice(0,-2);
	y=(elt.style.height).slice(0,-2);
	
    elt.style.top=(centerY+(y/2))+"px";
    elt.style.left=(centerX+(x/2))+"px";
	 elt.style.display='block';
}

function displayElt1(evt,eid)
{
	var b = document.body;
	
	if(evt.x>0)
	{
		var x=evt.x;
		var y=evt.y +  b.scrollTop; 
	}
	else
	{		
		var x=evt.clientX;
		var y=evt.clientY;
	}
	
	elt=document.getElementById(eid);
	
	var wid=parseInt(elt.style.width);  	
	var hei=parseInt(elt.style.height);  	
	if(typeof(wid)=='undefined') var wid=200; 
	if(typeof(hei)=='undefined') var hei=200;
	  
	if(y > ((screen.height/2) + hei) )
	{
	 	elt.style.top=y+"px";
    }
	else
	{	
		var posY=y-hei;		
   		elt.style.top=posY+"px";
	}
	
	
	if(x < ((screen.width/2) + wid))
	{		
	    elt.style.left=x+"px";
    }
	else
	{	
		var posX=x-wid;		
	    elt.style.left=posX+"px";  
    }
    
	elt.style.display='block';
}


function hideElt(elt)
{
	elt.style.display='none';
}

function hideElt1(eid)
{
	elt=document.getElementById(eid);
	elt.style.display='none';
}


function ajax_call(url,form_id,response_id,callback)
{
	var frm=$(form_id);
	var params=Form.serialize(frm);
	
	new Ajax.Request(url,
  {
    method:'post',
    parameters: {ajax: '1', request: escape(params) },
    onSuccess: function(transport)
    {
	    var response = transport.responseText || "no response text";  
	    
        if(callback=='') 
        {
	        $(resonse_id).innerHTML=response;	      	      	      	      
	        transport.responseText.evalScripts();
        }
        else
        {
	        //run callback function 
	        eval(callback+"(\"" + response + "\") ");
        }
    },
    onFailure: function()
    { 
	    alert('Something went wrong...') 
	}
  }); 
}


function popupdetails(obj)
    {
        if(obj==true)
        {
//            document.getElementById(popup).style.display=''; 
document.getElementById('divDisable').style.display='';
//$('#divDisable').fadeIn(500,0.5);
            document.getElementById('divDisable').style.height = document.body.scrollHeight + "px";
            document.getElementById('divDisable').style.width = document.body.scrollWidth + "px";
        }
        else
        {
           document.getElementById('divDisable').style.display='none';
   //document.getElementById(popup).style.display='none';  
  //$('#divDisable').fadeOut(1500,0.5);
        }
        return false;
    }

function showHideMenuNode(node_id,image_id){

   ship = document.getElementById(node_id);
   img = document.getElementById(image_id);
   if(ship){
         //alert(node_id);
       ship.style.display = ship.style.display == "none" ? "block" : "none";
       if(img)
       {
           img.src = ship.style.display == "none" ? "images/plus.gif" : "images/minus.gif";
       }
   }
}

//select all none checkbox
function SetAllCheckBoxes(form, chkbox, CheckValue)
{
	if(!document.forms[form])
	    return;

    var objCheckBoxes = document.forms[form].elements;
				
    if(!objCheckBoxes)
		return;

	var countCheckBoxes = objCheckBoxes.length;
	//alert(countCheckBoxes);

	if(!countCheckBoxes)
	   {
		objCheckBoxes.checked = CheckValue;
	   }
	else
	  {
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
		 objCheckBoxes[i].checked = CheckValue;
	  }
}

function returnSerialized(form)
{
	var form =document.forms[form];
	var els = form.elements;
	var len=els.length;
	var ar = new Array();
	var j =0;
 	for(i=0; i<len; i++)
	if(els[i].type == "checkbox" && els[i].name == "ids[]" &&  els[i].checked==true )
 	ar[j++] = els[i].value;
    var arr_csv ="";
	if(ar.length >0){
   	arr_csv= ar[0];
	for(i=0; i < ar.length; i++)
	arr_csv=arr_csv+","+ar[i];
	}
    return arr_csv;
 }
 
function centerElt(eid,w,h)
{  
    var scrolls= getScrollXY();  

    elt=document.getElementById(eid);        
    var centerY= Math.floor(Math.round(screen.height/2));
    var centerX= Math.floor(Math.round(screen.width/2));
   
   centerY+=scrolls[1];
   
    elt.style.top=(centerY-Math.floor(Math.round(h/2))-100)+"px";
    elt.style.left=(centerX-Math.floor(Math.round(w/2)))+"px";      
   
   elt.style.zIndex=2;
    elt.style.display='block';   
}

function nl2br (str, is_xhtml) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Philip Peterson
    // +   improved by: Onno Marsman
    // +   improved by: Atli Þór
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Maximusya
    // *     example 1: nl2br('Kevin\nvan\nZonneveld');
    // *     returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
    // *     example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
    // *     returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
    // *     example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
    // *     returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';

    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}