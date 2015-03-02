# Project `api.sayhey.cn ` 

an api for sayhey.cn

## Requirement
+ yaf.so    
  | - php 5.2+ 5.3+recommended   
  | - mongodb
  | - mysql  

## Deploy

### Comliple Yaf In Linux
	$PHP_BIN/phpize
    ./configure --with-php-config=$PHP_BIN/php-config
    make
    make install

### Configure Yaf In php.ini
	yaf.use_namespace=	On

### Rewrite Rules

#### Apache
	#.htaccess
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule .* index.php

#### Nginx
	server {
	  listen ****;
	  server_name  get.appvv.com;
	  root   /data0/www/get-appvv-com/public;
	  index  index.php index.html index.phtml;

      location ~* .*\.(php|php5)?$ {
      fastcgi_pass  127.0.0.1:9000;
      fastcgi_index index.php;
      include fcgi.conf;
      }

	 
	  if (!-e $request_filename) {
	    rewrite ^/(.*)  /index.php/$1 last;
	  }
	}

#### Lighttpd
	$HTTP["host"] =~ "(www.)?domain.com$" {
	  url.rewrite = (
	     "^/(.+)/?$"  => "/index.php/$1",
	  )
	}

## Usage

### Jsonp Http Request
	请求地址
	http://api.dev/app/getRecommended/id/22  

	+ Original Javascript   

	<script src="http://api.dev/app/getRecommended/id/22?access_token=test&format=jsonp&callback=hello"></script>
	<script>
		function hello(data) {
			alert(JSON.stringify(data));
		}
	</script>
    
	+ Jquery Ajax
	$.ajax({
		type: 'GET',
		url: "http://api.dev/app/getRecommended/id/22",
		dataType: 'jsonp',
		data: {access_token:'test',format:'jsonp'},
		success: function(data){$('.content').append(JSON.stringify(data));}     //or hello
	});
	
	$.ajax({
		type: 'GET',
		url: "http://api.dev/app/getRecommended/id/22",
		dataType: 'jsonp',
		data: {access_token:'test',format:'jsonp',callback:'hello'},
	});
	
	$.ajax({
		type: 'GET',
		url: "http://api.dev/app/getRecommended/id/22",
		dataType: 'jsonp',
		data: {access_token:'test',format:'jsonp'},
	}).done(hello).fail(function(data){alert(JSON.stringify(data));});
	
	
	
	+ Jquery $.get
	
	$.get('http://api.dev/app/getRecommended/id/22',{access_token:'test',format:'jsonp',callback:'hello'},'jsonp');
	
	$.get('http://api.dev/app/getRecommended/id/22',{access_token:'test',format:'jsonp'},hello,'jsonp');

	$.get('http://api.dev/app/getRecommended/id/22',{access_token:'test',format:'jsonp'},function(data){$('.content').append('internal');},'jsonp');	
	



### Json Http Request
	请求地址
	e.g.: http://api.sayhey.cn/ring/ring/search/s/hello?format=json	

### Xml Http Request
	请求地址
	e.g.: http://api.sayhey.cn/ring/ring/search/s/hello?format=xml

	

