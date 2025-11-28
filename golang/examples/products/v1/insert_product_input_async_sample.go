// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

package v1

// [START merchantapi_insert_product_input_async]

import (
	"context"
	"fmt"
	"log"
	"math/rand"
	"sync"
	"time"

	"cloud.google.com/go/shopping/merchant/products/apiv1/productspb"
	"cloud.google.com/go/shopping/type/typepb"
	"google.golang.org/api/option"

	products "cloud.google.com/go/shopping/merchant/products/apiv1"

	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

const (
	// The ID of the account that owns the data source.
	accountForInsertAsync = "accounts/1234567890"
	// The ID of the data source that will own the product.
	// You can only insert products into datasource types of Input "API", and of Type
	// "Primary" or "Supplemental."
	// This field takes the `name` field of the datasource.
	dataSourceForInsertAsync = "accounts/1234567890/dataSources/1234567890"
)

var seededRand *rand.Rand = rand.New(
	rand.NewSource(time.Now().UnixNano()))

const charset = "abcdefghijklmnopqrstuvwxyz" +
	"ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"

// generateRandomString creates a random string of a given length.
func generateRandomString(length int) string {
	b := make([]byte, length)
	for i := range b {
		b[i] = charset[seededRand.Intn(len(charset))]
	}
	return string(b)
}

// createRandomProductInput creates a new ProductInput with a random offer ID.
func createRandomProductInput() *productspb.ProductInput {
	price := &typepb.Price{
		AmountMicros: collection.Ptr(int64(33450000)),
		CurrencyCode: collection.Ptr("USD"),
	}

	shipping1 := &productspb.Shipping{
		Price:   price,
		Country: "GB",
		Service: "1st class post",
	}

	shipping2 := &productspb.Shipping{
		Price:   price,
		Country: "FR",
		Service: "1st class post",
	}

	attributes := &productspb.ProductAttributes{
		Title:                 collection.Ptr("A Tale of Two Cities"),
		Description:           collection.Ptr("A classic novel about the French Revolution"),
		Link:                  collection.Ptr("https://exampleWebsite.com/tale-of-two-cities.html"),
		ImageLink:             collection.Ptr("https://exampleWebsite.com/tale-of-two-cities.jpg"),
		Availability:          collection.Ptr(productspb.Availability_IN_STOCK),
		Condition:             collection.Ptr(productspb.Condition_NEW),
		GoogleProductCategory: collection.Ptr("Media > Books"),
		Gtins:                 []string{"9780007350896"},
		Shipping:              []*productspb.Shipping{shipping1, shipping2},
	}

	return &productspb.ProductInput{
		ContentLanguage:   "en",
		FeedLabel:         "CH",
		OfferId:           generateRandomString(8),
		ProductAttributes: attributes,
	}
}

type insertProductInputAsyncSample struct{}

func init() {
	if err := collection.Add("products.productinputs.v1.insert_product_input_async", &insertProductInputAsyncSample{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *insertProductInputAsyncSample) Description() string {
	return "This sample demonstrates how to insert a product input asynchronously"
}

func (s *insertProductInputAsyncSample) Execute() error {
	ctx := context.Background()

	// Authenticates with Google.
	tokenSource, err := googleauth.AuthWithGoogle(ctx)
	if err != nil {
		return fmt.Errorf("failed to authenticate: %w", err)
	}

	// Creates a new product inputs client.
	productInputsClient, err := products.NewProductInputsClient(ctx, option.WithTokenSource(tokenSource))
	if err != nil {
		return fmt.Errorf("could not create product inputs client: %w", err)
	}
	defer productInputsClient.Close()

	parent := accountForInsertAsync

	// Number of concurrent requests to send.
	const numRequests = 5

	var wg sync.WaitGroup
	resultsChan := make(chan *productspb.ProductInput, numRequests)
	errChan := make(chan error, numRequests)

	fmt.Println("Sending insert product input requests")

	// Launch goroutines to send requests concurrently.
	for i := 0; i < numRequests; i++ {
		wg.Add(1)
		go func() {
			defer wg.Done()

			req := &productspb.InsertProductInputRequest{
				Parent:       parent,
				DataSource:   dataSourceForInsertAsync,
				ProductInput: createRandomProductInput(),
			}

			// Calls the API to insert the product input.
			resp, err := productInputsClient.InsertProductInput(ctx, req)
			if err != nil {
				errChan <- fmt.Errorf("could not insert product input: %w", err)
				return
			}
			resultsChan <- resp
		}()
	}

	// Wait for all goroutines to finish and then close the channels.
	go func() {
		wg.Wait()
		close(resultsChan)
		close(errChan)
	}()

	// Collect all errors.
	var errs []error
	for err := range errChan {
		errs = append(errs, err)
	}

	// Collect all successful results.
	var results []*productspb.ProductInput
	for result := range resultsChan {
		results = append(results, result)
	}

	// Handle results and errors after all requests are complete.
	if len(errs) > 0 {
		for _, err := range errs {
			fmt.Println(err)
		}
		return fmt.Errorf("encountered %d errors during async insert", len(errs))
	}

	fmt.Println("Inserted products below")
	fmt.Println(results)

	return nil
}

// [END merchantapi_insert_product_input_async]
