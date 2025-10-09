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
