<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Test</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name = "description" content = "" />
  <meta name="document-state" content="dynamic"/>
  <meta name="robots" content="index,follow"/>
<style>
	#usersField {
		text-align: center;
		margin-top: 100px;
	}
	#ansver {
		color: red;
		font-size: 15px;
	}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$('body').on('click', '#usersField  input[type="button"]', function(){
		var name=$('#usersField  input[name="name"]').val();
		var surName=$('#usersField  input[name="surName"]').val();
		var email=$('#usersField  input[name="email"]').val();
		var pass=$('#usersField  input[name="pass"]').val();
		var repeatPass=$('#usersField  input[name="repeatPass"]').val();
		if (pass == repeatPass && pass != "") {
			$.post('userEntryAndVerification.php', {name: name, surName: surName,  email: email, pass: pass, repeatPass: repeatPass},
							function(data){ 
								result = JSON.parse(data);
								if (result.action == 'verification') {
									$('#ansver').html(result.resp['name'] + '<br>' + result.resp['surName'] + '<br>' + result.resp['email'] + '<br>' + result.resp['pass']);
								}
								if (result.action == 'entry') {
									if (!result.success) $('#ansver').html(result.resp['text']);
									if (result.success) $('#usersField').html('ВЫ ЗАРИГЕСТРИРОВАЛИСЬ!');
								}
							});
			
		} else {
			$('#ansver').html('Вы не ввели пароль или пароль не совпадант с его повторением');
			$('#usersField  input[name="pass"], #usersField  input[name="repeatPass"]').css("border-color", "red")
		}
		return false;
	})
	
})
</script>
</head>
<body>
	<div id="usersField" class="fieldPoint">
		<form  name="form" action="userEntryAndVerification.php" method="post">
			<p><input type="text" name="name" placeholder="Имя"></p>
			<p><input type="text" name="surName" placeholder="Фамилия"></p>
			<p><input type="text" name="email" placeholder="E-Mail"></p>
			<p><input type="password" name="pass" placeholder="Пароль"></p>
			<p><input type="password" name="repeatPass" placeholder="Повторите пароль"></p>
			<p><input type="button" name="button" value="Отправить"></p>
			<p id="ansver"></p>
		</form> 
	</div>
</body>
</html>