// Package all imports all sample packages, triggering
// their init() functions for registration with the collection.
//
// To disable a group of samples from the build, simply comment out its import line.
package all

import (
	// This package imports samples to trigger their init() functions for registration.
	_ "github.com/google/merchant-api-samples/go/examples/accounts/accounts/v1"
)
