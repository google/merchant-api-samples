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

package v1

// [START merchantapi_update_product_input]

import (
	"context"
	"fmt"
	"log"

	"google.golang.org/protobuf/types/known/fieldmaskpb"

	"cloud.google.com/go/shopping/merchant/products/apiv1/productspb"
	"cloud.google.com/go/shopping/type/typepb"

	products "cloud.google.com/go/shopping/merchant/products/apiv1"

	"google.golang.org/api/option"

	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

const accountForUpdate = "1234567890"
const productIDForUpdate = "en~label~sku123" // An ID assigned to a product by Google. In the format contentLanguage~feedLabel~offerId
const dataSourceForUpdate = "1234567890"     // Replace with your datasource ID.

type updateProductInputSample struct{}

func init() {
	if err := collection.Add("products.productinputs.v1.update_product_input", &updateProductInputSample{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *updateProductInputSample) Description() string {
	return "This sample demonstrates how to update a product input"
}

func (s *updateProductInputSample) Execute() error {
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

	// The name of the product input to update.
	// Format: accounts/{account}/productInputs/{product}
	name := fmt.Sprintf("accounts/%s/productInputs/%s", accountForUpdate, productIDForUpdate)

	// The datasource can be either a primary or supplemental datasource.
	// Format: accounts/{account}/dataSources/{datasource}
	dataSource := fmt.Sprintf("accounts/%s/dataSources/%s", accountForUpdate, dataSourceForUpdate)

	// The list of fields to be updated.
	// Only product_attributes and custom_attributes can be updated.
	fieldMask := &fieldmaskpb.FieldMask{
		Paths: []string{
			"product_attributes.title",
			"product_attributes.description",
			"product_attributes.link",
			"product_attributes.image_link",
			"product_attributes.availability",
			"product_attributes.condition",
			"product_attributes.gtins",
			"custom_attributes.mycustomattribute",
		},
	}

	// The new product attributes to be updated.
	attributes := &productspb.ProductAttributes{
		Title:        collection.Ptr("A Tale of Two Cities"),
		Description:  collection.Ptr("A classic novel about the French Revolution"),
		Link:         collection.Ptr("https://exampleWebsite.com/tale-of-two-cities.html"),
		ImageLink:    collection.Ptr("https://exampleWebsite.com/tale-of-two-cities.jpg"),
		Availability: collection.Ptr(productspb.Availability_IN_STOCK),
		Condition:    collection.Ptr(productspb.Condition_NEW),
		Gtins:        []string{"9780007350896"},
	}

	// The new custom attributes to be updated.
	customAttribute := &typepb.CustomAttribute{
		Name:  collection.Ptr("mycustomattribute"),
		Value: collection.Ptr("Example value"),
	}

	// The product input to be updated.
	productInput := &productspb.ProductInput{
		Name:              name,
		ProductAttributes: attributes,
		CustomAttributes:  []*typepb.CustomAttribute{customAttribute},
	}

	// The request to update the product input.
	req := &productspb.UpdateProductInputRequest{
		ProductInput: productInput,
		UpdateMask:   fieldMask,
		DataSource:   dataSource,
	}

	fmt.Println("Sending update ProductInput request")
	// Calls the API to update the product input.
	resp, err := productInputsClient.UpdateProductInput(ctx, req)
	if err != nil {
		return fmt.Errorf("could not update product input: %w", err)
	}

	fmt.Println("Updated ProductInput Name below")
	// The last part of the product name will be the product ID assigned to a product by Google.
	// Product ID has the format `contentLanguage~feedLabel~offerId`
	fmt.Println(resp.Name)
	fmt.Println("Updated Product below")
	fmt.Println(resp)
	return nil
}

// [END merchantapi_update_product_input]