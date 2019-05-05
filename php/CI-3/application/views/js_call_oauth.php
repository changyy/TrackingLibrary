<html>
	<head>
	</head>
	<body>
		<script>
			function getCookie(objName){
				var arrStr = document.cookie.split("; ");
				for(var i = 0;i < arrStr.length;i ++){
					var temp = arrStr[i].split("=");
					if(temp[0] == objName)
						return unescape(temp[1]);
				}
				return '';
			}

			function setCookie(objName, objValue, expiresSec){
				var expiresTime;
				if(!expiresSec) {
					var d = new Date();
					d.setTime(d.getTime() + (1*24*60*60*1000));
					expiresTime = "expires="+ d.toUTCString();
				} else {
					var d = new Date();
					d.setTime(d.getTime() + (expiresSec*1000));
					expiresTime = "expires="+ d.toUTCString();
				}
				document.cookie = objName+'='+objValue+'; expires='+expiresTime+'; path=/';
			}

			function sign_in() {
				gapi.auth.authorize( {
					client_id: '<?php echo $client_id;?>',
					scope: [
						'https://www.googleapis.com/auth/analytics.readonly',
						'https://www.googleapis.com/auth/adsense.readonly'
					],
					immediate: false,
				}, function(ret){
					if (!ret.error) {
						//console.log(ret);
						if (ret.access_token && ret.expires_in) {
							//console.log('pass');
							setCookie('ga_access_token', ret.access_token, ret.expires_in);
							location.href = <?php echo $callback_url;?>;
						}
					}
				});
			}
		</script>
		<script src="https://apis.google.com/js/client.js?onload=sign_in"></script>
	</body>
</html>		
