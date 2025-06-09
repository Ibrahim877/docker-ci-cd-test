#!/bin/bash
# scripts/prod.sh - Production üçün
echo "🚀 Production mühitini başladırıq..."
docker-compose -f docker-compose.prod.yml up -d --build