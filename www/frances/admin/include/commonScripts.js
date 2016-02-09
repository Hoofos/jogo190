<!-- 
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function HabilitaFinal() {
	
	objCampo = document.getElementById('situacao_final') ;
	
	if (objCampo.checked) {
		document.frm.TaProblematicaBoa.disabled = true ;
		document.frm.TaProblematicaMedia.disabled = true ;
		document.frm.TaProblematicaRuim.disabled = true ;
		document.frm.TaFinal.disabled = false ;
	}
	else {
		document.frm.TaFinal.disabled = true ;
		document.frm.TaProblematicaBoa.disabled = false ;
		document.frm.TaProblematicaMedia.disabled = false ;
		document.frm.TaProblematicaRuim.disabled = false ;
	}
}

function mostraErro(Erro) {
	if (Erro=="1") {
		msg = "OS CAMPOS MARCADOS COM * SÃO OBRIGATÓRIOS !!!";
	}
	else if (Erro=="2") {
		msg = "DATA INVÁLIDA !!!";
	}
	else if (Erro=="3") {
		msg = "CONFIRMAÇÃO DE SENHA NÃO CONFERE !!!";
	}
	else if (Erro=="4") {
		msg = "É NECESSÁRIO LER E ACEITAR O REGULAMENTO !!!";
	}
	else if (Erro=="5") {
		msg = "SOMENTE SERÃO ACEITOS ARQUIVOS MP3 PARA MÚSICAS !!!";
	}
	else if (Erro=="6") {
		msg = "SOMENTE SERÃO ACEITOS ARQUIVOS JPG OU GIF PARA IMAGEM !!!";
	}
	else if (Erro=="7") {
		msg = "SOMENTE SERÃO ACEITOS ARQUIVOS WMV PARA CLIPE !!!";
	}
	
  if ((obj=MM_findObj('divTextoErro'))!=null) with (obj) {
    if (document.layers) {
			document.write(unescape(msg));
			document.close();
	}
    else
		innerHTML = unescape(msg);
	}
	
	MM_showHideLayers('divForm','','hide');
	MM_showHideLayers('divErro','','show');
	window.scroll(0,0);
}

function checkrequired(which) {
	objForm = MM_findObj(which);
	var pass=true;
	if (document.images) {
		for (i=0;i<objForm.length;i++) {
			var tempobj=objForm.elements[i];
			
			//VERIFICA SE O CAMPO É OBRIGATÓRIO
			if (tempobj.name.substring(0,2)=="ob") {
				
				//VERIFICA SE O CAMPO ESTÁ EM BRANCO
				if (((tempobj.type=="text"||tempobj.type=="textarea"||tempobj.type=="password"||tempobj.type=="file")&&tempobj.value=='')||(tempobj.type.toString().charAt(0)=="s" && tempobj.selectedIndex==0)) {
					Erro=1;
					pass=false;
					break;
       		}
				
				
			}
		}
	}

	if (!pass) {
		mostraErro(Erro);
	}
	else {
		objForm.submit();
	}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->