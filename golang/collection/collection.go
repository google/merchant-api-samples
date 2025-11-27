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

// Package collection provides sample registration and retrieval.
package collection

import "fmt"

// Sample defines the interface that every code sample must implement.
type Sample interface {
	Execute() error
	Description() string
}

// samples stores all the added samples.
var samples = make(map[string]Sample)

// Add places a new sample into the collection.
// It returns an error if a sample with the given name already exists.
func Add(name string, sample Sample) error {
	if _, exists := samples[name]; exists {
		return fmt.Errorf("a sample named '%s' already exists in the collection", name)
	}
	samples[name] = sample
	return nil
}

// Get retrieves a sample from the collection by its name.
func Get(name string) (Sample, bool) {
	sample, exists := samples[name]
	return sample, exists
}

// List returns a list of all sample names in the collection.
func List() []string {
	var names []string
	for name := range samples {
		names = append(names, name)
	}
	return names
}

// Ptr returns a pointer to v.
func Ptr[T any](v T) *T {
	return &v
}
