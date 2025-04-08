@echo off
php "%~dp0commit-msg.php" %*
exit /b %ERRORLEVEL%