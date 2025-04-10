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

const fs = require('fs');
const path = require('path');
const homedir = require('os').homedir();
const http = require('http');
const crypto = require('crypto');
const {OAuth2Client, GoogleAuth} = require('google-auth-library');
const destroyer = require('server-destroy');


/**
 * Gets the configuration object.
 * @returns {!object} Configuration object.
 */
const getConfig = () => {
  const baseDir = path.resolve(homedir, 'shopping-samples', 'content');
  return {
    serviceAccountFile: path.join(baseDir, 'service-account.json'),
    tokenFile: path.join(baseDir, 'token.json'),
    clientSecretsFile: path.join(baseDir, 'client-secrets.json'),
    merchantInfoFile: path.join(baseDir, 'merchant-info.json')
  };
};

const SCOPES = ['https://www.googleapis.com/auth/content'];

/**
 * Gets authentication credentials, preferring Service Account, then Token file.
 * Returns an initialized GoogleAuth or OAuth2Client instance ready for use.
 * @returns {!Promise<!GoogleAuth | !OAuth2Client>} Authenticated client object.
 */
const getCredentials = async () => {
  const config = getConfig();

  console.log('Attempting to load service account information...');
  if (fs.existsSync(config.serviceAccountFile)) {
    console.log('Service account file exists, using service account.');
    // Return GoogleAuth instance configured with the key file
    const auth = new GoogleAuth({
      keyFilename: config.serviceAccountFile,
      scopes: SCOPES,
    });
    return auth;  // Or await auth.getClient() if you need the specific client
                  // type now
  } else {
    console.log(
        'Service account file does not exist, attempting to load token file...');
    if (fs.existsSync(config.tokenFile)) {
      console.log('Token file exists, using token file.');
      try {
        const tokenContent = fs.readFileSync(config.tokenFile, 'utf-8');
        const tokenData = JSON.parse(tokenContent);

        if (!tokenData.client_id || !tokenData.client_secret ||
            !tokenData.refresh_token) {
          throw new Error(
              'Token file is missing required fields (client_id, client_secret, refresh_token).');
        }

        // Create OAuth2Client instance
        const oauth2Client = new OAuth2Client(
            tokenData.client_id, tokenData.client_secret
            // No redirect URI needed here as we are just using the refresh
            // token
        );

        // Set the refresh token credentials
        oauth2Client.setCredentials({
          refresh_token: tokenData.refresh_token,
          client_secret: tokenData.client_secret
        });


        return oauth2Client;
      } catch (error) {
        console.error(
            `Error reading or parsing token file (${config.tokenFile}):`,
            error);
        throw new Error(
            `Failed to load credentials from token file. Please check the file format or run credential generation again. Original error: ${
                error.message}`);
      }
    } else {
      console.log('Token file does not exist.');
      if (fs.existsSync(config.clientSecretsFile)) {
        throw new Error(`Client secrets file (${
            config.clientSecretsFile}) exists, but token file (${
            config
                .tokenFile}) does not. Please run the 'generateUserCredentials' function/script to generate the token file using your client secrets.`);
      } else {
        throw new Error(
            'Service account file, token file, and client secrets file do not exist. Please follow setup instructions to create a service account or client secrets file.');
      }
    }
  }
};

/**
 * Performs the OAuth 2.0 flow using client secrets to generate
 * and store a token.json file (containing a refresh token).
 * @returns {!Promise<void>} Resolves when token.json is successfully created.
 */
