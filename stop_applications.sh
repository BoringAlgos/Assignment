#!/bin/bash

# Stop customer-portal-fe
echo "Stopping customer-portal-fe..."
kill -9 $(lsof -t -i:3007)

# Stop customer-portal-be
echo "Stopping customer-portal-be..."
cd customer-portal-be
docker-compose down
cd ..

# Stop admin-portal-fe
echo "Stopping admin-portal-fe..."
kill -9 $(lsof -t -i:3006)

# Stop admin-portal
echo "Stopping admin-portal..."
cd admin-portal
docker-compose down
cd ..

# Stop eclaim-core
echo "Stopping eclaim-core..."
cd eclaim-core
docker-compose down
cd ..

echo "All applications stopped successfully!"

