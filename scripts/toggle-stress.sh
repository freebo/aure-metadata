#start stress process if not running, else stop stress process

if  pgrep stress ; then
	pkill stress ;
	else
		stress -c 1&
       	fi