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

// [START merchantapi_delete_product_input]

import (
	"context"
	"fmt"
	"log"

	"cloud.google.com/go/shopping/merchant/products/apiv1/productspb"

	products "cloud.google.com/go/shopping/merchant/products/apiv1"

	"google.golang.org/api/option"
	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

// These are all placeholder values. Please fill in your own details.
const accountIDForDelete = "1234567890"

// The ID of the product. In the format of `contentLanguage~feedLabel~offerId`.
const productIDForDelete = "en~label~sku123"

// The data source from which to delete the product.
// Format: `accounts/{account}/dataSources/{dataSource}`
const dataSourceForDelete = "accounts/1234567890/dataSources/1234567890"

type deleteProductInputSample struct{}

func init() {
	if err := collection.Add("products.productinputs.v1.delete_product_input", &deleteProductInputSample{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *deleteProductInputSample) Description() string {
	return "This sample demonstrates how to delete a product input"
}

func (s *deleteProductInputSample) Execute() error {
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

	// The name of the product to delete.
	// Format: `accounts/{account}/productinputs/{productinput}`
	name := fmt.Sprintf("accounts/%s/productInputs/%s", accountIDForDelete, productIDForDelete)

	// Creates the request to delete the product input.
	req := &productspb.DeleteProductInputRequest{
		Name:       name,
		DataSource: dataSourceForDelete,
	}

	fmt.Println("Sending deleteProductInput request")
	// The DeleteProductInput method doesn't return a response upon success.
	err = productInputsClient.DeleteProductInput(ctx, req)
	if err != nil {
		return fmt.Errorf("could not delete product input: %w", err)
	}

	fmt.Println("Delete successful, note that it may take a few minutes for the delete to update in the system. If you make a products.get or products.list request before a few minutes have passed, the old product data may be returned.")
	return nil
}

// [END merchantapi_delete_product_input]
