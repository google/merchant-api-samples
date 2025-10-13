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

// Package all imports all sample packages, triggering
// their init() functions for registration with the collection.
//
// To disable a group of samples from the build, simply comment out its import line.
package all

import (
	// This package imports samples to trigger their init() functions for registration.
	_ "github.com/google/merchant-api-samples/go/examples/accounts/accounts/v1"
	_ "github.com/google/merchant-api-samples/go/examples/accounts/developerregistration/v1"
)
