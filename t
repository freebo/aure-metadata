public_ip=`az network public-ip show -g $RG -n vmssDemoLBPublicIP --query "{ address: ipAddress}" |jq -r ".address"`
az network dns record-set a add-record -g mikejfreeman-rg -z mikejfreeman.com -n vmss -a $public_ip
