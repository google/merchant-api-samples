# Merchant API Agent Skills

**Merchant API Agent Skills** is an Agent Skill that provides
specialized capabilities for Merchant API developers.

This Agent Skill encapsulates domain expertise, best practices, and migration
workflows to help developers integrate, debug, and migrate their Shopping
integrations more efficiently using AI-powered CLI tools like
[Antigravity CLI](https://antigravity.google) or
[Claude Code](https://code.claude.com).

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

### For Antigravity CLI Users

**Prerequisites:**

Antigravity CLI must be installed. You can install it by running the scripts
below, or by downloading the package from the
[official download page](https://antigravity.google/download#antigravity-cli).

```bash
# macOS or Linux
curl -fsSL https://antigravity.google/cli/install.sh -o install.sh
bash install.sh
rm install.sh

# Windows PowerShell
irm https://antigravity.google/cli/install.ps1 -OutFile install.ps1
.\install.ps1
Remove-Item install.ps1
```

Verify the installation:

```bash
agy --version
```

**Install Merchant API Agent Skill:**

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/google/merchant-api-samples.git
   cd merchant-api-samples
   ```

2. **Install the Skill:**

   You can install the skill either globally or for a specific project.

   **Option 1: Global Installation (Recommended)**

   Copy the skill directory to your global Antigravity skills directory:

   ```bash
   # Create the global skills directory if it doesn't exist
   mkdir -p ~/.gemini/antigravity-cli/skills/

   # Copy the skill directory
   cp -r agent-skills/mapi-developer-assistant \
     ~/.gemini/antigravity-cli/skills/
   ```

   **Option 2: Project-specific Installation**

   If you want the skill to be available only within a specific project
   directory:

   ```bash
   # Navigate to your project directory
   cd /path/to/your/project

   # Create the project skills directory if it doesn't exist
   mkdir -p .agents/skills/

   # Copy the skill directory
   cp -r /path/to/merchant-api-samples/agent-skills/mapi-developer-assistant \
     .agents/skills/
   ```

**Verify Installation:**

Start Antigravity CLI in your project or home directory:

```bash
agy
```

In the prompt, type `/skills` to open the skills panel. You should see
`mapi-developer-assistant` in the list.

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

To view available skills inside the Antigravity CLI, type `/skills` in the
prompt.

To uninstall the skill, simply delete the `mapi-developer-assistant` directory
from where you copied it (either `~/.gemini/antigravity-cli/skills/` or your
project's `.agents/skills/` directory).

**Note:** Unlike Antigravity CLI, other agents don't have `/skills` commands.
The skills activate automatically when your question matches their description.