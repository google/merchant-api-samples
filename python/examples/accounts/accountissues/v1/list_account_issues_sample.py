# -*- coding: utf-8 -*-
# Copyright 2024 Google LLC
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
"""A module to list all the account issues of an account."""


# [START merchantapi_list_account_issues]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountIssueServiceClient
from google.shopping.merchant_accounts_v1 import ListAccountIssuesRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def list_account_issues():
  """Lists all the account issues of an account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountIssueServiceClient(credentials=credentials)

  request = ListAccountIssuesRequest(parent=_PARENT)

  print("Sending list account issues request:")

  # Makes the request and catches and prints any error messages.
  try:
    response = client.list_account_issues(request=request)
    count = 0

    for account_issue in response:
      print(account_issue)
      count += 1
    print("The following count of account issues were returned: ")
    print(count)

  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  list_account_issues()

# [END merchantapi_list_account_issues]
