# numizmatik.ru
<VirtualHost *:80>
<Location />
<Limit GET POST PUT>
order allow,deny
allow from all
deny from 193.232.110.5
</Limit>
</Location>

	DocumentRoot "/var/www/htdocs/numizmatik.ru"
	ServerName numizmatik.ru
	ServerAlias www.numizmatik.ru 
	ServerAlias news.numizmatik.ru
	ServerAlias biblio.numizmatik.ru
	ServerAlias shopcoins.numizmatik.ru
	ServerAlias tboard.numizmatik.ru
	ServerAlias blacklist.numizmatik.ru
	ServerAlias advertise.numizmatik.ru
	ServerAlias album.numizmatik.ru
	ServerAlias aukcion.numizmatik.ru
	ServerAlias rating.numizmatik.ru
	ServerAlias catalog.numizmatik.ru

	php_value memory_limit 128M
	AddDefaultCharset WINDOWS-1251
LimitRequestFieldSize 16380
#	CharsetDefault UTF8
#	CharsetSourceEnc CP1251

	ErrorLog /dev/null
	CustomLog logs/access-numizmatik.ru.log combined
	ErrorDocument 404 /404.php
	<Directory "/var/www/htdocs/numizmatik.ru">
		Options Includes MultiViews
		Order deny,allow
		Allow from all
	</Directory>
	<Directory "/var/www/htdocs/numizmatik.ru/new">
		Options Includes MultiViews FollowSymLinks
		Order deny,allow
		Allow from all
		AllowOverride all
	</Directory>
	<Location /mm1805/phpmyadmin/>
	    AuthName "Authorization"
	    AuthType Basic
	    AuthUserFile "/var/www/htdocs/numizmatik.ru/mm1805/phpmyadmin/.htpasswd"
	    Require valid-user
	  </Location>
	RewriteEngine on
	RewriteLog "/dev/null"

	RewriteRule ^/ocenka-stoimost-monet          /new/ocenka-stoimost-monet [L]
        RewriteRule ^/gde-prodat-monety          /new/gde-prodat-monety [L]
	RewriteCond %{HTTP_HOST}	^biblio\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/biblio$1 [R=301,L] 
	RewriteCond %{HTTP_HOST}	^shopcoins\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/shopcoins$1 [R=301,L]
	#RewriteCond %{HTTP_HOST}	^tboard\.numizmatik\.ru
	#RewriteRule ^(.*)$		http://www.numizmatik.ru/tboard$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^www\.numizmatik\.ru
	RewriteCond %{REQUEST_URI}	^/tboard/.*	
	RewriteRule ^/tboard/(.*)$		http://tboard.numizmatik.ru/$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^tboard\.numizmatik\.ru
	RewriteCond %{REQUEST_URI}	^/.*_n([0-9]*)_p([0-9]*)\.html
	RewriteRule ^.*_n([0-9]*)_p([0-9]*)\.html$	/tboard/read.php?tboard=$1&pagenumtopic=$2 [L]
	RewriteCond %{HTTP_HOST}	^tboard\.numizmatik\.ru
	RewriteRule ^(.*)$		/tboard/$1 [L]
	RewriteCond %{HTTP_HOST}	^blacklist\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/blacklist$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^advertise\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/advertise$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^album\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/album$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^aukcion\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/aukcion$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^rating\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/rating$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^catalog\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru/catalog$1 [R=301,L]
	RewriteCond %{HTTP_HOST}	^news\.numizmatik\.ru
	RewriteCond %{REQUEST_URI}	^/.*_n([0-9]*)\.html
	RewriteRule ^.*_n([0-9]*)\.html$	/news/read.php?news=$1 [L]
	RewriteCond %{HTTP_HOST}	^news\.numizmatik\.ru
	RewriteRule ^(.*)$		/news/$1 [L]
	RewriteCond %{HTTP_HOST}	!^www\.numizmatik\.ru
	RewriteRule ^(.*)$		http://www.numizmatik.ru$1 [R=301,L]
	RewriteCond %{REQUEST_URI}	^/biblio/.*_n([0-9]*)\.html
	RewriteRule ^/biblio/.*_n([0-9]*)\.html$	/biblio/read.php?biblio=$1 [L]
	RewriteCond %{REQUEST_URI}	^/russiancoins/.*_ny([0-9]*)-.*_nom([0-9]*)\.html
	RewriteRule ^/russiancoins/.*_ny([0-9]*)-.*_nom([0-9]*)\.html$ /russiancoins/index.php?RussianCoinsYear=$1&Value=$2 [L]
	RewriteCond %{REQUEST_URI}	^/russiancoins/.*_ny([0-9]*)-.*_ns([0-9]*)\.html
	RewriteRule ^/russiancoins/.*_ny([0-9]*)-.*_ns([0-9]*)\.html$ /russiancoins/index.php?RussianCoinsYear=$1&RussianCoinsGroup=$2 [L]
	RewriteCond %{REQUEST_URI}	^/russiancoins/.*_ny([0-9]*)\.html
	RewriteRule ^/russiancoins/.*_ny([0-9]*)\.html$ /russiancoins/index.php?RussianCoinsYear=$1 [L]
	RewriteCond %{REQUEST_URI}	^/russiancoins/.*_n([0-9]*)\.html
	RewriteRule ^/russiancoins/.*_n([0-9]*)\.html$	/russiancoins/show.php?catalog=$1 [L]
	RewriteCond %{REQUEST_URI}	^/blacklist/.*_n([0-9]*)_p([0-9]*)\.html
	RewriteRule ^/blacklist/.*_n([0-9]*)_p([0-9*])\.html$	/blacklist/read.php?blacklist=$1&pagenumtopic=$2 [L]
	RewriteCond %{REQUEST_URI}	^/price/.*_cpc([0-9]*)_cpr([0-9]*)_pcn([0-9]*)\.html
	RewriteRule ^/price/.*_cpc([0-9]*)_cpr([0-9]*)_pcn([0-9]*)\.html$  /price/index.php?page=show&catalog=$1&parent=$2&pcondition=$3 [L]
	RewriteCond %{REQUEST_URI}	^/detector/.*_n([0-9]*)_p([0-9]*)\.html
	RewriteRule ^/detector/.*_n([0-9]*)_p([0-9*])\.html$	/detector/read.php?detectorforum=$1&pagenumtopic=$2 [L]
	RewriteCond %{REQUEST_URI}	^/tboard/.*_n([0-9]*)_p([0-9]*)\.html
	RewriteRule ^/tboard/.*_n([0-9]*)_p([0-9]*)\.html$	/tboard/read.php?tboard=$1&pagenumtopic=$2 [L]
	RewriteCond %{REQUEST_URI}	^/weekquestion/.*_n([0-9]*)_p([0-9]*)_s([0-9]*)\.html
	RewriteRule ^/weekquestion/.*_n([0-9]*)_p([0-9]*)_s([0-9]*)\.html$	/weekquestion/index.php?weekquestion=$1&pageCount=$2&pageStart=$3 [L]
	RewriteCond %{REQUEST_URI}	^/catalognew/.*_m([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_m([0-9]*)\.html$ /catalognew/index.php?page=show&catalog=$1&materialtype=$2 [L] 
	RewriteCond %{REQUEST_URI}	^/place/.*\.html
	RewriteRule ^/place/(.*)\.html$ /place/index.php?name=$1\.html
	RewriteCond %{REQUEST_URI}	^/metro/.*\.html
	RewriteRule ^/metro/(.*)\.html$ /metro/index.php?name=$1\.html

RewriteCond %{QUERY_STRING}  ^pagenum=(.*)&materialtype=6
RewriteRule ^.*shopcoins/prodaza_cvetnih_monet.html$ /shopcoins/index.php?pagenum=%1&materialtype=6 [L]
RewriteCond %{REQUEST_URI} ^/shopcoins/prodaza_cvetnih_monet.html
RewriteRule ^.*prodaza_cvetnih_monet.html$ /shopcoins/index.php?materialtype=6 [L]

        RewriteCond %{QUERY_STRING}  	^materialtype=2&pagenum=(.*)
        RewriteRule ^.*catalognew/prodaza_banknot_i_bon.html$ /catalognew/index.php?pagenum=%1&materialtype=2 [L]
        RewriteCond %{REQUEST_URI}	^/catalognew/prodaza_banknot_i_bon.html
        RewriteRule ^.*prodaza_banknot_i_bon.\html$ /catalognew/index.php?materialtype=2 [L]

        RewriteCond %{QUERY_STRING}  ^pagenum=(.*)&materialtype=2
        RewriteRule ^.*shopcoins/prodaza_banknot_i_bon.html$ /shopcoins/index.php?pagenum=%1&materialtype=2 [L]
        RewriteCond %{REQUEST_URI}	^/shopcoins/prodaza_banknot_i_bon\.html
        RewriteRule ^.*prodaza_banknot_i_bon.\html$ /shopcoins/index.php?materialtype=2 [L]


	RewriteCond %{REQUEST_URI}	^/shopcoins/mobile/.*_m([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_m([0-9]*)\.html$ /shopcoins/mobile/index.php?page=show&catalog=$1&materialtype=$2 [L] 

	RewriteCond %{REQUEST_URI}	^/shopcoins/mobile/albom_dlya_monet\.html 
	RewriteRule ^/shopcoins/mobile/albom_dlya_monet\.html$ /shopcoins/mobile/index.php?group=816&materialtype=3 [L]
	RewriteCond %{REQUEST_URI}	^/shopcoins/mobile/.*_pp([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_pc([0-9]*)_m([0-9]*)_pp([0-9]*)\.html$ /shopcoins/mobile/index.php?page=show&catalog=$1&parent=$2&materialtype=$3&pagenumparent=$4 [L]
	RewriteCond %{REQUEST_URI}	^/catalognew/.*_d([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_d([0-9]*)\.html$ /catalognew/detectors.php?page=show&catalog=$1&materialtype=$2 [L] 
	RewriteCond %{REQUEST_URI}	^/auction/.*_a([0-9]*)_g([0-9]*)_p([0-9]*)_s([0-9]*)\.html
	RewriteRule ^.*_a([0-9]*)_g([0-9]*)_p([0-9]*)_s([0-9]*)\.html$ /auction/index.php?page=show&auction=$1&group=$2&pagenum=$3&sort=$4 [L]
	RewriteCond %{REQUEST_URI}	^/shopcoins/.*_m([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_m([0-9]*)\.html$ /shopcoins/index.php?page=show&catalog=$1&materialtype=$2 [L] 
	RewriteCond %{REQUEST_URI}	^/shopcoins/.*_pp([0-9]*)\.html
	RewriteRule ^.*_c([0-9]*)_pc([0-9]*)_m([0-9]*)_pp([0-9]*)\.html$ /shopcoins/index.php?page=show&catalog=$1&parent=$2&materialtype=$3&pagenumparent=$4 [L]
	RewriteCond %{REQUEST_URI}	^/shopcoins/albom_dlya_monet\.html 
	RewriteRule ^/shopcoins/albom_dlya_monet\.html$ /shopcoins/index.php?group=816&materialtype=3 [L]
	RewriteCond %{REQUEST_URI}	^/forum/.*
	RewriteRule ^((urllist|sitemap_).*\.(xml|txt)(\.gz)?)$/forum/ vbseo_sitemap/vbseo_getsitemap.php?sitemap=$1 [L]
	RewriteCond %{REQUEST_URI}	^/forum/.*
	RewriteCond %{REQUEST_URI} !(admincp/|modcp/|cron|vbseo_sitemap|api\.php)
	RewriteRule ^((archive/)?(.*\.php(/.*)?))$ /forum/vbseo.php [L,QSA]
	RewriteCond %{REQUEST_URI}	^/forum/.*
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !/(admincp|modcp|clientscript|cpstyles|images)/
	RewriteRule ^(.+)$ /forum/vbseo.php [L,QSA]
	RewriteCond %{THE_REQUEST}	^GET\ .*/index\.(php|html)\ HTTP
	RewriteRule ^(.*)index\.(php|html)$ $1 [R=301,L]

</VirtualHost>

