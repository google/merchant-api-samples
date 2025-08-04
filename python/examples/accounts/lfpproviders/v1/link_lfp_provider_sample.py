# -*- coding: utf-8 -*-
# Copyright 2025 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
# [START merchantapi_link_lfp_provider]
"""Sample for linking LFP providers."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import LfpProvidersServiceClient
from google.shopping.merchant_accounts_v1 import LinkLfpProviderRequest

# Gets the merchant account ID from the configuration file.
_ACCOUNT = configuration.Configuration().read_merchant_info()


def link_lfp_provider(
    lfp_provider_name: str, external_account_id: str
) -> None:
  """Links the LFP Providers for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = LfpProvidersServiceClient(credentials=credentials)

  # Creates the request with the provider name and external account ID.
  request = LinkLfpProviderRequest(
      name=lfp_provider_name, external_account_id=external_account_id
  )

  print("Sending link lfp provider request:")
  # Makes the request and catches and prints any error messages.
  try:
    # An empty response is returned on success.
    client.link_lfp_provider(request=request)
    print(f"Successfully linked to LFP provider: {lfp_provider_name}")
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The region code for the omnichannel setting.
  _REGION_CODE = "REGION_CODE"  # e.g., "US"
  # The ID of the LFP provider.
  _LFP_PROVIDER_ID = "LFP_PROVIDER_ID"

  # The name of the LFP provider to link, returned from `find_lfp_providers`.
  # Format:
  # `accounts/{account}/omnichannelSettings/{omnichannel_setting}/lfpProviders/{lfp_provider}`
  lfp_provider = f"accounts/{_ACCOUNT}/omnichannelSettings/{_REGION_CODE}/lfpProviders/{_LFP_PROVIDER_ID}"

  # The external account ID by which this merchant is known to the LFP provider.
  _external_account_id = str(_ACCOUNT)

  link_lfp_provider(lfp_provider, _external_account_id)

# [END merchantapi_link_lfp_provider]
