// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// Command main runs Google Merchant API code samples.
package main

import (
	"fmt"
	"log"
	"os"

	"github.com/google/merchant-api-samples/go/collection"

	_ "github.com/google/merchant-api-samples/go/all"
)

func main() {
	if len(os.Args) < 2 {
		fmt.Println("Please specify which sample to run. Example call below:\n")
		fmt.Println("go run . accounts.accounts.v1.get_account\n")
		fmt.Println("Available samples:")
		for _, name := range collection.List() {
			s, _ := collection.Get(name)
			fmt.Printf("  %s: %s\n", name, s.Description())
		}
		os.Exit(1)
	}

	sampleName := os.Args[1]

	sampleToRun, exists := collection.Get(sampleName)
	if !exists {
		fmt.Printf("Error: Sample '%s' not found.\n", sampleName)
		os.Exit(1)
	}

	// Execute the chosen sample
	fmt.Printf("\n--- Running sample: %s ---\n", sampleName)
	if err := sampleToRun.Execute(); err != nil {
		log.Fatalf("Sample execution failed: %v", err)
	}
	fmt.Println("--- Sample execution finished ---")
}
