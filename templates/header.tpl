<div id="header">
	<div id="logo"><img alt="{message name="logo-alt"}"  src="{$cWebPath}/images/bflogo.png" /></div>
	<div id="login">
		<form action="" method="post">
			<div id="loginparts">
				<label>
					{message name="login-email"}
					<input name="lgEmail" type="text" />
				</label>
				<label>
					{message name="login-password"}
					<input name="lgPasswd" type="password"/>
				</label>
				<input type="submit" value="{message name="login-loginbutton"}"/>
			</div>
		</form>
		<div id="loginlinks">
			<ul>
				<li><a href="{$cScriptPath}/ForgotPassword">{message name="login-forgotpassword"}</a></li>
				<li><a href="{$cScriptPath}/Signup">{message name="login-signup"}</a></li>
			</ul>
		</div>
	</div>
</div>