# CLAUDE.md - AI Assistant Guide

**Repository**: websamnghe
**Last Updated**: 2025-11-16
**Status**: New Repository - Initial Setup

## Overview

This document serves as a comprehensive guide for AI assistants (like Claude) working on this codebase. It contains essential information about the project structure, development workflows, coding conventions, and best practices.

## Repository Status

This is a **new repository** currently in its initial setup phase. As the codebase evolves, this document should be updated to reflect:
- Technology stack decisions
- Architecture patterns
- Coding standards
- Testing strategies
- Deployment workflows

## Table of Contents

1. [Project Structure](#project-structure)
2. [Technology Stack](#technology-stack)
3. [Development Workflow](#development-workflow)
4. [Coding Conventions](#coding-conventions)
5. [Git Workflow](#git-workflow)
6. [Testing Strategy](#testing-strategy)
7. [AI Assistant Guidelines](#ai-assistant-guidelines)
8. [Common Tasks](#common-tasks)

---

## Project Structure

> **Note**: This section should be updated as the project structure is established.

```
websamnghe/
├── src/              # Source code (to be created)
├── tests/            # Test files (to be created)
├── docs/             # Documentation (to be created)
├── config/           # Configuration files (to be created)
└── CLAUDE.md         # This file
```

### Key Directories (Template)

- **`/src`**: Main application source code
- **`/tests`**: Unit, integration, and e2e tests
- **`/docs`**: Additional documentation
- **`/config`**: Configuration files for different environments
- **`/scripts`**: Build and deployment scripts
- **`/public`**: Static assets (if web application)

---

## Technology Stack

> **To Be Determined**: Update this section once technology decisions are made.

### Suggested Categories to Document:

- **Frontend**: (e.g., React, Vue, Angular, vanilla JS)
- **Backend**: (e.g., Node.js, Python, Go, Java)
- **Database**: (e.g., PostgreSQL, MongoDB, Redis)
- **Build Tools**: (e.g., Webpack, Vite, esbuild)
- **Testing**: (e.g., Jest, Vitest, Pytest, Mocha)
- **Deployment**: (e.g., Docker, Kubernetes, Vercel, AWS)

---

## Development Workflow

### Initial Setup

```bash
# Clone the repository
git clone <repository-url>
cd websamnghe

# Install dependencies (once package.json is created)
npm install  # or yarn install, pnpm install

# Set up environment variables
cp .env.example .env  # Edit with your local settings

# Run development server
npm run dev  # or appropriate command
```

### Daily Workflow

1. **Sync with remote**: `git fetch origin`
2. **Create feature branch**: `git checkout -b feature/your-feature-name`
3. **Make changes**: Develop and test locally
4. **Commit changes**: Follow commit message conventions
5. **Push and PR**: Push to remote and create pull request

---

## Coding Conventions

### General Principles

1. **Write clear, self-documenting code**
2. **Follow DRY (Don't Repeat Yourself) principle**
3. **Keep functions small and focused (single responsibility)**
4. **Use meaningful variable and function names**
5. **Comment complex logic, not obvious code**

### Naming Conventions

> Update these based on chosen language/framework:

- **Files**: `kebab-case` for files (e.g., `user-service.js`)
- **Variables/Functions**: `camelCase` (e.g., `getUserData`)
- **Classes/Components**: `PascalCase` (e.g., `UserProfile`)
- **Constants**: `UPPER_SNAKE_CASE` (e.g., `MAX_RETRY_COUNT`)

### Code Style

- **Indentation**: 2 or 4 spaces (specify preference)
- **Line Length**: Max 80-120 characters
- **Quotes**: Single or double (be consistent)
- **Semicolons**: Use or omit (be consistent)
- **Trailing Commas**: Yes/No in multiline (specify)

### Example Code Template

```javascript
/**
 * Description of what this function does
 * @param {type} paramName - Description
 * @returns {type} Description
 */
function exampleFunction(paramName) {
  // Implementation
  return result;
}
```

---

## Git Workflow

### Branch Naming Convention

```
feature/short-description     # New features
bugfix/issue-description      # Bug fixes
hotfix/critical-fix           # Production hotfixes
refactor/what-is-refactored   # Code refactoring
docs/what-documentation       # Documentation updates
test/what-is-tested           # Test additions/updates
```

### Commit Message Format

Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, no logic change)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks, dependency updates

**Examples:**
```
feat(auth): add user login functionality

Implements JWT-based authentication with refresh tokens.
Includes login, logout, and token refresh endpoints.

Closes #123
```

```
fix(api): resolve null pointer in user service

Adds null check before accessing user.profile to prevent crashes
when profile data is missing.
```

### Pull Request Guidelines

1. **Title**: Clear, descriptive summary
2. **Description**:
   - What changed and why
   - How to test
   - Screenshots (if UI changes)
   - Related issues
3. **Review**: At least one approval required (adjust as needed)
4. **Tests**: All tests must pass
5. **Conflicts**: Resolve before merging

---

## Testing Strategy

### Test Types

1. **Unit Tests**: Test individual functions/components in isolation
2. **Integration Tests**: Test interaction between modules
3. **E2E Tests**: Test complete user workflows
4. **Performance Tests**: Benchmark critical paths

### Testing Conventions

```javascript
// Example test structure
describe('ComponentName or FeatureName', () => {
  describe('specificMethod or scenario', () => {
    it('should do something specific', () => {
      // Arrange
      const input = setupTestData();

      // Act
      const result = functionUnderTest(input);

      // Assert
      expect(result).toBe(expectedValue);
    });
  });
});
```

### Coverage Goals

- **Minimum**: 70% code coverage
- **Target**: 80%+ for critical business logic
- **100%**: For utility functions and shared libraries

---

## AI Assistant Guidelines

### When Working on This Codebase

1. **Always Read Before Writing**
   - Use `Read` tool to understand existing code
   - Check for similar patterns in the codebase
   - Maintain consistency with existing style

2. **Use Task Management**
   - Use `TodoWrite` for multi-step tasks
   - Track progress clearly
   - Mark tasks complete only when fully done

3. **Security First**
   - Never introduce vulnerabilities (XSS, SQL injection, etc.)
   - Validate and sanitize all user inputs
   - Use parameterized queries for databases
   - Follow OWASP Top 10 guidelines

4. **Testing Requirements**
   - Write tests for new features
   - Update tests when modifying existing code
   - Run test suite before committing
   - Don't mark tasks complete if tests fail

5. **Code Quality**
   - Follow established conventions in this file
   - Refactor duplicated code
   - Add comments for complex logic
   - Keep functions focused and small

6. **Git Practices**
   - Create feature branches for all work
   - Write clear commit messages
   - Keep commits atomic and logical
   - Don't commit sensitive data (.env files, secrets, etc.)

### Code Review Checklist

Before completing a task, verify:

- [ ] Code follows project conventions
- [ ] No security vulnerabilities introduced
- [ ] Tests written and passing
- [ ] Documentation updated if needed
- [ ] No console.logs or debug code left behind
- [ ] Error handling implemented
- [ ] Edge cases considered
- [ ] Performance implications considered

### Common Pitfalls to Avoid

1. **Don't** assume the tech stack - check package.json first
2. **Don't** create files unnecessarily - prefer editing existing ones
3. **Don't** skip error handling
4. **Don't** hardcode values that should be configurable
5. **Don't** commit directly to main/master branch
6. **Don't** push without running tests
7. **Don't** use deprecated APIs or libraries

---

## Common Tasks

### Adding a New Feature

1. Create feature branch: `git checkout -b feature/feature-name`
2. Implement the feature with tests
3. Update documentation
4. Run full test suite
5. Commit with conventional commit message
6. Push and create pull request

### Fixing a Bug

1. Reproduce the bug
2. Write a failing test that demonstrates the bug
3. Fix the bug
4. Verify the test now passes
5. Check for similar bugs elsewhere
6. Commit with `fix:` prefix

### Refactoring Code

1. Ensure good test coverage exists
2. Make incremental changes
3. Run tests after each change
4. Update documentation if public APIs change
5. Commit with `refactor:` prefix

### Updating Dependencies

1. Check for breaking changes in changelogs
2. Update one dependency at a time (for easier rollback)
3. Run full test suite
4. Test critical user flows manually
5. Commit with `chore(deps):` prefix

---

## Project-Specific Notes

> This section should contain project-specific information such as:

### Environment Variables

Document all required environment variables:

```
# Example template
DATABASE_URL=          # PostgreSQL connection string
API_KEY=              # Third-party API key
JWT_SECRET=           # Secret for JWT signing
NODE_ENV=             # development | production | test
```

### External Services

List any external services/APIs used:
- Service name, purpose, documentation link

### Important Files

Highlight critical files that require extra care:
- Configuration files
- Database migrations
- Security-related code

### Known Issues

Document any known issues or technical debt:
- Issue description
- Workaround if available
- Plan to resolve

---

## Resources

### Documentation Links

- [Project Documentation](link-to-docs)
- [API Documentation](link-to-api-docs)
- [Design System](link-to-design-system)
- [Deployment Guide](link-to-deployment)

### Communication

- **Issue Tracker**: GitHub Issues
- **Discussions**: GitHub Discussions
- **Team Chat**: (Slack/Discord/etc.)

---

## Maintenance

### Updating This Document

This document should be updated when:
- Project structure changes significantly
- New conventions are adopted
- Technology stack changes
- New team members join (add common questions)
- After retrospectives reveal process improvements

**Last Updated By**: Claude AI
**Next Review**: After initial project setup complete

---

## Version History

| Date       | Changes                                    | Updated By |
|------------|--------------------------------------------|------------|
| 2025-11-16 | Initial creation for new repository        | Claude AI  |

---

**Note to Future AI Assistants**: This document is your primary reference for working on this codebase. Always read it first before making changes. Keep it updated as the project evolves. When in doubt, ask the user for clarification rather than making assumptions.
