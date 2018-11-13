#Update names as required

RG=vmssDemoRg
NAME=vmssDemo


#Resource Group
az group create --name $RG --location southeastasia

#Scale Set
az vmss create -n $NAME -g $RG --vm-sku Standard_F1s \
        --image Canonical:UbuntuServer:16.04-LTS:latest \
        --location southeastasia \
        --public-ip-address-dns-name `echo ${NAME}ip | tr '[:upper:]' '[:lower:]'` \
        --public-ip-per-vm --vm-domain-name `echo ${NAME}vm | tr '[:upper:]' '[:lower:]'`

#Load Balancer Probe
az network lb probe create -g $RG --lb-name ${NAME}lb -n ${NAME}probe --protocol Http --port 80 --path /

#Load Balancer Rule
az network lb rule create -g $RG --lb-name ${NAME}lb -n ${NAME}rule --protocol Tcp \
        --frontend-port 80 --backend-port 80 --frontend-ip-name loadBalancerFrontEnd --backend-pool-name ${NAME}LBBEPool --probe-name ${NAME}probe

#VMSS extension which loads the code onto the VM
az vmss extension set --publisher Microsoft.Azure.Extensions  \
       --version 2.0 --name CustomScript --vmss-name $NAME  -g $RG  \
       --settings '{"fileUris":["https://raw.githubusercontent.com/freebo/azure-metadata/master/deploy.sh"],"commandToExecute":"sh deploy.sh"}'

#Roll out extension
az vmss update-instances --instance-ids "*" -n vmssDemo -g vmssDemoRg