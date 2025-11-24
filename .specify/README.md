# .specify Directory Structure

This directory contains all specification-driven development artifacts for the Universo Platformo Laravel project.

## Directory Organization

```
.specify/
├── memory/              # Project-wide knowledge and constraints
│   ├── constitution.md  # Core principles and governance
│   └── *.md            # Other project memory documents
├── scripts/            # Automation scripts for spec workflow
│   └── bash/          # Bash scripts for feature management
├── specs/             # Feature specifications (one per feature)
│   └── NNN-feature-name/  # Feature directory (NNN = 3-digit number)
│       ├── spec.md         # Feature specification
│       ├── plan.md         # Implementation plan
│       ├── tasks.md        # Task breakdown
│       ├── data-model.md   # Data models (optional)
│       ├── research.md     # Research notes (optional)
│       ├── quickstart.md   # Quick start guide (optional)
│       ├── checklists/     # Requirement checklists (optional)
│       └── contracts/      # API contracts (optional)
└── templates/         # Templates for creating new specs
    ├── spec-template.md     # Template for spec.md
    ├── plan-template.md     # Template for plan.md
    ├── tasks-template.md    # Template for tasks.md
    └── *.md                # Other templates
```

## File Naming Conventions

### Core Specification Files (lowercase)
These are the primary specification documents that follow the spec-driven development workflow:

- `spec.md` - Feature specification with requirements and user stories
- `plan.md` - Technical implementation plan
- `tasks.md` - Detailed task breakdown for implementation
- `data-model.md` - Data models and database schemas
- `research.md` - Research notes and technical decisions
- `quickstart.md` - Quick start and testing guide

### Additional Documentation (SCREAMING_SNAKE_CASE)
Supplementary reports and documentation use uppercase with underscores:

- `IMPROVEMENTS.md` - Improvement suggestions
- `SUMMARY.md` - Feature summary
- `PATTERN_INTEGRATION_REPORT.md` - Pattern analysis reports
- etc.

## Workflow Commands

The following commands are available for working with specifications:

- `/speckit.specify` - Create a new feature specification
- `/speckit.clarify` - Clarify ambiguities in specification
- `/speckit.plan` - Generate implementation plan
- `/speckit.tasks` - Generate task breakdown
- `/speckit.analyze` - Analyze consistency across artifacts
- `/speckit.implement` - Begin implementation
- `/speckit.constitution` - View/update constitution

## Memory System

The `memory/` directory contains project-wide knowledge:

### constitution.md
The project constitution defines core principles that govern all development:

1. **Monorepo Package Architecture** - All features in packages/
2. **Bilingual Documentation** - English + Russian
3. **Database-First with Supabase** - PostgreSQL-based
4. **Laravel Full-Stack with React Frontend** - Inertia.js + Material UI
5. **Clean Architecture & Incremental Development** - Three-tier pattern
6. **GitHub Process Compliance** - Issues, PRs, labels
7. **Reference Implementation Alignment** - universo-platformo-react
8. **Build and Deployment Standards** - Vite, Composer, proper .gitignore

These principles are **non-negotiable** and must be followed in all development.

## Scripts

Automation scripts in `scripts/bash/`:

- `check-prerequisites.sh` - Validate feature branch and spec files
- `common.sh` - Shared functions for all scripts
- `create-new-feature.sh` - Create new feature directory
- `setup-plan.sh` - Initialize plan.md
- `update-agent-context.sh` - Update GitHub agent context

## Usage

1. **Start a new feature**:
   ```bash
   git checkout -b 002-my-feature
   # Run /speckit.specify to create specification
   ```

2. **Work on existing feature**:
   ```bash
   git checkout 001-laravel-platform-setup
   # Feature spec is at .specify/specs/001-laravel-platform-setup/
   ```

3. **Validate current feature**:
   ```bash
   .specify/scripts/bash/check-prerequisites.sh --json
   ```

## Integration with GitHub Agents

GitHub Copilot agents in `.github/agents/` have access to `.specify/` for:

- Reading specifications during task generation
- Analyzing consistency across spec, plan, and tasks
- Implementing features according to specifications
- Validating compliance with constitution

All agents understand the `.specify/specs/` structure and can locate feature documentation automatically.

## Best Practices

1. **Always work on a feature branch** named `NNN-feature-name`
2. **Create specification before implementation** using `/speckit.specify`
3. **Follow the constitution** - it defines all architectural principles
4. **Keep specs updated** - reflect actual implementation state
5. **Use templates** - they enforce consistency
6. **Bilingual docs** - English first, then Russian translation

---

For more information, see:
- Project constitution: `.specify/memory/constitution.md`
- Contributing guidelines: `CONTRIBUTING.md`
- Architecture documentation: `ARCHITECTURE.md`
