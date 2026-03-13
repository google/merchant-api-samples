# Merchant API Agent Skills

**Merchant API Agent Skills** is an Agent Skill that provide
specialized capabilities for Merchant API developers.

This Agent Skill encapsulates domain expertise, best practices, and migration workflows to help developers integrate, debug, and migrate their Shopping integrations more efficiently using AI-powered CLI tools like [Gemini CLI](https://geminicli.com) or [Claude Code](https://code.claude.com).

## Available Skills

### **mapi-developer-assistant**
General-purpose Merchant API expertise for documentation queries, code
generation, and troubleshooting.

**Features:**

- API Documentation Expert: Deep understanding of Merchant API resources, methods, and fields
- Migration Guidance: Helps migrate from Content API to Merchant API
- Code Generation: Generates idiomatic code snippets (Python, Java, PHP, etc.)
- Error Troubleshooting: Analyzes API error responses and suggests fixes

**Use when:** You need quick answers about Merchant API, code examples, or error
troubleshooting.

## Installation & Setup

### For Gemini CLI Users

**Prerequisites:**

Agent Skills support is available in Gemini CLI v0.24.0 or higher.
Recommend v0.33.1+ for stable Agentic Workflows.

1. **Install or Update Gemini CLI:**

   ```bash
   # Install/Update to latest version
   npm install -g @google/gemini-cli@latest

   # Verify version
   gemini --version
   
   # Should show v0.24.0 or higher
   ```
   

2. **Enable Agent Skills:**
   
   Open Gemini CLI settings:

   ```bash
   gemini
   # In the prompt, type:
   /settings
   ```

   Search for "Skills" and enable `experimental.skills`
   
   Or configure it directly:

   ```bash
   gemini config set experimental.skills true
   ```

3. **Restart Gemini CLI** for the change to take effect

Note: If you are on v0.30.0+, skills are enabled by default for most accounts. For more details, see the [Agent Skills documentation](https://geminicli.com/docs/cli/skills/).

**Install Merchant API Agent Skill:**

```bash
# Clone the Repository
git clone https://github.com/google/merchant-api-samples.git

# Navigate to the specific skill directory
cd agent-skills/mapi-developer-assistant

# Install the skill locally
gemini skills install .

# Verify Installation
gemini skills list
```

You should see `mapi-developer-assistant` in the list.

### For Other AI Agents (Claude, Kiro, etc.)

1. **Clone this repository:**

   ```bash
   git clone https://github.com/google/merchant-api-samples.git
   ```

2. **Add skills to your agent:**

   **Claude Code:**

   ```bash
   # Create the skills directory if it doesn't exist
   mkdir -p .claude/skills/

   # Copy the skill directory to your project
   cp -r merchant-api-samples/agent-skills/mapi-developer-assistant .claude/skills/

   ```

   **Kiro or other agents:**
   - Add the skill directory (agent-skills/mapi-developer-assistant) to your project workspace.
   - Ensure your agent has read access to all files in the skill directory (SKILL.md and reference files)

## Usage

### mapi-developer-assistant

**Activation:** Automatically activates when you ask Merchant API-related
questions.

**Example Prompts:**

- "How do I insert a product using the Merchant API in Python?"
- "What is the difference between ProductInput and Product?"
- "I'm getting a '400 Bad Request' with validation error. How do I fix this?"
- "Show me how to handle authentication in Merchant API"

**Management:**

```bash
gemini skills list # View all installed skills
gemini skills disable mapi-developer-assistant
gemini skills enable mapi-developer-assistant
```

**Note:** Unlike Gemini CLI, other agents don't have `/skills` commands. The
skills activate automatically when your question matches their description.