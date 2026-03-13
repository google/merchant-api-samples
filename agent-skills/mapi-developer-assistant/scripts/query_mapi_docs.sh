#!/bin/bash

if [ -z "$1" ]; then
  echo "Error: Query argument is required."
  exit 1
fi

QUERY=$1
# Simple escape for double quotes to prevent breaking JSON
QUERY_ESCAPED=${QUERY//\"/\\\"}

curl -s -X POST "https://merchantapi.googleapis.com/devdocs/mcp/" \
  -H "Accept: application/json, text/event-stream" \
  -H "Content-Type: application/json" \
  -H "MCP-Protocol-Version: 2025-06-18" \
  -d "{
    \"jsonrpc\": \"2.0\",
    \"method\": \"tools/call\",
    \"params\": {
      \"name\": \"query_mapi_docs\",
      \"arguments\": {
        \"query\": \"$QUERY_ESCAPED\" 
      }
    },
    \"id\": 1
  }"
