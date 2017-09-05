# azure-metadata, Inspired by alphamusk's AWS metatdata page

![alt text](https://raw.githubusercontent.com/username/projectname/branch/path/to/img.png)

#To create vm image (ubuntu)
apt-get install -y git apache2 php7.0 libapache2-mod-php7.0 stress

cd /var/www/html && git clone http://github.com/freebo/azure-metadata

cronjob="*/2 * * * * cd /var/www/html && git pull https://github.com/freebo/azure-metadata.git > /dev/null 2>&1" (crontab -u root -l; echo "${cronjob}" ) | crontab -u ${root} - crontab -l



*To create the demo vmss from image...
# First the Resource group

RG=vmssDemo1Rg
NAME=vmssDemo1

az group create --name $RG --location southeastasia

#Then the Scale set (also creates the LB)

az vmss create -n $NAME -g $RG --vm-sku Standard_F1s --public-ip-address-dns-name `echo ${NAME}ip | tr '[:upper:]' '[:lower:]'` --image /subscriptions/f32027f3-4939-4286-8da7-6aa9a8cd5258/resourceGroups/images-rg/providers/Microsoft.Compute/images/Metadata-ub16v092 --public-ip-per-vm --vm-domain-name `echo ${NAME}vm | tr '[:upper:]' '[:lower:]'`

#Enable Autoscale and Scaling rules on the vmss (only by console or ARM Template, see autoscale.json)

#Then the health probe

az network lb probe create -g $RG --lb-name ${NAME}lb -n ${NAME}probe --protocol Http --port 80 --path /

#Then the LB rule

az network lb rule create -g $RG --lb-name ${NAME}lb -n ${NAME}rule --protocol Tcp --frontend-port 80 --backend-port 80 --frontend-ip-name loadBalancerFrontEnd --backend-pool-name ${NAME}LBBEPool

#show the puiblic ip of the LB 
az network lb frontend-ip show -g $RG --lb-name ${NAME}lb

#Add extension

az vmss extension set --publisher Microsoft.Azure.Extensions  \
       --version 2.0 --name CustomScript --vmss-name vmssDemo -vm -g vmssDemo-rg  \
       --settings '{"fileUris":["https://raw.githubusercontent.com/freebo/azure-metadata/master/test.sh"],"commandToExecute":"sh test.sh"}'
