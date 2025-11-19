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

// Package v1 provides examples for managing a product input using the Merchant API.
package v1

// [START merchantapi_insert_product_input]

import (
	"context"
	"fmt"
	"log"

	"cloud.google.com/go/shopping/merchant/products/apiv1/productspb"
	"cloud.google.com/go/shopping/type/typepb"

	"google3/third_party/golang/cloud_google_com/go/shopping/merchant/products/v/v1/apiv1/products"
	"google.golang.org/api/option"

	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

const dataSourceForInsert = "accounts/642650150/dataSources/10525548636"
const accountForInsert = "accounts/642650150"

type insertProductInputSample struct{}

func init() {
	if err := collection.Add("products.productinputs.v1.insert_product_input", &insertProductInputSample{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *insertProductInputSample) Description() string {
	return "This sample demonstrates how to insert a product input"
}

func ptr[T any](v T) *T {
	return &v
}

func (s *insertProductInputSample) Execute() error {
	ctx := context.Background()

	tokenSource, err := googleauth.AuthWithGoogle(ctx)
	if err != nil {
		return fmt.Errorf("failed to authenticate: %w", err)
	}

	productInputsClient, err := products.NewProductInputsClient(ctx, option.WithTokenSource(tokenSource))
	if err != nil {
		return fmt.Errorf("could not create product inputs client: %w", err)
	}
	defer productInputsClient.Close()

	parent := accountForInsert

	// Define Price using inline pointers
	price := &typepb.Price{
		AmountMicros: ptr(int64(33450000)),
		CurrencyCode: ptr("USD"),
	}

	// Shipping fields (Country/Service) are raw strings, so no ptr() needed here.
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

	// Define Attributes using inline pointers for strings and enums
	attributes := &productspb.ProductAttributes{
		Title:                 ptr("A Tale of Two Cities"),
		Description:           ptr("A classic novel about the French Revolution"),
		Link:                  ptr("https://exampleWebsite.com/tale-of-two-cities.html"),
		ImageLink:             ptr("https://exampleWebsite.com/tale-of-two-cities.jpg"),
		Availability:          ptr(productspb.Availability_IN_STOCK),
		Condition:             ptr(productspb.Condition_NEW),
		GoogleProductCategory: ptr("Media > Books"),
		Gtins:                 []string{"9780007350896"},
		Shipping:              []*productspb.Shipping{shipping1, shipping2},
	}

	productInput := &productspb.ProductInput{
		ContentLanguage:   "en",
		FeedLabel:         "US",
		OfferId:           "sku123",
		ProductAttributes: attributes,
	}

	req := &productspb.InsertProductInputRequest{
		Parent:       parent,
		DataSource:   dataSourceForInsert,
		ProductInput: productInput,
	}

	fmt.Println("Sending insert ProductInput request")
	resp, err := productInputsClient.InsertProductInput(ctx, req)
	if err != nil {
		return fmt.Errorf("could not insert product input: %w", err)
	}

	fmt.Println("Inserted ProductInput Name below")
	fmt.Println(resp.Name)
	return nil
}

// [END merchantapi_insert_product_input]
