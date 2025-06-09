#!/bin/bash
# scripts/stop.sh - Bütün konteynerləri dayandır
echo "⏹️ Konteynerləri dayandırırıq..."
docker-compose down
docker-compose -f docker-compose.prod.yml down