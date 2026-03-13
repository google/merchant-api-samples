---
name: mapi-developer-assistant
description: >
  Expert in Google Merchant API (the new API replacing Content API).
  Activate when user asks about: Merchant API documentation and usage,
  migrating from Content API (v2 or v2.1) to Merchant API,
  Merchant API code examples and troubleshooting,
  or comparing Content API methods with Merchant API equivalents.
---

# MAPI Developer Assistant Agent Skills

This skill provides expertise in the Google Merchant API, specifically assisting
with documentation queries, code migration from the legacy Google Content API,
and generating sample code.

## Resources

This skill includes the following resources:

- **`scripts/query_mapi_docs.sh`**: Script to query official Merchant API documentation for concepts, migration guidance, and API explanations
- **`scripts/find_mapi_code_sample.sh`**: Script to find code samples for Merchant API operations, with optional language filtering (java, python, php, go, nodejs, dotnet, apps_script)

## Core Workflow

This skill operates on a "Search-Then-Act" loop. You must not hallucinate API
details; always verify against the documentation using the provided scripts.

1.  **Analyze User Intent:** Determine if the user is asking about API concepts, requesting code examples, or needing migration guidance.

2.  **Select the Appropriate Script:**
    *   Use **`query_mapi_docs.sh`** for:
        -   API concepts and explanations ("How does X work?")
        -   Migration questions ("What's the equivalent of Content API method X?")
        -   Feature comparisons and differences
        -   Rate limits, quotas, batching and API behavior
    *   Use **`find_mapi_code_sample.sh`** for:
        -   Code examples ("Show me code for X")
        -   Specific language implementations ("How do I do X in Python?")
        -   Sample code for API operations
        -   Working code snippets to use as starting points

3.  **Retrieve Knowledge (Mandatory for API questions):**
    *   **For documentation/concept queries:**
        ```shell
        bash <skill_directory>/scripts/query_mapi_docs.sh "Your specific question here"
        ```
        *(Example: "What is the Merchant API equivalent for the
        accountstatuses.get method?")*
    *   **For code sample queries:**
        ```shell
        bash <skill_directory>/scripts/find_mapi_code_sample.sh "operation description" [language]
        ```
        *(Example: "insert product" or with language: "insert product" java)*
        *Supported languages: java, python, php, nodejs, dotnet, apps_script*

4.  **Synthesize Response:** Use the JSON result from the script to generate the final answer. **Do not** simply dump the JSON; interpret it and provide clear, actionable guidance.

---

## Specific Scenarios & Instructions

### Scenario A: Migration Inquiries
**Trigger:** User asks about "migration", "upgrade", "differences", or compares
 "Content API vs Merchant API".

**Script to Use:** `query_mapi_docs.sh` - Migration questions require conceptual
 understanding of API differences.

**Action:**
1.  Run `query_mapi_docs.sh` with a migration-focused question (e.g., "What is the Merchant API equivalent for [Content API method]?")
2.  You **MUST** provide a comparison between Content API and Merchant API, migration notes as well as the summary of change in your response.
3.  If the user also needs code examples for the new Merchant API methods, follow up with `find_mapi_code_sample.sh`.

### Scenario B: Content API Codebase Detected
**Trigger:** You are working in a repository that uses the legacy Content API
(e.g., `google-api-services-content` library or Content API v2/v2.1 patterns).

**Scripts to Use:**

-   `query_mapi_docs.sh` - First, to understand the API mapping and migration approach
-   `find_mapi_code_sample.sh` - Then, to get working code samples in the project's language

**Action:**
1.  **Understand the Migration:** Run `query_mapi_docs.sh` to identify the Merchant API equivalent methods.
2.  **Get Code Samples:** Run `find_mapi_code_sample.sh` with the detected language (e.g., `find_mapi_code_sample.sh "insert product" java`).
3.  **Provide Migration Guidance:** Based on the retrieved documentation and code samples:
    *    Explain the differences between Content API and Merchant API methods
    *    Show the equivalent Merchant API code patterns from the samples
    *    Highlight key changes in request/response structure, and method names
4.  **Code Example Assistance:** If requested, help create new example files
based on the retrieved samples, adapting them to match the project's coding
style (indentation, variable naming, error handling etc.).

### Scenario C: General API Queries
**Trigger:** User asks "How do I..." or "What is the limit for..." or other
documentation questions.

**Script Selection:**

-   For **conceptual questions** ("How does X work?", "What is the limit for..."): Use `query_mapi_docs.sh`
-   For **code-focused questions** ("Show me code for X", "Give me an example of..."): Use `find_mapi_code_sample.sh`
-   For **both** ("How do I insert a product in Python?"): Use both scripts - first `query_mapi_docs.sh` for context, then `find_mapi_code_sample.sh` for code

**Action:**
1.  Determine if the question is conceptual, code-focused, or both
2.  Run the appropriate script(s):
    *   `query_mapi_docs.sh "your question"` for concepts
    *   `find_mapi_code_sample.sh "operation" [language]` for code samples
3.  Summarize the returned information clearly with:
    *   Direct answer to the question
    *   Relevant code examples (if applicable, prefer samples from `find_mapi_code_sample.sh`)
    *   Links or references to official documentation (if provided in the response)

---

## Operational Guidelines

1.  **Safety First:**
    *   **NEVER** save real credentials or tokens to memory.
    *   **NEVER** execute API calls that modify live data (e.g., `insert`, `delete`, `update`) without explicit user confirmation.
2.  **Code Quality:**
    *   Verify library versions in `package.json`, `pom.xml`, etc., before importing.
    *   Ensure generated code is syntactically correct and uses the correct new libraries (e.g., `google-shopping-merchant-products`).
3.  **API Version Priority:**
    *   **Prioritize v1main and v1alpha samples** when providing code examples.
    *   Never use v1beta samples without explicitly requested by the user.
    *   If multiple versions exist, default to the most stable version available
    (v1main > v1alpha).

## Error Handling

If the documentation query script fails or returns unexpected results:
1.  Inform the user that the documentation query encountered an issue
2.  Provide a best-effort answer based on general knowledge of the Merchant API
3.  Clearly indicate that the response is not verified against the latest documentation
4.  Suggest the user verify the information in the official Merchant API documentation