const generateUserCredentials = async () => {
  const config = getConfig();
  return new Promise(async (resolve, reject) => {
    let server;  // Declare server in the promise scope for access in finally

    try {
      console.log(
          `Checking for client secrets file: ${config.clientSecretsFile}`);
      if (!fs.existsSync(config.clientSecretsFile)) {
        throw new Error(`Client secrets file does not exist at ${
            config.clientSecretsFile}. Please follow setup instructions.`);
      }

      console.log('Client secrets file exists. Starting OAuth2 flow...');
      const keys =
          JSON.parse(fs.readFileSync(config.clientSecretsFile, 'utf-8'));
      const keyData = keys.installed || keys.web;  // Handle both types
      if (!keyData) {
        throw new Error(
            'Invalid client secrets file format: missing "installed" or "web" key.');
      }
      const clientId = keyData.client_id;
      const clientSecret = keyData.client_secret;
      const redirectUriBase = 'http://127.0.0.1';  // Use loopback IP

      server = http.createServer();
      destroyer(server);

      server.listen(0, '127.0.0.1', async () => {
        const {port} = server.address();
        const redirectUri = `${redirectUriBase}:${port}/oauth2callback`;
        console.log(`Callback server listening on: ${redirectUri}`);

        const oauth2Client =
            new OAuth2Client(clientId, clientSecret, redirectUri);
        const codes = await oauth2Client.generateCodeVerifierAsync();

        const state = crypto.randomBytes(16).toString('hex');
        const authorizeUrl = oauth2Client.generateAuthUrl({
          access_type: 'offline',
          scope: SCOPES,
          prompt: 'consent',
          state: state,
          code_challenge: codes.codeChallenge,
          code_challenge_method: 'S256'
        });

        server.on('request', async (req, res) => {
          try {
            const requestUrl = new URL(req.url, redirectUriBase);

            if (requestUrl.pathname === '/oauth2callback') {
              const code = requestUrl.searchParams.get('code');
              const receivedState = requestUrl.searchParams.get('state');

              if (!code) {
                throw new Error('Authorization code missing from callback.');
              }
              if (receivedState !== state) {
                throw new Error('State mismatch. Possible CSRF attack.');
              }

              console.log(`Received authorization code: ${code}`);
              res.writeHead(200, {'Content-Type': 'text/plain'});
              res.end(
                  'Authentication successful! You can close this tab and return to the console.');

              console.log('Exchanging code for tokens...');
              const {tokens} = await oauth2Client.getToken({
                code,
                codeVerifier: codes.codeVerifier,
              });

              console.log('Tokens acquired.');

              console.log(
                  `Received Refresh Token (save this securely if needed, it will be stored in ${
                      config.tokenFile}): ${tokens.refresh_token}`);

              const tokenFileData = {
                client_id: clientId,
                client_secret: clientSecret,
                refresh_token: tokens.refresh_token
              };

              fs.writeFileSync(
                  config.tokenFile, JSON.stringify(tokenFileData, null, 2));
              console.log(
                  `Credentials successfully saved to ${config.tokenFile}`);

              resolve();

            } else {
              res.writeHead(404);
              res.end('Not Found');
            }
          } catch (e) {
            console.error('Error handling callback request:', e);
            if (!res.headersSent) {
              res.writeHead(500, {'Content-Type': 'text/plain'});
              res.end(`Authentication failed: ${e.message}`);
            }
            reject(e);
          } finally {
            if (server && server.listening) {
              console.log('Shutting down callback server...');
              server.destroy();
            }
          }
        });

        console.log(
            `Please log in to Google and authorize the application by visiting:\n${
                authorizeUrl}\n`);
        try {
          const open = (await import('open')).default;
          await open(authorizeUrl, {wait: false});
          console.log('Attempted to open browser automatically.');
        } catch (openError) {
          console.warn(
              'Could not open browser automatically:', openError.message);
          console.log(
              'Please copy the URL above and paste it into your browser.');
        }
      });

      server.on('error', (err) => {
        console.error('Server error:', err);
        reject(err);
      });

    } catch (error) {
      console.error('Setup error:', error.message);
      reject(error);
      if (server && server.listening) {
        server.destroy();
      }
    }
  });
};

/**
 * Gets authentication credentials, generating them if they don't exist.
 * @returns {!Promise<!GoogleAuth | !OAuth2Client>} Authenticated client object.
 */
const getOrGenerateUserCredentials = async () => {
  const config = getConfig();
  const refreshTokenExists = fs.existsSync(config.tokenFile);
  const serviceAccountExists = fs.existsSync(config.serviceAccountFile);
  if (refreshTokenExists || serviceAccountExists) {
    return await getCredentials();
  } else {
    await generateUserCredentials();
    return await getCredentials();
  }
};

module.exports = {
  getOrGenerateUserCredentials,
  getConfig
};
