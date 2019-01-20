
export RG=vmssDemoRg
export NAME=vmssDemo

az monitor autoscale create \
  --resource-group $RG \
  --resource vmssDemo \
  --resource-type Microsoft.Compute/virtualMachineScaleSets \
  --name autoscale \
  --min-count 2 \
  --max-count 10 \
  --count 2

  az monitor autoscale rule create \
  --resource-group $RG \
  --autoscale-name autoscale \
  --condition "Percentage CPU > 70 avg 5m" \
  --scale out 3

  az monitor autoscale rule create \
  --resource-group $RG \
  --autoscale-name autoscale \
  --condition "Percentage CPU < 30 avg 5m" \
  --scale in 1