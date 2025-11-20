#!/bin/bash

echo "🚀 Starting INVIRO WordPress Theme Preview..."
echo ""

if ! command -v docker &> /dev/null
then
    echo "❌ Docker not found. Please install Docker first."
    exit 1
fi

echo "📦 Building and starting containers..."
docker compose up -d --build

echo ""
echo "⏳ Waiting for database to be ready..."
sleep 20

echo ""
echo "✅ Done!"
echo ""
echo "🌐 Preview available at: http://localhost:8080"
echo ""
echo "📝 WordPress setup:"
echo "   - Database: wordpress"
echo "   - Username: wpuser"
echo "   - Password: wppass"
echo ""
echo "🛑 To stop: docker compose down"
echo "🗑️  To stop and remove data: docker compose down -v"
