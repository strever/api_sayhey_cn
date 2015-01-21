	$(function(){
		//新增小圆点的动画效果
		$('.sicon').animate({opacity:1},2000);

		var nav_lang = $('.nav-lang');
		var nav_l =$('.nav-l');
		var nav_r =$('.nav-r');
		var s_ipt = $('.s-ipt');
		
		var search = $('.search');
		s_ipt.focus(function(){
			$(this).removeClass('s-text');
		});
		s_ipt.blur(function() {
			var ipt_v = s_ipt.val();
			if(ipt_v == ''){
				$(this).addClass('s-text');
			}
		});
		var lang = $('.lang');

		lang.click(function(){
			nav_lang.slideToggle(400,function(){
			});
		});


		ipad_css();
		tips();

		//law页面切换
		$('#law-cus').on('click',function(){
			$('.c-us').show();
			$('.law-con,.c-privacy,.c-safety').hide();
			$('.f-law').addClass('b-fix');
			$(this).addClass('active').siblings().removeClass('active');
		});
		$('#law-law').on('click',function(){
			$('.c-us,.c-privacy,.c-safety').hide();
			$('.law-con').show();
			$('.f-law').removeClass('b-fix');
			$(this).addClass('active').siblings().removeClass('active');
		});	
		$('#law-privacy').on('click',function(){
			$('.c-us,.law-con,.c-safety').hide();
			$('.c-privacy').show();
			$('.f-law').removeClass('b-fix');
			$(this).addClass('active').siblings().removeClass('active');
		});
		$('#law-safety').on('click',function(){
			$('.c-us,.law-con,.c-privacy').hide();
			$('.c-safety').show();
			$('.f-law').removeClass('b-fix');
			$(this).addClass('active').siblings().removeClass('active');
		});
		// 搜索部分代码
	    var $searchInput = $("#search-input")
	     , searchUrl = {
	          app : "http://apple.vshare.com/search-1/"
	       }
	     , searchEnter = function () {
	        $v = encodeURI($searchInput.val())
	        if ( $v == ""){ return false}
	        window.open(searchUrl.app + $v + '/');
	        
	    }
	    $("#enter").on('click',function() {
	        searchEnter();
	    });
	     $searchInput.on({
	        keydown: function () {
	            if (event.keyCode === 13) {
	                searchEnter();
	                return false;
	            }
	        }
	        , focus: function () {
	            $(this).addClass('focus')
	        }
	        , blur: function () {
	            var $that = $(this)
	            $that.val() == "" && $that.removeClass('focus')
	        }
	   }) 		
	})
	function jb_redirect_device(){
		var lang=navigator.userLanguage||navigator.language;
		var $C=location.hash;
		if (navigator.platform.indexOf('iPhone') != - 1 && $C!="#pc"){
			window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=ios&language=en');
		}else if (navigator.platform.indexOf('iPad') != - 1 && $C!="#pc"){
		    window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=ios&language=en');
		}
	}
	function nojb_redirect_devices(){
		var lang=navigator.userLanguage||navigator.language;
		var $C=location.hash;
		if (navigator.platform.indexOf('iPhone') != - 1 && $C!="#pc"){
			window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=iosOnJB&language=en');
		}else if (navigator.platform.indexOf('iPad') != - 1 && $C!="#pc"){
		    window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=iosOnJB&language=en');
		}
	}
	function nojb_redirect_device(){
		var lang=navigator.userLanguage||navigator.language;
		var $C=location.hash;
		if (navigator.platform.indexOf('iPhone') != - 1 && $C!="#pc"){
			window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=iosNoJB&language=en');
		}else if (navigator.platform.indexOf('iPad') != - 1 && $C!="#pc"){
		    window.location.href='itms-services://?action=download-manifest&url=' + encodeURIComponent('https://ssl-api.appvv.com/update.plist?type=iosNoJB&language=en');
		}
	}
	function redirect_device(){
			window.location.href="http://vvdl.appvv.com//vshareInstaller/download/vshareInstaller.exe";	

	}		
	function ipad_css(){
		var lang=navigator.userLanguage||navigator.language;
		var $C=location.hash;
		 if (navigator.platform.indexOf('iPad') != - 1 && $C!="#pc"){
			// alert($('.ipad-btn').length);
			$('.pc-btn,.code').hide();
			$('.ipad-btn').show();
			$('.down').addClass('pl40');
		}	
	}
	function tips(){
		$('.tips').show();
		$('.shadow').show();
		$('.close').show();
		$('.close').on('click',function(){
			$('.tips').hide();
			$('.shadow').hide();
			$('.close').hide();
		})
	}		