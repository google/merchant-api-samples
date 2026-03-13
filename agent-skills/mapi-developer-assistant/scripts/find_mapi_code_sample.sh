#!/bin/bash

if [ -z "$1" ]; then
  echo "Error: Query argument is required."
  echo "Usage: $0 \"query\" [language]"
  echo "  language: java, python, php, nodejs, dotnet, apps_script (optional)"
  exit 1
fi

QUERY=$1
LANGUAGE=${2:-""}
QUERY_ESCAPED=${QUERY//\"/\\\"}
LANGUAGE_ESCAPED=${LANGUAGE//\"/\\\"}

if [ -n "$LANGUAGE" ]; then
  ARGS="\"query\": \"$QUERY_ESCAPED\", \"language\": \"$LANGUAGE_ESCAPED\""
else
  ARGS="\"query\": \"$QUERY_ESCAPED\""
fi

curl -s -X POST "https://merchantapi.googleapis.com/devdocs/mcp/" \
  -H "Accept: application/json, text/event-stream" \
  -H "Content-Type: application/json" \
  -H "MCP-Protocol-Version: 2025-06-18" \
  -d "{
    \"jsonrpc\": \"2.0\",
    \"method\": \"tools/call\",
    \"params\": {
      \"name\": \"find_mapi_code_sample\",
      \"arguments\": {
        $ARGS
      }
    },
    \"id\": 1
  }"
