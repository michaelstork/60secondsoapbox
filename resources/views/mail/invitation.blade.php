<!DOCTYPE HTML>
<html>
	<head>
		<style>
			body {
				background-color:#EEEEEE;
				font-family:'Arial', sans-serif;
			}
			table {
				margin:0 auto;
				width:90%;
				background-color:#EFEFEF;
				max-width:600px;
				overflow:hidden;
				border-collapse:collapse;
			}
			h1 {
				text-align:center;
				font-size:18px;
				color:#EFEFEF;
				font-weight:bold;
				line-height:36px;
				white-space:nowrap;
				height:36px;
				letter-spacing:0.5px;
				margin:0;
				width:100%;
				background-color:#00BCD4;
			}
			p {
				font-size:13px;
				color:#333333;
				margin-bottom:32px;
				line-height:1.6em;
				text-align:center;
			}
			p.intro {
				font-size:16px;
				line-height:1.4em;
				font-weight:bold;
				margin:32px auto 0 auto;
			}
			p.sub-intro {
				font-size:12px;
				margin-top:0;
			}
			a {
				color:#00BCD4;
				white-space:nowrap;
			}
			a:visited {
				color:#00BCD4;
			}
			button {
				background-color:#00BCD4;
				border-radius:8px;
				overflow:hidden;
				outline:none;
				border:none;
				margin:48px auto 0 auto;
				display:block;
				position:relative;
				height:36px;
				line-height:32px;
				width:120px;
			}
			button.reject {
				background-color:#F44336;
				margin:12px auto 48px auto;
			}
			button a {
				display:block;
				position:absolute;
				top:0;
				bottom:0;
				left:0;
				right:0;
				text-decoration:none;
				white-space:nowrap;
				color:#EFEFEF;
				text-align:center;
				font-size:16px;
			}
			button a:visited {
				color:#EFEFEF;
			}
			p.instructions {
				line-height:1.8em;
			}
			td {
				padding:0 18px;
			}
			p span,
			p span.im {
				color:#333333 !important;
				font-size:13px !important;
			}
			.instructions a.code {
				text-decoration:underline;
				color:#00BCD4;
				font-weight:bold;
				font-size:24px !important;
				margin-top:12px;
				display:inline-block;
			}
		</style>
	</head>
	<body bgcolor="#EEEEEF" style="background-color:#EEEEEF">
		<table>
			<tr>
				<td height="48"></td>
			</tr>
			<tr>
				<td bgcolor="#00BDC4">
					<h1>60 Second Soapbox</h1>
				</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFE">
					<p class="intro">You've been nominated to record an episode of <a style="color:#00BCD4 !important;" href="https://www.aliem.com/category/clinical/60-second-soapbox/">60 Second Soapbox!</a></p>

					<p class="sub-intro">by {{ $nominator->name }} ({{ $nominator->email }})</p>

					<p>{!! $content !!}</p>

					<p class="instructions">If you'd like to participate, click the button below and enter the following invitation code:<br><a href="https://60secondsoapbox.io" target="_blank" class="code">{{ $code }}</a></p>
				</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFE">
					<button bgcolor="#00BCD4"><a style="color:#FFFFFE !important;" href="https://60secondsoapbox.io">Get Started</a></button>
					<button bgcolor="#F44336" class="reject"><a style="color:#FFFFFE !important;" target="_blank" href="https://60secondsoapbox.io/no-thanks/{{ $nominee->id }}">No Thanks</a></button>
				</td>
			</tr>
			<tr>
				<td height="48"></td>
			</tr>
		</table>
	</body>
</html>