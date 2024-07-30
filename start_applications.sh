#!/bin/bash

# Start customer-portal-fe
echo "Starting customer-portal-fe..."
cd customer-portal-fe
npm start &

# Start customer-portal-be
echo "Starting customer-portal-be..."
cd ../customer-portal-be
rm -f bootstrap/cache/*.php
docker-compose up -d
docker exec Customer_Portal php artisan migrate:refresh --seed

# Start admin-portal-fe
echo "Starting admin-portal-fe..."
cd ../admin-portal-fe
npm start &

# Start admin-portal
echo "Starting admin-portal..."
cd ../admin-portal
rm -f bootstrap/cache/*.php
docker-compose up -d
docker exec Admin_Portal php artisan migrate:refresh --seed

# Start eclaim-core
echo "Starting eclaim-core..."
cd ../eclaim-core
rm -f bootstrap/cache/*.php
docker-compose up -d

echo "All applications started successfully!"

#!/bin/bash

#!/bin/bash

#!/bin/bash

# Retrieve the gateway IP addresses from the Docker networks
gateway_eclaim=$(docker network inspect eclaim-core_default -f '{{range .IPAM.Config}}{{.Gateway}}{{end}}')
gateway_admin=$(docker network inspect admin-portal_default -f '{{range .IPAM.Config}}{{.Gateway}}{{end}}')
gateway_customer=$(docker network inspect customer-portal-be_default -f '{{range .IPAM.Config}}{{.Gateway}}{{end}}')

# Update the .env file for the Customer Portal
sed -i "s/GATEWAY_ECLAIM=.*/GATEWAY_ECLAIM=$gateway_eclaim:8002/" /home/anirban/Assignment/customer-portal-be/.env
sed -i "s/GATEWAY_ADMIN=.*/GATEWAY_ADMIN=$gateway_admin:8001/" /home/anirban/Assignment/customer-portal-be/.env

# Update the .env file for the Admin Portal
sed -i "s/GATEWAY_CUSTOMER=.*/GATEWAY_CUSTOMER=$gateway_customer:8000/" /home/anirban/Assignment/admin-portal/.env
sed -i "s/GATEWAY_ECLAIM=.*/GATEWAY_ECLAIM=$gateway_eclaim:8002/" /home/anirban/Assignment/admin-portal/.env

# Update the .env file for Eclaim Core
sed -i "s/GATEWAY_ADMIN=.*/GATEWAY_ADMIN=$gateway_admin:8001/" /home/anirban/Assignment/eclaim-core/.env
sed -i "s/GATEWAY_CUSTOMER=.*/GATEWAY_CUSTOMER=$gateway_customer:8000/" /home/anirban/Assignment/eclaim-core/.env
