# Git Setup Instructions for FITDECK Project

## Step 1: Install Git (if not already installed)

If Git is not installed on your system:

1. Download Git for Windows from: https://git-scm.com/download/win
2. Run the installer and follow the installation wizard
3. **Important**: During installation, make sure to select "Add Git to PATH" option
4. Restart your terminal/command prompt after installation

## Step 2: Verify Git Installation

Open PowerShell or Command Prompt and run:
```bash
git --version
```

If Git is installed correctly, you should see a version number.

## Step 3: Push Your Project

### Option A: Use the Automated Script

Simply run the `push_to_github.bat` file:
```bash
.\push_to_github.bat
```

### Option B: Manual Commands

If you prefer to run commands manually, execute these in order:

```bash
# Initialize Git repository (if not already done)
git init

# Add the remote repository
git remote add origin https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git

# Add all files to staging
git add .

# Commit the files
git commit -m "Initial commit: FITDECK project"

# Set the default branch to main
git branch -M main

# Push to GitHub
git push -u origin main
```

## Step 4: Authentication

When you run `git push`, you may be prompted for credentials:

- **Username**: Your GitHub username
- **Password**: You'll need to use a **Personal Access Token** (not your GitHub password)

### Creating a Personal Access Token:

1. Go to GitHub.com and sign in
2. Click your profile picture → Settings
3. Scroll down to "Developer settings" (left sidebar)
4. Click "Personal access tokens" → "Tokens (classic)"
5. Click "Generate new token" → "Generate new token (classic)"
6. Give it a name (e.g., "FITDECK Project")
7. Select scopes: Check "repo" (full control of private repositories)
8. Click "Generate token"
9. **Copy the token immediately** (you won't see it again!)
10. Use this token as your password when pushing

## Troubleshooting

### If you get "remote origin already exists" error:
```bash
git remote remove origin
git remote add origin https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git
```

### If you get authentication errors:
- Make sure you're using a Personal Access Token, not your password
- Check that the token has "repo" permissions

### If you need to update files later:
```bash
git add .
git commit -m "Your commit message"
git push
```

## Your Repository URL
https://github.com/adityamevcha141735/FITDECK_WT_92510103039_

