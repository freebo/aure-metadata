#start stress process if not running, else stop stress process

if  pgrep stress >/dev/null 2>&1; then
	pkill stress >/dev/null 2>&1;
	else
		stress -c 1&
       	fi