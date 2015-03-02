function iphone_jbdown(){
	window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=ios&language=en');
}
function iphone_nojbdown(){
	window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=iosNoJB&language=en');
}
function close_shadow(){
	$('.tips-shadow,.tips').hide();
	setCookie("vshare_close_cookie", "ok"); 
}
function go_two_page(){
	var win_width = $(window).width();
	$('#p-ul-top').animate({left:'-'+win_width+'px'},400,'linear');
}

function setCookie(name, value) { 
	var Days = 30; 
	var exp = new Date(); 
	exp.setTime(exp.getTime() + Days*24*60*60*1000); 
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 
function getCookie(name) { 
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
	if(arr=document.cookie.match(reg))
 
		return unescape(arr[2]); 
	else 
		return null; 
} 
function delCookie(name) { 
	var exp = new Date(); 
	exp.setTime(exp.getTime() - 1); 
	var cval=getCookie(name); 
	if(cval!=null) 
		document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
} 
var user_cookie = getCookie("vshare_close_cookie");
getCookie("vshare_close_cookie");
if( !user_cookie || user_cookie != ''){
	$('.tips-shadow,.tips').show();			
}
$(function(){
	var nav_r = $('.nav-r');
	var nav_l = $('.nav-l');
	var nav_a = $('.nav-a');
	var langs = $('.top_list');
	var xia = $('.icon-xiangxia');
	var i = 1;
	nav_l.tap(function(){
		nav_a.addClass('cblue');
	});
	nav_r.tap(function(){
		i++;
		if(i%2==0){
			$(this).addClass('cblue');
			langs.show();	
		}else{
			$(this).removeClass('cblue');
			langs.hide();				
		}     	
	});
});

function togo(){
	var win_width = $(window).width();
	var $p_ul = $('#p-ul');
	var left_now = parseInt($p_ul.css('left'));
	var ul_img = $('#p-ul').find('li');
	var img_length = ul_img.length;
	var max_now = img_length * win_width - win_width;
	if( left_now == '-'+max_now ){
		$p_ul.animate({left:'0px'},0,'linear');
	} else {
		$p_ul.animate({left:left_now-win_width+'px'},400,'linear');
	}
}


$(function(){
	pic_slide();
	pic_slide2();
	$(window).resize(function() {
		//window.location.href=this.location;
	})
	
	function pic_slide(){
		var top_height = $('.top').height();
		var btn_height = $('.btn-list').height();
		var c_h = btn_height+top_height;
		var p_ul = $('#p-ul');
		var ul_img = $('#p-ul').find('li');
		var f_img = p_ul.find('li');
		var win_width = $(window).width();
		var win_height = $(window).height();
		var img_width = f_img.width();
		var img_height = f_img.height();
		var img_length = ul_img.length;
		ul_img.css({"width":win_width+'px','height':win_height-c_h+'px'} );
		$('.list-2').css({"width":win_width+"px","height":win_height-c_h+'px'});
		$('.list-2 ul').css({"width":img_length*win_width+"px",'overflow':'hidden'});
		$('#p-ul').swipeLeft(function(){		        	
			var img_length = ul_img.length;     	
			var img_width = ul_img.width();
			var fin_left = -img_width*(img_length-1);
			var now_left = parseInt($(this).css('left'));
			var new_left = now_left-img_width;        	
			if(new_left > fin_left){
				$(this).animate({left:new_left},400,'linear');
			}else{
				$(this).animate({left:fin_left},400,'linear');
			}	        		        	
		});
		$('#p-ul').swipeRight(function(){
			var img_length = ul_img.length;	        	
			var img_width = ul_img.width();
			var fin_left = 0;
			var now_left = parseInt($(this).css('left'));
			var new_left = now_left+img_width;	        	
			if(new_left < fin_left){
				$(this).animate({left:new_left},400,'linear');
			}else{
				$(this).animate({left:fin_left},400,'linear');
			}	        		        	
		});
		//window.setInterval(togo, 3000);
	}	   
	
	function pic_slide2(){
		var p_ul = $('#p-ul-top');
		var win_width = $(window).width();
		var win_height = $(window).height();
		var ul_li = $('#p-ul-top').find('li');
		var ul_img = ul_li;
		var img_length = 2;
		ul_li.css({"width":win_width+'px','height':win_height+'px'} );
		ul_img.css({"width":win_width+'px'} );
		$('.list-1').css({"width":win_width+"px","height":win_height+'px'});
		$('.list-1 ul').css({"width":img_length*win_width+"px",'overflow':'hidden'});
		$('#p-ul-top').swipeLeft(function(){		        	
			var img_length = ul_img.length;     	
			var img_width = ul_img.width();
			var fin_left = -img_width*(img_length-1);
			var now_left = parseInt($(this).css('left'));
			var new_left = now_left-img_width;        	
			if(new_left > fin_left){
				$(this).animate({left:new_left},300,'linear');
			}else{
				$(this).animate({left:fin_left},300,'linear');
			}	        		        	
		});
		$('#p-ul-top').swipeRight(function(){
			var img_length = ul_img.length;	        	
			var img_width = ul_img.width();
			var fin_left = 0;
			var now_left = parseInt($(this).css('left'));
			var new_left = now_left+img_width;	        	
			if(new_left < fin_left){
				$(this).animate({left:new_left},300,'linear');
			}else{
				$(this).animate({left:fin_left},300,'linear');
			}	        		        	
		});	        	
	}	        
	
});		