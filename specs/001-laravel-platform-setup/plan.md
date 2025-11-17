# Implementation Plan: [FEATURE]

**Branch**: `[###-feature-name]` | **Date**: [DATE] | **Spec**: [link]
**Input**: Feature specification from `/specs/[###-feature-name]/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

[Extract from feature spec: primary requirement + technical approach from research]

## Technical Context

<!--
  ACTION REQUIRED: Replace the content in this section with the technical details
  for the project. The structure here is presented in advisory capacity to guide
  the iteration process.
-->

**Language/Version**: [e.g., PHP 8.2+, Laravel 10.x or NEEDS CLARIFICATION]  
**Primary Dependencies**: [e.g., Laravel, Passport.js, Supabase client, MUI or NEEDS CLARIFICATION]  
**Storage**: [e.g., Supabase/PostgreSQL, Redis cache or NEEDS CLARIFICATION]  
**Testing**: [e.g., PHPUnit, Jest, React Testing Library or NEEDS CLARIFICATION]  
**Target Platform**: [e.g., Web application, API server or NEEDS CLARIFICATION]
**Project Type**: [monorepo with packages - determines source structure]  
**Performance Goals**: [domain-specific, e.g., 1000 req/s, <200ms response time or NEEDS CLARIFICATION]  
**Constraints**: [domain-specific, e.g., <200ms p95, Supabase free tier limits or NEEDS CLARIFICATION]  
**Scale/Scope**: [domain-specific, e.g., 10k users, 50 API endpoints, 20 UI screens or NEEDS CLARIFICATION]

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

Verify compliance with `.specify/memory/constitution.md` core principles:

- [ ] **Monorepo Package Architecture**: Feature organized in `packages/` with frontend (`-frt`) and backend (`-srv`) separation if applicable. Each package has `base/` directory.
- [ ] **Bilingual Documentation**: English documentation planned first, Russian translation follows with identical structure.
- [ ] **Database-First with Supabase**: Uses Supabase/PostgreSQL with Passport.js authentication. Design extensible for other DBMS.
- [ ] **Laravel Best Practices**: Follows Laravel conventions (Eloquent, service container, form requests, API resources). Frontend uses Material UI.
- [ ] **Clean Architecture**: No legacy code replication. Uses established patterns (Clusters/Domains/Resources model).
- [ ] **GitHub Process**: Issue created before implementation, proper labels applied, PR follows guidelines.

If any principle cannot be met, document justification in Complexity Tracking section.

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)
<!--
  ACTION REQUIRED: Replace the placeholder tree below with the concrete layout
  for this feature. Delete unused options and expand the chosen structure with
  real paths (e.g., packages/clusters-frt, packages/clusters-srv). The delivered 
  plan must not include Option labels.
-->

```text
# Monorepo structure (PNPM workspaces)
packages/
├── [feature-name]-frt/       # Frontend package (if applicable)
│   ├── base/                 # Base implementation
│   │   ├── src/
│   │   │   ├── components/
│   │   │   ├── pages/
│   │   │   └── services/
│   │   └── tests/
│   ├── package.json
│   └── README.md (+ README-RU.md)
│
└── [feature-name]-srv/       # Backend package (if applicable)
    ├── base/                 # Base implementation
    │   ├── app/
    │   │   ├── Models/
    │   │   ├── Http/
    │   │   │   ├── Controllers/
    │   │   │   └── Requests/
    │   │   └── Services/
    │   ├── database/
    │   │   └── migrations/
    │   ├── routes/
    │   └── tests/
    │       ├── Feature/
    │       └── Unit/
    ├── composer.json
    └── README.md (+ README-RU.md)

# Root configuration
package.json              # PNPM workspace configuration
pnpm-workspace.yaml      # Workspace definition
```

**Structure Decision**: [Document the selected structure and reference the real
directories captured above. Specify whether this feature requires frontend-only,
backend-only, or both packages.]

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |
