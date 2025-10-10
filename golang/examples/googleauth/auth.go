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

// Package googleauth provides functions for authenticating with Google.
package googleauth

import (
	"context"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"net/http/httptest"
	"os"
	"os/user"
	"path"
	"time"

	"google.golang.org/api/merchantapi/accounts/v1"
	"golang.org/x/oauth2"
	"golang.org/x/oauth2/google"
	"github.com/pkg/browser"
)

const (
	serviceAccountFile = "service-account.json"
	oauth2ClientFile   = "client-secrets.json"
	storedTokenFile    = "token.json"
)

// AuthWithGoogle attempts to authenticate with Google, using, in order:
// - Application Default Credentials
// - Service Account credentials
// - OAuth2 credentials
func AuthWithGoogle(ctx context.Context) (*http.Client, error) {
	// // First, check for the Application Default Credentials.
	if client, err := google.DefaultClient(ctx, merchantapi.ContentScope); err == nil {
		fmt.Println("Using Application Default Credentials.")
		return client, nil
	}

	// Second, check for service account info, since it's the easier auth flow.
	usr, err := user.Current()
	if err != nil {
		return nil, fmt.Errorf("failed to get current user: %v", err)
	}

	defaultPath := path.Join(usr.HomeDir, "shopping-samples", "content")

	serviceAccountPath := path.Join(defaultPath, serviceAccountFile)
	if _, err := os.Stat(serviceAccountPath); err == nil {
		fmt.Printf("Loading service account from %s.\n", serviceAccountPath)
		data, err := ioutil.ReadFile(serviceAccountPath)
		if err != nil {
			return nil, fmt.Errorf("failed to read service account file: %v", err)
		}
		config, err := google.JWTConfigFromJSON(data, merchantapi.ContentScope)
		if err != nil {
			return nil, fmt.Errorf("failed to parse service account credentials: %v", err)
		}
		fmt.Printf("Service account credentials for user %s found.\n", config.Email)

		return config.Client(ctx), nil
	}
	// Last chance for authentication, check for OAuth2 client secrets.
	oauth2ClientPath := path.Join(defaultPath, oauth2ClientFile)
	if _, err := os.Stat(oauth2ClientPath); err == nil {
		fmt.Printf("Loading OAuth2 client from %s.\n", oauth2ClientPath)
		data, err := ioutil.ReadFile(oauth2ClientPath)
		if err != nil {
			return nil, fmt.Errorf("failed to read OAuth2 client file: %v", err)
		}
		config, err := google.ConfigFromJSON(data, merchantapi.ContentScope)
		if err != nil {
			return nil, fmt.Errorf("failed to parse OAuth2 client credentials: %v", err)
		}
		fmt.Printf("OAuth2 client credentials for application %s found.\n", config.ClientID)
		return newOAuthClient(ctx, config, defaultPath)
	}

	// If none of the above worked.
	return nil, fmt.Errorf("authentication failed: no OAuth2 authentication files found. Checked:\n- %s\n- %s\nPlease check https://github.com/google/merchant-api-samples/blob/main/README.md#setting-up-authentication-and-sample-configuration", serviceAccountPath, oauth2ClientPath)
}

func loadToken(tokenPath string) (*oauth2.Token, error) {
	var token oauth2.Token
	jsonBlob, err := ioutil.ReadFile(tokenPath)
	if err != nil {
		return nil, err
	}
	if err := json.Unmarshal(jsonBlob, &token); err != nil {
		return nil, err
	}
	return &token, nil
}

func storeToken(tokenPath string, token *oauth2.Token) error {
	jsonBlob, err := json.MarshalIndent(token, "", "  ")
	if err != nil {
		return err
	}
	return ioutil.WriteFile(tokenPath, jsonBlob, 0660)
}

func newOAuthClient(ctx context.Context, config *oauth2.Config, defaultPath string) (*http.Client, error) {
	tokenPath := path.Join(defaultPath, storedTokenFile)
	token, err := loadToken(tokenPath)
	if err != nil {
		fmt.Printf("No stored token found in %s, re-authenticating.\n", tokenPath)
		token, err = tokenFromWeb(ctx, config)
		if err != nil {
			return nil, err
		}
		if err := storeToken(tokenPath, token); err != nil {
			return nil, fmt.Errorf("error storing OAuth2 token: %v", err)
		}
	} else {
		fmt.Printf("Using token stored in %v for authentication.\n", tokenPath)
	}
	return config.Client(ctx, token), nil
}

func tokenFromWeb(ctx context.Context, config *oauth2.Config) (*oauth2.Token, error) {
	type authResult struct {
		code string
		err  error
	}
	ch := make(chan authResult)
	randState := fmt.Sprintf("st%d", time.Now().UnixNano())
	ts := httptest.NewServer(http.HandlerFunc(func(rw http.ResponseWriter, req *http.Request) {
		if req.URL.Path == "/favicon.ico" {
			http.Error(rw, "", 404)
			return
		}
		if req.FormValue("state") != randState {
			err := fmt.Errorf("state doesn't match: req = %#v", req)
			log.Print(err)
			http.Error(rw, err.Error(), 500)
			ch <- authResult{err: err}
			return
		}
		if code := req.FormValue("code"); code != "" {
			fmt.Fprintf(rw, "<h1>Success</h1>Authorized.")
			rw.(http.Flusher).Flush()
			ch <- authResult{code: code}
			return
		}
		err := fmt.Errorf("no code received")
		log.Print(err)
		http.Error(rw, err.Error(), 500)
		ch <- authResult{err: err}
	}))
	defer ts.Close()

	config.RedirectURL = ts.URL
	authURL := config.AuthCodeURL(randState)
	go browser.OpenURL(authURL)
	log.Printf("Authorize this app at: %s", authURL)

	result := <-ch
	if result.err != nil {
		return nil, result.err
	}
	code := result.code
	log.Printf("Got code: %s", code)

	token, err := config.Exchange(ctx, code)
	if err != nil {
		return nil, fmt.Errorf("token exchange error: %v", err)
	}
	return token, nil
}
