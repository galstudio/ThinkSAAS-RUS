<?php
header ( "HTTP/1.1 404 Not Found" );
header ( "Status: 404 Not Found" );

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Страница, которую вы ищете, не существует!</title>
</head>

<body>
<h1>404</h1>
<p>Страница не найдена!</p>
</body>
</html>
';
