#!/bin/bash

# Exit immediately if a command fails
set -e

# Directory where the SSL certificate and private key are stored
SSL_DIR="$(dirname "$0")/php/apache"

# Create the SSL directory if it does not exist
mkdir -p "$SSL_DIR"


# Do not generate a new certificate if one already exists
if [ -f "$SSL_DIR/server.crt" ] && [ -f "$SSL_DIR/server.key" ]; then
    echo "SSL certificate already exists."
    exit 0
fi

# Generate a self-signed SSL certificate and private key
openssl req -x509 -nodes -days 365 \
    -newkey rsa:2048 \
    -keyout "$SSL_DIR/server.key" \
    -out "$SSL_DIR/server.crt" \
    -subj "/CN=localhost"

echo "SSL certificate generated."
