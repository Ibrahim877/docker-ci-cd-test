#!/bin/bash
# scripts/clean.sh - Təmizlik
echo "🧹 Docker təmizliyi..."
docker-compose down -v
docker system prune -f
docker volume prune -f